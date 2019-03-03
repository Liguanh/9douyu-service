<?php
/**
 * Created by PhpStorm.
 * 连连支付类
 * User: caelyn
 * Date: 16/1/27
 * Time: 下午15:23
 */
namespace App\Services\Pay\Auth\LL;
use App\Services\Services;

class LLPay{

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 查单
     * @param $orderId
     * @return array
     */
    public function search($orderId){

        $config  = $this->config;
        $url     = $config['PCAUTH']['CHECK_ORDER_URL'];
        $tmp = array(
            'no_order'      => $orderId,
            'oid_partner'   => $config['OID_PARTNER'],
            'sign_type'     => $config['SIGN_TYPE']
        );
        $data = $this->encrypt($tmp);

        $data = json_encode($data);
        //$json_data = curlOpen($url,$data);
        $http = new Services();
        $json_data = $http->curlOpen($url,$data);
        return json_decode($json_data,true);
    }


    /**
     * @param $cardNo
     * @return array
     * 连连卡bin接口
     */
    public function getCardInfo($cardNo){

        $strParams      = array(
            "oid_partner" => $this->config["OID_PARTNER"],
            "card_no"     => $cardNo,
            "sign_type"   => $this->config["SIGN_TYPE"],
        );

        $strParams = $this->encrypt($strParams);

        $strParams = json_encode($strParams);

        $http = new Services();
        $json_data = $http->curlOpen($this->config["BANK_CARD_QUERY"],$strParams);

        $result    = json_decode($json_data,true);

        return $result;
    }



    /**
     * 处理数据
     * @$param array $params
     * @return json
     */
     public function encrypt($params){

        $config       = $this->config;

        $para_filter = $this->paraFilter($params);
        //对待签名参数数组排序
        $para_sort = $this->argSort($para_filter);

        //生成签名结果
        $arg  = "";
        while (list ($key, $val) = each ($para_sort)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

        $mysign = md5($arg ."&key=". $config['KEY']);

        $para_sort['sign'] = $mysign;
        $para_sort['sign_type'] = $config['SIGN_TYPE'];
        foreach ($para_sort as $key => $value) {
            $para_sort[$key] = $value;
        }
        return $para_sort;
    }

    /**
     * 验签
     */
    public function decrypt($params, $sign){

        $config  = $this->config;

        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($params);

        //对待签名参数数组排序
        $para_sort = $this->argSort($para_filter);

        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);

        $isSgin = $this->md5Verify($prestr, $sign, $config['KEY']);

        return $isSgin;
    }


    public function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        //file_put_contents("log.txt","转义前:".$arg."\n", FILE_APPEND);
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        //file_put_contents("log.txt","转义后:".$arg."\n", FILE_APPEND);
        return $arg;
    }


    function md5Sign($prestr, $key) {
        $prestr = $prestr ."&key=". $key;
        return md5($prestr);
    }

    /**
     * 验证签名
     * @param $prestr 需要签名的字符串
     * @param $sign 签名结果
     * @param $key 私钥
     * return 签名结果
     */
    function md5Verify($prestr, $sign, $key) {
        $prestr = $prestr ."&key=". $key;
        $mysgin = md5($prestr);
        if($mysgin == $sign) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $val == "")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }


}



?>
