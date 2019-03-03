<?php
/**
 * User: zhangshuang
 * Date: 16/5/10
 * Time: 12:57
 * Desc: 连连认证支付
 */

namespace App\Http\Models\Pay\Auth;
use App\Http\Models\Pay\PayModel;
use App\Services\Pay\Auth\LL\LLPay;

class LLModel extends PayModel{

    const APP_REQUEST_FROM_WAP  = 'wap',
          APP_REQUEST_FROM_PC   = 'pc',
          APP_REQUEST_FROM_APP  = 'app';

    private $service;

    public function __construct()
    {
        parent::__construct('LLPAY_CONFIG');
        
        $this->service = new LLPay($this->config);

    }

    /**
     * @param array $params
     * @return array
     * 连连支付加密方法
     */
    public function encrypt(array $params)
    {
        // TODO: Implement encrypt() method.

        $config         = $this->config;

        $no_agree       = "";//协议号

        $busi_partner   = "101001";//支付类型
        $no_order       = $params['order_id'];//商户网站订单系统中唯一订单号，必填
        $money_order    = $params['cash'];//付款金额
        $name_goods     = "充值";//商品名称
        $url_order      = "";//订单地址
        $info_order     = "九斗鱼在线充值";//订单描述

        $pay_type       = "D";//支付方式
        $card_no        = $params["card_no"];//卡号
        $id_type        = "0";
        $acct_name      = $params["name"];
        $id_no          = $params["id_card"];

        $notifyUrl      = $params['notify_url'];
        $returnUrl      = $params['return_url'];
        $bank_code      = "";//银行网银编码

        $from           = strtolower($params['platform']);  //来源 PC WAP APP
        //风控参数
        $risk_data = array(
            "frms_ware_category"    =>  "2009",
            "user_info_mercht_userno"  => $params["user_id"],
            "user_info_dt_register" => date("YmdHis"),
            "user_info_full_name"   => $acct_name,
            "user_info_identify_type"   => "1",
            "user_info_identify_state" => "1",
            "user_info_id_no"       => $id_no
        );

        $flag_modify    = "0";//修改标记
        //$risk_item      = "";//风险控制参数
        $risk_item      = json_encode($risk_data);

        $shareing_data  = "";//分账信息数据
        $back_url       = "";//返回修改信息地址
        $valid_order    = "60";//订单有效期 分钟
        $userreq_ip     = str_replace(".", "_", $params['user_ip']);


        if($from == self::APP_REQUEST_FROM_APP){

            //构造要请求的参数数组，无需改动
            $parameter1 = array (
                "oid_partner"      => $config['OID_PARTNER'],
                "sign_type"        => $config['SIGN_TYPE'],
                "busi_partner"     => $busi_partner,
                "no_order"         => $no_order,
                "dt_order"         => date('YmdHis', time()),
                "name_goods"       => $name_goods,
                "info_order"       => $info_order,
                "money_order"      => $money_order,
                "notify_url"       => $notifyUrl,
                "valid_order"      => $valid_order,
                "risk_item"        => $risk_item,
            );
            $parameter2 = array (
                "user_id"          => $params["user_id"],
                "pay_type"         => $pay_type,
                "bank_code"        => $bank_code,
                "force_bank"       => 0,
                "id_type"          => $id_type,
                "id_no"            => $id_no,
                "acct_name"        => $acct_name,
                "card_no"          => $card_no,
                "no_agree"         => $no_agree,
            );

            $parameter1      = $this->service->encrypt($parameter1);
            $parameter       = array_merge($parameter1, $parameter2);

        }else {
            //构造要请求的参数数组，无需改动
            $parameter = array(
                "oid_partner" => $config['OID_PARTNER'],
                "sign_type" => $config['SIGN_TYPE'],
                //"userreq_ip"       => $userreq_ip,
                "id_type" => $id_type,
                "valid_order" => $valid_order,
                "user_id" => $params['user_id'],
                "busi_partner" => $busi_partner,
                "no_order" => $no_order,
                "dt_order" => date('YmdHis', time()),
                "name_goods" => $name_goods,
                "info_order" => $info_order,
                "money_order" => $money_order,
                "notify_url" => $params['notify_url'],
                "url_return" => $returnUrl,
                "url_order" => $url_order,
                "bank_code" => $bank_code,
                "pay_type" => $pay_type,
                "no_agree" => $no_agree,
                "shareing_data" => $shareing_data,
                "risk_item" => $risk_item,
                "id_no" => $id_no,
                "acct_name" => $acct_name,
                "flag_modify" => $flag_modify,
                "card_no" => $card_no,
                "back_url" => $back_url,
                //"query_version"    => $config['QUERY_VERSION'],
            );


            $version = '';
            switch ($from) {
                //微信端
                case self::APP_REQUEST_FROM_WAP: {

                    $secondConfig = $config['WAPAUTH'];
                    $version = $secondConfig['VERSION'];
                    $parameter['app_request'] = '3';
                    break;
                }
                case self::APP_REQUEST_FROM_APP:
                case self::APP_REQUEST_FROM_PC: {

                    $version = $config['VERSION'];
                    $secondConfig = $config['PCAUTH'];
                    $parameter['timestamp'] = date('YmdHis', time());
                    $parameter['userreq_ip'] = $userreq_ip;

                    break;
                }
            }

            $parameter['url_return'] = $returnUrl;
            $parameter['version'] = $version;

            $parameter      = $this->service->encrypt($parameter);

        }

        return [
            'parameter' => $parameter,
            'url'       => isset($secondConfig['API_GATEWAY']) ? $secondConfig['API_GATEWAY'] : '',
        ];
        
    }

