<?php
/**
 * Created by PhpStorm.
 * User: lgh189491
 * Date: 16/10/14
 * Time: 20:32
 * Desc: 美联验证码短信接口
 */

namespace App\Services\Sms;


use Illuminate\Support\Facades\Log;

class MiSms extends SMS{

    const
        SEND_SMS_URL = '/api/send/index.php?',
        API_KEY       = '4157ae2d8ef465f6a88eff54abf00288';

    public function __construct($userName = null, $password = null )
    {
        parent::__construct($userName,$password);
    }
    /**
     * @desc 发送短信的统一接口
     * @param      $phones
     * @param      $message
     * @param null $sendTime
     * @return array
     */
    public function sendCode($phones, $message){
        $returnArr = array(
            "status"    => true,
            "errorNo"   => 0,
            "errorMsg"  => '发送成功'
        );
        if(empty($phones) || empty($message)){
            $returnArr["status"]   = false;
            $returnArr["errorNo"]  = -10;
            $returnArr["errorMsg"] = '手机号或短信内容不能为空';
            return $returnArr;
        }
        $result = $this->sendSms($phones, $message);

        if(stripos($result,"success") === false ) {

            $returnArr["status"]   = false;
            $returnArr["errorNo"]  = -11;
            $returnArr["errorMsg"] = $result;

        }
        Log::info('Mi-sendSms-Api-Return:', $returnArr);
        return $returnArr;
    }
    /**
     * @desc 发送短信数据包装
     * @param $phones
     * @param $message
     * @return mixed
     */
    public function sendSms($phones, $message){
        if(is_array($phones)) $phones = implode(',', $phones);

        $url = $this->baseUrl.self::SEND_SMS_URL;

        $postData  =  [
            'username'       => $this->getUsername(),
            'password_md5'   => $this->getMd5Password(),
            'apikey'         => $this->apiKey,
            'content'        => urlencode($message),
            'mobile'         => $phones,
            'encode'         => 'UTF-8'
        ];
        Log::info('Mi-sendSms:', $postData);
        $res = $this->postData($url, $postData);
        return $res;
    }

    /**
     * @desc 提交数据
     * @author lgh
     * @param $url
     * @param $postData
     * @return mixed
     */
    public function postData($url, $postData){
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_TIMEOUT,30);
        curl_setopt($ch,CURLOPT_HEADER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
        $data = curl_exec($ch);
        curl_close($ch);
        $res = explode("\r\n\r\n",$data);
        return $res[2];
    }

}
