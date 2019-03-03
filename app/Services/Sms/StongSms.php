<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/10/17
 * Time: 14:37
 * Desc: 大汉三通营销短信接口
 */

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

class StongSms extends SMS{

    const
        SEND_SMS_URL      = '/Submit',
        SMS_REPORT_URL = '/Report',
        SIGN         = ["【九斗鱼】", "【九金服】"],//短信签名
        SUBCODE      = "";       // 短信子码,选填


    public function __construct($userName = null, $password = null )
    {
        parent::__construct($userName, $password);
    }

    /**
     * @desc 发送短信统一接口函数
     * @param $phones
     * @param $message
     * @return array
     */
    public function sendCode($phones, $message){
//        if(stripos($message,'【九斗鱼】') >=0){
//            $message = str_replace('【九斗鱼】','',$message);
//        }
        $returnArr = array(
            "status"    => true,
            "errorNo"   => 0,
            "errorMsg"  => '发送成功'
        );
        if(empty($phones) || empty($message)){
            $returnArr["status"]   = false;
            $returnArr["errorNo"]  = -10;
            $returnArr["errorMsg"] = '手机号或短信内容不能为空';
            Log::info('Stong-sendSms:'.json_encode($returnArr));
            return $returnArr;
        }
        $this->sendSms($phones, $message);
        $report = json_decode($this->getSmsReport());

        if($report->result!= 0){

            $returnArr["status"]   = false;
            $returnArr["errorNo"]  = $report->result;
            $returnArr["errorMsg"] = $report->desc;
        }
        Log::info('Stong-sendSms-Api-Return:', $returnArr);
        return $returnArr;
    }

    /**
     * @desc         短信发送数据包装
     * @param        $phones
     * @param        $message
     * @param string $msgid
     * @param string $sendtime
     * @return mixed
     */
    public function sendSms($phones, $message, $msgid = '', $sendtime =''){
        $sign = "【九斗鱼】";

        foreach(self::SIGN as $value) {
            if(stripos($message,$value) !== false){
                $message = str_replace($value,'',$message);
                $sign = $value;
            }
        }

        if(is_array($phones)) $phones = implode(',', $phones);
        $url = $this->baseUrl.self::SEND_SMS_URL;
        $data = array (
            'account'  => $this->getUsername(),
            'password' => $this->getMd5Password(),
            'msgid'    => $msgid,
            'phones'   => $phones,
            'content'  => $message,
            'sign'     => $sign,
            'subcode'  => self::SUBCODE,
            'sendtime' => $sendtime
        );
        Log::info('Stong-sendSms-Content:', $data);
        $res = $this->postData($url, json_encode($data));
        return $res;
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
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json; charset=utf-8', 'Content-Length: ' .strlen( $data )));
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $ret = curl_exec ( $ch );
        curl_close ( $ch );
        return $ret;
    }

    /**
     * @desc 短信状态报告
     * @return mixed
     */
    public function getSmsReport() {
        $url = $this->baseUrl.self::SMS_REPORT_URL;
        $data = array ('account' => $this->getUsername(), 'password' => strtolower(md5($this->password)));
        return $this->postData($url, json_encode ( $data ) );
    }
    /**
     * @desc 处理返回值
     * @param $result
     * @return array
     */
    public function execResult($result){
        $result=preg_split("/[,\r\n]/",$result);
        return $result;
    }

}