    /**
     * @param array $params
     * 解密接口
     */
    public function decrypt(array $params)
    {
        // TODO: Implement decrypt() method.

        $sign           = $params['sign'];              //签名
        $decryptType    = $params['decrypt_type'];      //验证数据类型


        //回调数据签名验证
        if($decryptType === 'notice'){

            $data = array (
                'oid_partner' => $params["oid_partner"],
                'sign_type'   => $params["sign_type"],
                'dt_order'    => $params["dt_order"],
                'no_order'    => $params["no_order"],
                'oid_paybill' => $params["oid_paybill"],
                'money_order' => $params["money_order"],
                'result_pay'  => $params["result_pay"],
                'settle_date' => $params["settle_date"],
                'info_order'  => $params["info_order"],
                'pay_type'    => $params["pay_type"],
                'bank_code'   => $params["bank_code"],
                'no_agree'    => $params["no_agree"],
                'id_type'     => $params["id_type"],
                'id_no'       => $params["id_no"],
                'acct_name'   => $params["acct_name"]
            );

        }else{

            if(!isset($params['platform']) || $params['platform'] == self::APP_REQUEST_FROM_PC){
                //return数据签名验证
                $data = array (
                    'oid_partner' => $params['oid_partner'],
                    'sign_type'   => $params['sign_type'],
                    'dt_order'    => $params['dt_order'],
                    'no_order'    => $params['no_order'],
                    'oid_paybill' => $params['oid_paybill'],
                    'money_order' => $params['money_order'],
                    'result_pay'  => $params['result_pay'],
                    'settle_date' => $params['settle_date'],
                    'info_order'  => $params['info_order'],
                    'pay_type'    => $params['pay_type'],
                    'bank_code'   => $params['bank_code']
                );
            }else{
                $data["dt_order"]       = $params["dt_order"];
                $data["money_order"]    = $params["money_order"];
                $data["no_order"]       = $params["no_order"];
                $data["oid_partner"]    = $params["oid_partner"];
                $data["oid_paybill"]    = $params["oid_paybill"];
                $data["result_pay"]     = $params["result_pay"];
                $data["settle_date"]    = $params["settle_date"];
                $data["sign_type"]      = $params["sign_type"];
            }

        }

        //判断签名是否通过
        $verify_status = $this->service->decrypt($data,$sign);
        if($verify_status === true){

            $result_pay     =  $params["result_pay"];                           //支付结果

            $this->decryptReturn['verify_status']  = true;                      //签名状态
            $this->decryptReturn['trade_no']       = $params["oid_paybill"];  //交易流水号
            $this->decryptReturn['order_id']       = $params["no_order"];     //订单号
            $this->decryptReturn['amount']         = $params["money_order"];     //订单金额

            //支付成功
            if($result_pay == 'SUCCESS') {

                $this->decryptReturn['trade_status'] = self::TRADE_SUCCESS;
                $this->decryptReturn['msg']          = self::TRADE_SUCCESS_MSG;

            }
        }else{
            $this->decryptReturn['msg']          = self::TRADE_SIGN_ERROR;

        }

        return $this->decryptReturn;

    }


    /**
     * @param $orderId
     * 查单接口
     */
    public function search(array $params)
    {
        // TODO: Implement search() method.

        $orderId = $params['order_id'];
        $res = $this->service->search($orderId);

        //格式化处理
        $this->format($res);

        $this->searchReturn['order_id']       = $orderId;       //订单号

        return $this->searchReturn;
    }

    /**
     * @param $returnData
     * 返回结果格式化
     */
    public function format(array $returnData){

        //查询订单成功
        if($returnData["ret_code"] == '0000'){
            
            switch($returnData['result_pay']){
                case 'SUCCESS':{
                    $msg = self::TRADE_SUCCESS_MSG;
                    $tradeStatus = self::TRADE_SUCCESS;
                    break;
                }
                case 'WAITING':{
                    $msg = self::TRADE_WAITING_MSG;
                    $tradeStatus = self::TRADE_WAITING;
                    break;
                }
                case 'FAILURE':{
                    $msg = self::TRADE_FAIL_MSG;
                    $tradeStatus = self::TRADE_FAIL;
                    break;
                }
                default:{
                    $msg = self::TRADE_UNKNOW_MSG;
                    $tradeStatus = self::TRADE_UNKNOW;
                    break;
                }
            }
        }else{
            $msg = $returnData['ret_msg'];
            $tradeStatus = self::TRADE_UNKNOW;
        }

        $this->searchReturn['status']       = $tradeStatus;     //支付状态
        $this->searchReturn['msg']          = $msg;             //状态说明
    }


    /**
     * @param $cardNo
     * @return array
     * 远程获取银行卡信息
     */
    public function getCardInfo($cardNo){

        $result = $this->service->getCardInfo($cardNo);

        return $result;

      
    }


}