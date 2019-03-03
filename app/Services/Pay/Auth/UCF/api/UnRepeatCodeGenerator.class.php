<?php
/**
 * 后台请求
 * @param string $key 接口关键字，参考paymentapi.conf.php
 * @param array $params 参数数组
 * @return array
 */
class UnRepeatCodeGenerator{

    /**
     * @param 获取序列号
     * @param $merchantId   商户号
     * @param $service      接口名称
     * @param $merchantNo   商户订单号
     * @return string
     */
    public static  function makeOrderSn($merchantId, $service, $merchantNo) {

        $randomVal = self::guid();
        $reqSn=strtoupper(md5($merchantId.$service.$merchantNo.$randomVal));
        return  $reqSn;

    }

    public static function guid(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = //chr(123)// "{"
                substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
            //.chr(125);// "}"
            return strtolower($uuid);
        }
    }

}


