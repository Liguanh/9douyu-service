<?php

/**
 * 支付认证接口
 * @author wrb
 *
 */

class UCFAuthPay {

    /**
     * 获取token 服务名
     * @var unknown
     */
    //const TOKEN_SERVICE = 'REQ_GET_TOKEN';

    /**
     * PC 创建订单服务名
     * @var unknown
     */
    const PC_ORDER_CREATE_SERVICE = 'MOBILE_CERTPAY_PC_ORDER_CREATE';

    /**
     * H5 创建订单服务名
     * @var unknown
     */
    const H5_ORDER_CREATE_SERVICE = 'MOBILE_CERTPAY_H5_ORDER_CREATE';

    /**
     * 解绑银行卡服务名称
     * @var unknown
     */
    const UNBIND_BANK_NO_SERVICE = 'MOBILE_CERTPAY_UNBIND_CARD';

    /**
     * 版本号
     * @var unknown
     */
    const VERSION = '3.0.0';

    //配置文件
    const CONFIG_FILE = 'conf/paymentapi.conf.php';

    /**
     * 超时时间
     * @var unknown
     */
    public static $timeout = 30;

    //接口参数配置
    private static $config = null;

    /**
     * 加密算法 默认为RSA
     * @var unknown
     */
    public $secId = 'RSA';

    public static $errno = 0;

    public static $error = '';

    public static $httpCode = 0;

    public $header = '';

    /**
     * 请求来源：PC 或者  H5
     * @var unknown
     */
    public $source = 'PC';
    /**
     * 请求编号
     * @var unknown
     */
    public $reqId = '';

    /**
     * 构造函数
     * @param unknown $source PC or H5
     * @param string $secId 算法 RSA
     */
    public function __construct($source,$config) {

        $this->source = $source;
        self::$config  = $config;
    }

    /**
     * 创建订单编号
     *
     * @return string
     */
    public function createReqId() {
        $now = time();
        $day = date('Ymd',$now);
        $H   = date('H',$now);
        $i   = date('i',$now);
        $s   = date('s',$now);
        $reqId = $day.$H.$i.$s.mt_rand(100000, 999999);
        return $reqId;
    }

    /**
     * 设置订单编号
     * @param string $reqId
     */
    public function setReqId ($reqId = '') {
        $this->reqId = $reqId;
    }
    /**
     * curl post 请求
     * @param string $url
     * @param array $param
     */
    public function post($url, $param=array()) {
        if (empty($url)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::$timeout);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (substr($url, 0, 5) === 'https')
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //信任任何证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //检查证书中是否设置域名
        }

        $result = curl_exec($ch);
        // 获得响应结果里的：头大小
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        // 根据头大小去获取头信息内容
        $this->header = substr($result, 0, $headerSize);

