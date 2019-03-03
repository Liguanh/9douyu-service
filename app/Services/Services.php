<?php
/**
 * 公共类
 * User: bihua
 * Date: 16/5/9
 * Time: 11:12
 */
namespace App\Services;

class Services
{
    public function curlOpen($url, $data = array(), $ssl = true)
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        //post提交
        if(!empty($data)){
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $data);
        }
        //SSL方式
        if(!empty($ssl)){
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }


    /**
     * cURL get请求
     * @param       $url
     * @param array $callback
     * @param array $opt
     * @param array $args
     */
    public function getRequest($url, $callback = array(), $opt = array(), $args = array()) {
        $url = preg_replace('/#.+$/i', '', $url);   //去掉#
        if(stripos($url, '?') !== false) {
            $url = str_replace('?', "?{$addQuery}&", $url);
        } else {
            $url .= "?{$addQuery}";
        }
        return $this->_request($url, $callback, $opt, $args);
    }

    /**
     * cURL post请求
     * @param       $url
     * @param       $data   其中的int类型要转成string
     * @param array $callback
     * @param array $opt
     * @param array $args
     */
    public function postRequest($url, $data, $callback = array(), $opt = array(), $args = array()) {
        $url = preg_replace('/#.+$/i', '', $url);   //去掉#
        $defaultOpt = array(
            CURLOPT_POST        => true,
            CURLOPT_POSTFIELDS  => $data,
        );
        $opt = $defaultOpt + (array)$opt;   //不能使用array_merge，否则会导致键位重置为0开始

        return $this->_request($url, $callback, $opt, $args);
    }

    /**
     * cURL请求
     * @param       $url
     * @param array $callback
     * @param array $opt
     * @param array $args
     *
     * @throws \Ares333\CurlMulti\Exception
     */
    protected function _request($url, $callback = array(), $opt = array(), $args = array()) {
        if(empty($callback)) {
            $callback = array($this, 'response');
        }
        $curl = new \Ares333\CurlMulti\Core();

        $curl->add(array (
            'url'   => $url,
            'opt'   => $opt,
            'args'  => $args,
        ), $callback);

        $curl->start();
    }
}