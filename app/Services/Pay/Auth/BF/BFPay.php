<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/10/19
 * Time: 上午11:46
 */

namespace App\Services\Pay\Auth\BF;
use App\Services\Services;

class BFPay
{

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param $data
     * @return string
     * 加密
     */
    public function encrypt($data){
        $basePath = base_path();
        require_once($basePath."/app/Services/Pay/Auth/BF/api/BaofooSdk.php");

        $Encrypted_string = str_replace("\\/", "/",json_encode($data));//转JSON

        $pfx_filename = $basePath.$this->config['pfx_filename'];
        $cer_filename = $basePath.$this->config['cer_filename'];
        $private_key_password = $this->config['private_key_password'];

        //Log::LogWirte("请求明文：".$Encrypted_string);
        $baoFooSdk = new \BaofooSdk($pfx_filename,$cer_filename,$private_key_password); //实例化加密类。
        $data_content = $baoFooSdk->encryptedByPrivateKey($Encrypted_string);	//RSA加密

        return $data_content;
    }

    /**
     * @param $enData
     * @return string
     * 解密
     */
    public function decrypt($enData){

        $basePath = base_path();
        require_once($basePath."/app/Services/Pay/Auth/BF/api/BaofooSdk.php");

        $pfx_filename = $basePath.$this->config['pfx_filename'];
        $cer_filename = $basePath.$this->config['cer_filename'];
        $private_key_password = $this->config['private_key_password'];


        $baoFooSdk = new \BaofooSdk($pfx_filename,$cer_filename,$private_key_password); //实例化加密类。
        $enData = $baoFooSdk->decryptByPublicKey($enData);	//RSA解密

        if(!empty($enData)){//解析返回参数。

            $enData = json_decode($enData,TRUE);

        }

        return $enData;

    }

    /**
     * @param $data
     * @return mixed|string
     * 查单
     */
    public function search($data){

        $basePath = base_path();
        require_once($basePath."/app/Services/Pay/Auth/BF/api/HttpClient.php");

        $request_url = $this->config['search_order'];

        $Result = \HttpClient::Post($data, $request_url);//发送请求并接收结果

        require_once($basePath."/app/Services/Pay/Auth/BF/api/BaofooSdk.php");

        $pfx_filename = $basePath.$this->config['pfx_filename'];
        $cer_filename = $basePath.$this->config['cer_filename'];
        $private_key_password = $this->config['private_key_password'];

        $baoFooSdk = new \BaofooSdk($pfx_filename,$cer_filename,$private_key_password); //实例化加密类。
        $Result = $baoFooSdk->decryptByPublicKey($Result);	//RSA解密

        if(!empty($Result)){//解析返回参数。
            $Result = json_decode($Result,TRUE);
        }

        return $Result;

    }

    /**
     * @param $data
     * @return mixed|string
     * sdk 支付交易号请求
     */
    public function sdkGetTradeNo($data){

        $basePath = base_path();
        require_once($basePath."/app/Services/Pay/Auth/BF/api/HttpClient.php");

        $request_url = $this->config['sdk_pay_url'];

        $Result = \HttpClient::Post($data, $request_url);//发送请求并接收结果

        if(!empty($Result)){//解析返回参数。
            $Result = json_decode($Result,TRUE);
        }

        return $Result;

    }

}