        self::$errno = curl_errno($ch);
        self::$error = curl_error($ch);
        self::$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        return $result;
    }

    /**
     * 获取请求的错误
     * @return multitype:number string
     */
    public function getRequestError() {
        return array('errno' => self::$errno, 'error' => self::$error, 'httpCode' => self::$httpCode);
    }

    /**
     * 获取跳转信息
     * @return string
     */
    public function getLocation() {
        $edengUrl = '';
        $headArr = explode("\r\n", $this->header);
        foreach ($headArr as $loop) {
            if(strpos($loop, "Location") !== false){
                $edengUrl = trim(substr($loop, 10));
            }
        }
        return $edengUrl;
    }

    /**
     * 加载配置
     */
    public static function getConfig($key, $subKey = '')
    {

        if (empty(self::$config[$key]))
        {
            return array();
        }

        if ($subKey !== '')
        {
            return self::$config[$key][$subKey];
        }

        return self::$config[$key];
    }

    /**
     * 获取签名
     * @param unknown $params
     * @return string
     */
    public function getSignature($params) {
        ksort($params);
        $paramsJoin = array();
        foreach ($params as $key => $value)
        {
            $paramsJoin[] = "$key=$value";
        }
        $paramsString = implode('&', $paramsJoin);
        $md5val = strtolower(md5($paramsString));
        $public_key =self::getConfig('public_key');
        $pem = chunk_split(($public_key), 64, "\n");
        $pem = "-----BEGIN PUBLIC KEY-----\n".$pem."-----END PUBLIC KEY-----\n";
        $publicKey = openssl_pkey_get_public($pem);
        openssl_public_encrypt($md5val,$crypted,$publicKey);
        return base64_encode($crypted);
    }

    /**
     * 获取TOKEN
     * @return array('token)
     */
    public function getToken($reqParams) {
        $result = array('errno' => '0', 'error' => '', 'data' => '');

        //获取接口Url
        $url = self::getConfig('gateway');
        //拼接参数
        $params['service'] = self::TOKEN_SERVICE;
        $params['secId']   = $this->secId;
        $params['version'] = self::VERSION;
        $params['merchantId'] = $reqParams['merchantId'];
        $params['reqId'] = $reqParams['reqId'];
        $params['sign']    = $this->getSignature($params);
        $response = $this->post($url, $params);
        //处理结果
        if (empty($response)) {
            $result = array('errno' => 1, 'error' => '接口请求错误', 'data' => array('httpError' => $this->getRequestError()));
            return $result;
        }
        $resultArray = json_decode($response, true);
        if (!isset($resultArray['result']) || empty($resultArray['result'])) {
            $result = array('errno' => $resultArray['resCode'], 'error' => $resultArray['resMessage'], 'data' => array('httpError' => $this->getRequestError()));
            return $result;
        }
        $result['data']['token'] = $resultArray['result'];
        return $result;
    }

    /**
     * 获取创建支付订单请求参数
     * @param unknown $params 请求的参数
     * @return array
     */
    public function getCreateOrderReqParams($params) {
        //拼接参数
        $request_params['service'] = $this->source == 'PC' ?  self::PC_ORDER_CREATE_SERVICE :self::H5_ORDER_CREATE_SERVICE;
        $request_params['secId']   = $this->secId;
        $request_params['version'] = self::VERSION;
        if (is_array($params) && $params) {
            foreach ($params as $key => $value) {
                $request_params[$key] = $value;
            }
        }
        $request_params['sign'] = $this->getSignature($request_params);
        return $request_params;
    }

    /**
     * 解绑银行卡
     * @param unknown $params
     * @return array (errno=> 0 ：成功, error :错误信息 , data:返回数据)
     */
    public function unbindCardNo ($params) {
        $result = array('errno' => '0', 'error' => '', 'data' => '');
        //获取接口Url
        $url = self::getConfig('gateway');
        //拼接参数
        $request_params['service'] = self::UNBIND_BANK_NO_SERVICE;
        $request_params['secId']   = $this->secId;
        $request_params['version'] = self::VERSION;
        if (is_array($params) && $params) {
            foreach ($params as $key => $value) {
                $request_params[$key] = $value;
            }
        }
        //获取签名
        $request_params['sign'] = $this->getSignature($request_params);
        //请求
        $response = $this->post($url, $request_params);

        if (empty($response)) {
            $result = array('errno' => 1, 'error' => '接口请求错误', 'data' => '');
            return $result;
        }
        //格式化结果
        $resultArray = json_decode($response, true);
        //请求服务不正确
        if (!isset($resultArray['status'])) {
            $result = array('errno' => $resultArray['resCode'], 'error' => $resultArray['resMessage'], 'data' => array('httpError' => $this->getRequestError()));
            return $result;
        }
        //解绑失败
        if ($resultArray['status'] != '00') {
            $result = array('errno' => $resultArray['respCode'], 'error' => $resultArray['respMsg'], 'data' => array('httpError' => $this->getRequestError()));
            return $result;
        }
        return $result;
    }
}