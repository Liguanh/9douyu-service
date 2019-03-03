<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/10/18
 * Time: 18:13
 */

namespace App\Services\Sms;

class SMS{

    //用户名
    protected $userName = null;
    //密码
    protected $passwordMd5 = null;

    protected $baseUrl;
    protected $apiKey;

    public function __construct($userName,$password)
    {
            $this->username = $userName;
            $this->password = $password;
    }

    /**
     * @desc 获取账号的用户名
     * @return null|string
     */
    public function getUsername(){
        return $this->username;
    }

    /**
     * @desc 获取当前接口的请求地址
     * @param $baseUrl
     * @return string
     */
    public function setBaseUrl($baseUrl){
        return $this->baseUrl = $baseUrl;
    }

    /**
     * @desc 设置Apikey
     * @param $apiKey string
     * @return array
     */
    public function setApiKey( $apiKey )
    {
        return $this->apiKey = $apiKey;
    }

    /**
     * @desc 32位小写md5加密密码
     * @return string
     */
    public function getMd5Password(){
        $pwd = md5($this->password);

        return strtolower($pwd);
    }

    /**
     * @return string
     * @desc 返回密码的md5的值
     */
    public function returnMd5Password()
    {
        return md5 ($this->password);
    }
}

