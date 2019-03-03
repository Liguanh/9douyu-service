<?php
use App\Http\Logics\Auth\SecurityAuthLogic;

class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
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
        $sign = $this->_getSign($url);
        $addQuery = "secret_sign={$sign}&partner_id=" . $this->_getAuthPartnerId();
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
        $sign = $this->_getSign($url, $data);
        $data['secret_sign'] = $sign;
        $data['partner_id'] = $this->_getAuthPartnerId();

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

    /**
     * 请求响应处理
     * @param $response
     * @param $args
     */
    public function response($response, $args) {
        //请定制响应处理
    }

    /**
     * @return string
     * 测试商户ID
     */
    protected function _getAuthPartnerId() {

        return '110000901001';
    }

    /**
     * 通过url获取sign
     * @param       $url
     * @param array $postData
     *
     * @return bool
     */
    protected function _getSign($url, $postData = array()) {

        $urlInfo = parse_url($url);

        if(!empty($urlInfo['query'])) {
            parse_str($urlInfo['query'], $_GET);
        }

        if(empty($postData)) {
            $data = $_GET;
        } else {
            $data = $postData;
        }


        $info = SecurityAuthLogic::getPartnerInfo($this->_getAuthPartnerId());
        if(empty($info['partner_id'])) {

            exit(json_encode(['sign' => false]));
        }

        $sign = SecurityAuthLogic::createSign($info['secret_key'], $data);
        return $sign;
    }
}
