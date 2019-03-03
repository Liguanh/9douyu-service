<?php
/**
 * Created by PhpStorm.
 * User: scofie
 * Date: 2017/9/14
 * Time: PM3:06
 */

namespace App\Services\Sms;


use App\Tools\ToolCryptAes;
use function GuzzleHttp\Psr7\str;

class DhFlowSms extends SMS
{
    const
        SEND_SUCCESS    =   '00',
        FLOW_METHOD     =   'FLOW' ,
        CALLS_METHOD    =   'CALLS',

        END =   true;
    public function __construct($username, $password ,$baseUrl) {

        parent::__construct ($username, $password);

        $this->baseUrl  =   $this->setBaseUrl ($baseUrl) ;
    }
    /**
     * @param array $param
     * @return string
     * @desc 生成 签名的sign
     */
    public function setSign($param = [])
    {
        //MD5(account+MD5(pwd)+timestamp+mobiles+ packageSize + clientOrderId)
        return $this->toMd5($param['account'] . $this->toMd5($this->password) . $param['timestamp'] . $param['mobiles'] . $param['packageSize'] . $param['clientOrderId'] ) ;
    }

    /**
     * @param $sendParams
     * @return array
     * @desc 构建请求的参数
     */
    protected function doConstructParams($type, $sendParams)
    {
        $formatParams=     [
            'timestamp'     =>  $this->getMillisecond (),
            'mobiles'       =>  $sendParams['phone'],
            'account'       =>  $this->getUsername (),
            'clientOrderId' =>  $sendParams['orderId'],
            'packageSize'   =>  $sendParams['packPrice']
        ];


        $formatParams['sign'] =   $this->setSign ($formatParams);
        $cryptAes           =   new ToolCryptAes();
        $cryptAes->set_key (substr($this->returnMd5Password(),0,16));
        $cryptAes->set_iv (substr($this->returnMd5Password(),16,16));

        $formatParams['mobiles']= $cryptAes->getEncryptText ($sendParams['phone']);

        if( strtoupper ($type) == self::CALLS_METHOD ){
            unset($formatParams['packageSize']);
            $formatParams['price']  =   $sendParams['packPrice'] ;
        }
        \Log::info('dhFlowSendParams' , $formatParams);

        return json_encode($formatParams);

    }
    /**
     * @param $string
     * @return string
     */
    public function toMd5($string)
    {
        return md5($string);
    }

    /**
     * @param $url
     * @param $postData
     * @return mixed
     * @desc 发送请求
     */
    public function sendRequest($type, $sendParams = [])
    {
        $return     =    $this->postData($this->baseUrl, $this->doConstructParams($type ,$sendParams));

        $returnArr = [
            "status"    => true,
            "code"   => self::SEND_SUCCESS,
            "msg"  => isset($return['resultMsg']) ? $return['resultMsg'] : '充值成功',
        ];
        if($return['resultCode'] != self::SEND_SUCCESS){
            $returnArr = [
                "status"    => false,
                "code"      => $return['resultCode'],
                "msg"       => $return['resultMsg']
            ];
        }

        return $returnArr;
    }

    /**
     * @desc 数据提交
     * @param $url
     * @param $data
     * @return mixed
     */
    public function postData($url, $data){
        $ch = curl_init ( $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_FRESH_CONNECT, 1 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_FORBID_REUSE, 1 );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 30 );
        //curl_setopt ( $ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json; charset=utf-8', 'Content-Length: ' .strlen( $data )));
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $ret = curl_exec ( $ch );
        curl_close ( $ch );
        $return     =   json_decode ($ret, true) ;
        \Log::info('dahan_post_result',$return);
        return $return;
    }
    /**
     * @return float
     * @desc 获取毫秒级的时间戳
     */
    public function getMillisecond()
    {
        list( $time1, $time2 ) = explode(' ', microtime() );

        return (float)sprintf('%.0f', (floatval($time1) + floatval($time2)) * 1000);
    }
}