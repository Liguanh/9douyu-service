<?php
/**
 * Created by @author scofie. <wu.changming@9douyu.com>
 * Date: 16/3/29  Time: 下午3:33
 * @version 1.0.0
 * @copyright  Copyright 2015 9douyu.com
 */

namespace App\Services\Sms;

use Log;

class LsmVoice
{
    //定义变量
    const
        API_KEY        = 'afbc116bce83dfdfc6e680f336eb2658',
        API_URL_VERIFY = 'http://voice-api.luosimao.com/v1/verify.json',
        API_URL_STATUS = 'http://voice-api.luosimao.com/v1/status.json',
        LSM_NUMBER     = '021-31587056';

    //通信apikey
    protected $apiKey;

    //发送验证码的的api接口
    protected $apiUrlVerify;

    //账号余额请求API接口
    protected $apiUrlStatus;

    //接口的类型
    protected $apiType;
    //获取请求参数
    public function __construct()
    {

        //$lsmConfigArr       = $this->getLsmConfig();
        $this->apiKey       = self::API_KEY;   //通信秘钥
        $this->apiUrlVerify = self::API_URL_VERIFY;
        $this->apiUrlStatus = self::API_URL_STATUS;

    }

    //发送语音
    public function sendCode($phones, $code){

        $status = self::curlPost($phones,$code);
        Log::info("LsmVoice: return info ".$status);
        $returnArr = json_decode($status,true);
        if($returnArr["error"]<0){
            Log::info('语音验证码发送失败，错误码为：'.$returnArr["error"].",错误信息为：".$returnArr["msg"]);
        }
        return $returnArr;
    }
    //获取语音状态
    public function getLsmStatus()
    {
        $status = self::curlPost();
        return $status;
    }
    //
    private function curlPost($phones='', $code = ''){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrlVerify);

        curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD  , 'api:'.$this->apiKey);

        if(!empty($phones) && !empty($code)) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $phones, 'code' => $code));
        }

        $CodeStatus = curl_exec( $ch );
        curl_close( $ch );
        //$CodeStatus  = curl_error( $ch );
        return $CodeStatus;
    }

}