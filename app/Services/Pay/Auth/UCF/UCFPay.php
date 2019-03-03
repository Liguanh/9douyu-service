<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/10/19
 * Time: 上午11:46
 */

namespace App\Services\Pay\Auth\UCF;

use App\Services\Services;

class UCFPay
{

    private $ucfObj = null;

    private $config = [];

    public function __construct($source,$config)
    {
        $basePath = base_path();
        require_once($basePath."/app/Services/Pay/Auth/UCF/api/UCFPayAuth.class.php");
        require_once($basePath."/app/Services/Pay/Auth/UCF/api/UnRepeatCodeGenerator.class.php");

        $this->ucfObj = new \UCFAuthPay($source,$config);

        $this->config = $config;
    }

    /**
     * @param $service
     * @param $orderId
     * @return string
     * 生成序列号
     */
    public function getReq($service,$orderId){

        return \UnRepeatCodeGenerator::makeOrderSn($this->config['merchant_id'], $service, $orderId);

    }
    /**
     * @param $data
     * @return string
     * 加密
     */
    public function encrypt($params){


        ksort ( $params );
        $paramsJoin = array ();
        foreach ( $params as $key => $value ) {
            $paramsJoin [] = "$key=$value";
        }

        $paramsString = implode ( '&', $paramsJoin );
        $md5val = strtolower ( md5 ( $paramsString ) );
        //$public_key = include_once '../file/publickey.php';
        $public_key = $this->ucfObj->getConfig('public_key');
        $pem = chunk_split ( ($public_key), 64, "\n" );
        $pem = "-----BEGIN PUBLIC KEY-----\n" . $pem . "-----END PUBLIC KEY-----\n";
        $publicKey = openssl_pkey_get_public ( $pem );
        openssl_public_encrypt ( $md5val, $crypted, $publicKey );
        return  base64_encode ( $crypted );

    }

    /**
     * @param $enData
     * @return string
     * 解密
     */
    public function decrypt($enData){

        //定义签名算法
        //$secId = $enData['secId'];
        $secId = "RSA";//或是可以直接定义

        //签名验证
        $signature = isset($enData['sign']) ? trim($enData['sign']) : 0;
        unset($enData['sign']);

        //根据参数名称中的首字母进行a-z的自然排序，如果首字母一样，则按照第二个字母进行排序，以此类推
        ksort($enData);

        //获取参数及参数值，装入数组中
        $paramsJoin = array ();
        foreach ( $enData as $key => $value ) {
            if($key == 'sign'){
                continue;
            }else{
                $paramsJoin [] = "$key=$value";
            }
        }

        $flag = false;
        if ($secId == 'MD5') {
            $paramsString = implode ( '&', $paramsJoin );
            $mySign = md5 ( $paramsString );
            if($signature == $mySign){
                $flag = true;
            }
        } else if($secId == 'RSA'){
            //(1) *******start商户将 先锋支付结果通知 的参数排序后进行MD5加密然后得到MD5的值；*********/
            $paramsString = implode ( '&', $paramsJoin );
            $mySign = strtolower (md5 ( $paramsString ));
            //*******end**********************************************************************/

            //(2) *******start将先锋发送的支付结果通知的RSA的sign解密成MD5的值；*************/
            $public_key = $this->ucfObj->getConfig('public_key');
            $pem = chunk_split ( ($public_key), 64, "\n" );
            $pem = "-----BEGIN PUBLIC KEY-----\n" . $pem . "-----END PUBLIC KEY-----\n";
            $publicKey = openssl_pkey_get_public ( $pem );
            openssl_public_decrypt ( base64_decode($signature), $decryptData, $publicKey );//先进行公钥解密
            //*******end**********************************************************************/

            //(3) 把(1)和 (2)比较，相同则代表验签成功
            if($decryptData == $mySign){
                $flag = true;
            }
        }

        return $flag;


    }

    /**
     * @param $data
     * @return mixed|string
     * 
     */
    public function post($data,$url){

        $result = $this->ucfObj->post($url,$data);
        return json_decode($result,true);

    }




}