<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/4/19
 * Time: 17:09
 */

namespace App\Http\Models\Pay\Online;
use App\Http\Models\Pay\PayModel;

class HnaModel extends PayModel{

    public function __construct(){

        parent::__construct('HNAPAY_CONFIG');

    }


    /**
     * @param array $params
     * @return array
     * 加密接口
     */
    public function encrypt(array $params){

        $config         = $this->config;

        $orderId = $params['order_id'];
        $cash    = $params['cash'] * 100;

        $parameter = array(
            'version' => $config['version'],
            'serialID' => $orderId,
            'submitTime' => date('YmdHis'),
            'failureTime' => date('YmdHis',time()+3600*24),
            'customerIP' => $params['user_ip'],
            'orderDetails' => $this->getOrderDetails($orderId,$cash),
            'totalAmount' => (int)$cash,
            'type' => $config['type'],
            'buyerMarked' => $config['buyerMarked'],
            'payType' => $config['payType'],
            'orgCode' => $params['bank_code'],
            'currencyCode' => $config['currencyCode'],
            'directFlag' => $config['directFlag'],
            'borrowingMarked' => $config['borrowingMarked'],
            'couponFlag' => $config['couponFlag'],
            'platformID' => $config['platformID'],
            'returnUrl' => $params['return_url'],
            'noticeUrl' => $params['notify_url'],
            'partnerID' => $config['partnerID'],
            'remark' => $config['remark'],
            'charset' => $config['charset'],
            'signType' => $config['signType'],
        );
        $signMsg = $this->getSign($parameter);
        $parameter['signMsg'] = $signMsg;

        $result = [
            'parameter' => $parameter,
            'url'       => $config['payUrl']
        ];

        return $result;
    }


    /**
     * @param array $params
     * @return array
     * 解密接口
     */
    public function decrypt(array $params){

        $config         = $this->config;

        $src = "orderID=".$params["orderID"]
            ."&resultCode=".$params["resultCode"]
            ."&stateCode=".$params["stateCode"]
            ."&orderAmount=".$params["orderAmount"]
            ."&payAmount=".$params["payAmount"]
            ."&acquiringTime=".$params["acquiringTime"]
            ."&completeTime=".$params["completeTime"]
            ."&orderNo=".$params["orderNo"]
            ."&partnerID=".$params["partnerID"]
            ."&remark=".$params["remark"]
            ."&charset=".$params["charset"]
            ."&signType=".$params["signType"];

        $src = $src."&pkey=".$config['pkey'];
        $ret = ($params["signMsg"] == md5($src));

        //判断签名是否正确
        if ($ret){

            //成功
            $this->decryptReturn['trade_no']       = $params["orderNo"];  //交易流水号
            $this->decryptReturn['order_id']       = $params['orderID'];  //订单号
            $this->decryptReturn['verify_status']  = true;                //签名状态

            if($params["resultCode"] == "0000"){

                $this->decryptReturn['trade_status'] = self::TRADE_SUCCESS;
                $this->decryptReturn['msg']          = self::TRADE_SUCCESS_MSG;

            }
        }else{
            $this->decryptReturn['msg']          = self::TRADE_SIGN_ERROR;

        }


        return $this->decryptReturn;
    }

    /**
     * 查单
     * @param $orderId
     * @return array
     */
    public function search(array $params){

        $orderId = $params['order_id'];
        $return = [];
        
        $config = $this->config;
        $parameter = array(
            'version' => $config['version'],
            'serialID' => $orderId,
            'mode'=>1,
            'type'=>1,
            'orderID'=>$orderId,
            'beginTime' => '',
            'endTime' => '',
            'partnerID' => $config['partnerID'],
            'remark' => $config['remark'],
            'charset' => $config['charset'],
            'signType' => $config['signType'],
        );
        $signMsg = $this->getSign($parameter);
        $parameter['signMsg'] = $signMsg;
        $result = $this->http_POST($config['searchUrl'],$parameter);
        $arr = explode('&',$result);
        foreach($arr as $vo){
            $tmp=explode('=',$vo);
            if($tmp[0]!='queryDetails') continue;
            $return = explode(',',$tmp[1]);
        }
        return $return;
    }


    /**
     * @param $parameter
     * @return string
     * 获取签名
     */
    private function getSign($parameter){
        $string = '';
        foreach($parameter as $key => $vo){
            $string .= $key.'='.$vo.'&';
        }
        if(!empty($string)){
            $string .= 'pkey='.$this->config['pkey'];
            $string = md5($string);
        }
        return $string;
    }

    private function getOrderDetails($orderId,$cash){
        $details = array(
            $orderId,
            (int)$cash,
            $this->config['displayName'],
            $this->config['goodsName'],
            1
        );
        $details = implode(',',$details);
        return $details;
    }

    private function http_POST($url,$arr) {
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $arr );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
        $response = curl_exec ( $ch );
        curl_close ( $ch );
        return $response;
    }


}