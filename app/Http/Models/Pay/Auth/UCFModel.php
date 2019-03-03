<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/10/19
 * Time: 上午11:37
 */

namespace App\Http\Models\Pay\Auth;


use App\Http\Models\Pay\PayModel;
use App\Services\Pay\Auth\UCF\UCFPay;
use App\Tools\ToolMoney;

class UCFModel extends PayModel
{
    const APP_REQUEST_FROM_WAP  = 'wap',
        APP_REQUEST_FROM_PC   = 'pc',
        APP_REQUEST_FROM_APP  = 'app';

    private $service;

    public function __construct()
    {
        parent::__construct('UCFPAY_CONFIG');
        

    }

    /**
     * @param array $params
     * @return array
     * 宝付支付加密方法
     */
    public function encrypt(array $params)
    {
        $config         = $this->config;

        $from           = strtolower($params['platform']);  //来源 PC WAP APP

        $parameter = [

            'secId'         => $config['sec_id'],                //答名算法
            'version'       => $config['version'],              //版本号
            'merchantId'    => $config['merchant_id'],          //商户号
            'mobileNo'      => $params['phone'],                //用户手机号
            'outOrderId'    => $params['order_id'],             //订单号
            'userId'        => $params['user_id'],              //用户ID
            'realName'      => $params['name'],                 //用户姓名
            'cardNo'        => strtoupper($params['id_card']),  //身份证号
            'cardType'      => $config['card_type'],            //证件类型
            'amount'        => $params['cash'] * 100,           //充值金额
            'returnUrl'     => $params['return_url'],           //支付完成页面跳转链接
            'noticeUrl'     => $params['notify_url'],           //支付结果回调地址
            'bankNo'        => trim($params['card_no']),        //银行卡号
            'bankCode'      => $params['bank_code'],            //银行编码
            'bankName'      => $params['bank_name'],            //银行名称

        ];

        if($from == self::APP_REQUEST_FROM_PC){

            $source = 'PC';
            $service = 'MOBILE_CERTPAY_PC_ORDER_CREATE';

            $parameter['merchantName']  = $config['merchant_name'];        //商户名称
            $parameter['productName']   = $config['product_name'];         //产品名称

        }elseif($from == self::APP_REQUEST_FROM_WAP){
            $source = 'H5';
            $service = 'MOBILE_CERTPAY_H5_ORDER_CREATE';
            
        }else{
            $source = 'H5';
            $service = 'MOBILE_CERTPAY_ORDER_CREATE';
            unset($parameter['bankCode'],$parameter['bankName'],$parameter['bankNo'],$parameter['returnUrl']);
        }

        $parameter['service'] = $service;

        $server = new UCFPay($source,$config);

        $parameter['reqSn'] = $server->getReq($service,$params['order_id']);

        $sign = $server->encrypt($parameter);

        $parameter['sign'] = $sign;

        $url = $config['gateway'];

        if($from == self::APP_REQUEST_FROM_APP){

            $result         = $server->post($parameter,$url);
            \Log::info(__METHOD__." : ".__LINE__." UCF_01_ ",[ $parameter, $result ]);
            //APP端创建订单失败
            if($result['respCode'] != '00'){

                throw new \Exception($result['respMsg']);
            }

            $parameter['bankNo'] = $params['card_no'];

        }

        $return = [
            'url'       => $url,
            'parameter' => $parameter,
        ];

        return $return;

    }

    /**
     * @param array $params
     * @return array
     * 宝付支付解密方法
     */
    public function decrypt(array $params)
    {
        $decryptType = $params['decrypt_type'];

        $method = 'decrypt'.ucwords($decryptType);
        $this->$method($params);

        return $this->decryptReturn;
    }

    /**
     * @param array $params
     * 解绑银行卡
     */
    public function unbind(array $params){

        $config         = $this->config;

        $service = 'MOBILE_CERTPAY_UNBIND_CARD';

        $parameter = [
            'service'       => $service,
            'secId'         => $config['sec_id'],                //答名算法
            'version'       => $config['version'],              //版本号
            'merchantId'    => $config['merchant_id'],          //商户号
            'userId'        => $params['user_id'],              //用户ID
            'bankCardNo'    => $params['card_no'],              //银行卡号
        ];

        $server = new UCFPay('PC',$config);

        $parameter['reqSn'] = $server->getReq($service,$params['card_no'].$params['user_id']);

        $parameter['sign'] = $server->encrypt($parameter);


        $url            = $config['gateway'];
        $result         = $server->post($parameter,$url);

        \Log::info(__METHOD__." : ".__LINE__." UCF_UNBIND_ ",[ $parameter, $result ]);
        if($result['respCode'] == '00'){
            $return['trade_status'] = self::TRADE_SUCCESS;
        }else{

            $return['trade_status'] = self::TRADE_FAIL;

        }

        $return['msg'] = $result['respMsg'];

        return $return;

    }
    /**
     * @param $params
     * @return array
     * 异步回调通知解密
     */
    private function decryptNotice($params){

        $server         = new UCFPay('PC',$this->config);

        $data = [

            'sign' => $params['sign'],
            'amount'    => $params['amount'],
            'tranTime'  => $params['tranTime'],
            'tradeNo'   => $params['tradeNo'],
            'merchantId'    => $params['merchantId'],
            'bankName'      => $params['bankName'],
            'orderStatus'   => $params['orderStatus'],
            'bankCardNo'    => $params['bankCardNo'],
            'outOrderId'    => $params['outOrderId'],
            'bankId'        => $params['bankId'],

        ];

        $result         = $server->decrypt($data);
        \Log::info(__METHOD__." : ".__LINE__." UCF_NOTIFY_ ",[ $data, $result ]);
        if($result){

            $this->decryptReturn['verify_status']  = true;      //签名状态

            if($params['orderStatus'] == '00'){

                $this->decryptReturn['trade_no']       = $data['tradeNo'];  //交易流水号
                $this->decryptReturn['amount']         = ToolMoney::formatDbCashDelete($data['amount']);//订单金额
                $this->decryptReturn['trade_status']   = self::TRADE_SUCCESS;
                $this->decryptReturn['msg']            = '支付成功';
            }
            $this->decryptReturn['order_id']        = $data['outOrderId'];  //订单号

        }

    }

    /**
     * @param $params
     * 前端支付跳转解密
     */
    private function decryptReturn($params){

        $this->decryptReturn['verify_status']  = true;      //签名状态
        \Log::info(__METHOD__." : ".__LINE__." UCF_RETURN_ ",[ $params ]);
        if($params['payStatus'] == '00'){

            $this->decryptReturn['trade_status']   = self::TRADE_SUCCESS;
            $this->decryptReturn['msg']             = '支付成功';


        }
        $this->decryptReturn['order_id']        = $params['outOrderId'];  //订单号

    }

    /**
     * @param array $params
     * @return array
     * 宝付查单方法
     */
    public function search(array $params){

        $config         = $this->config;

        $server         = new UCFPay('PC',$config);

        $service = 'REQ_WITHOIDING_QUERY';
        $orderId = $params['order_id'];

        $parameter = [

            'service'       => $service,
            'secId'         => $config['sec_id'],                //答名算法
            'version'       => $config['version'],              //版本号
            'merchantId'    => $config['merchant_id'],          //商户号
            'reqSn'         => $server->getReq($service,$orderId),
            'merchantNo'    => $orderId,
        ];

        $parameter['sign'] = $server->encrypt($parameter);
        
        \Log::info(__METHOD__.' : '.__LINE__." SEARCH- " , $parameter );
        $url            = $config['gateway'];
        $result         = $server->post($parameter,$url);

        $this->format($result);

        return $this->searchReturn;
    }

    /**
     * @param array $returnData
     * @desc 格式化
     */
    public function format(array $returnData){
        \Log::info(__METHOD__.' : '.__LINE__, $returnData);
        $resCode    = isset($returnData['resCode'])?$returnData['resCode']:"";
        $resMessage= isset($returnData['resMessage'])?$returnData['resMessage']:"";
        $errorMsgArr= $this->getErrorCodeInfo();
        $resMessage = $resMessage?$resMessage:(isset($errorMsgArr[$resCode])?$errorMsgArr[$resCode]:"");
        //查询订单成功
        if($returnData['status'] == 'S'){

            $tradeStatus = self::TRADE_SUCCESS;
            $this->searchReturn['trade_no']     = $returnData['tradeNo'];
            $msg = '支付成功';
        }elseif($returnData['status'] == 'I'){

            $tradeStatus = self::TRADE_WAITING;
            $msg = $resMessage ? $resMessage :'等待支付';

        }else{

            $tradeStatus = self::TRADE_FAIL;
            $msg = $resMessage ? $resMessage :'支付失败';
        }
        $cash   = isset($returnData["amount"])?intval($returnData["amount"] / 100):"-1";
        $this->searchReturn['status']       = $tradeStatus;     //支付状态
        $this->searchReturn['msg']          = $msg;
        $this->searchReturn['order_id']     = $returnData['merchantNo'];
        $this->searchReturn['cash']         = $cash;

    }


    /**
     * @desc  错误应信息
     **/
    public function getErrorCodeInfo($resCode=''){
        return [
            '20001'  => '查询用户绑定银行卡失败',
            '20002'  => '查询用户信息失败',
            '20003'  => '用户注册失败',
            '20004'  => '该银行卡已经被您的同名支付账户绑定，请使用其他银行卡或者切 换支付用户。如有疑问请致电先锋支付客服:400-8189-889',
            '20005'  => '您输入的验证码已被使用或已过有效期，请您重新获取',
            '20006'  => '您输入的验证码有误，请确认后重新输入',
            '20007'  => '解绑银行卡失败',
            '20008'  => '您输入的卡号与所选银行不符',

            '30001'  => '查询订单失败',
            '30002'  => '创建订单失败',
            '30003'  => '认证支付失败，原因可能是开卡预留手机号错误，或者姓名、身份证与银行卡不一致',

            '40001'  => '支付失败，请稍后重试',

            '70001'  => '该银行卡暂不支持',
            '70002'  => '查询卡bin失败',
            '70003'  => '暂不支持信用卡',
            '70004'  => '请输入有效的储蓄卡卡号',
            '70005'  => '请输入正确的银行卡号',

            '80001'  => '由于渠道维护，认证支付暂停服务，给您带来的不便敬请谅解,如有 疑问请咨询400-8189-889',
            '99999'  => '服务器繁忙，请稍后重试',
        ];
    }

}