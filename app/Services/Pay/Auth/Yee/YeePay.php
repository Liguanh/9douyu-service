<?php
/**
 * Created by PhpStorm.
 * 易宝支付类
 * User: caelyn
 * Date: 16/1/27
 * Time: 下午15:23
 */
namespace App\Services\Pay\Auth\Yee;
use App\Services\Services;

class YeePay{

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }
    
    /**
     * 签名
     * @param array $param
     * @return bool
     */
    public function submit($param){
        $data       = $param['data'];
        $encryptkey = $param['encryptkey'];

        try {
            $return['status'] = true;
            $return['data'] = $this->yeepay->callback($data, $encryptkey);
        }catch (\Exception $e) {
            $return['status'] = false;
            $return['data'] = $e->getMessage();
        }
        return $return;
    }




    /**
     * @param $id
     * @return array
     * 主动查询订单接口
     */
    public function search($orderId)
    {
        $basePath = base_path();
        require_once($basePath."/app/Services/Pay/Auth/Yee/api/yeepayMPay.class.php");
        $YeePAY = $this->config;
        $oid_partner = $YeePAY['ACCOUNT'];
        $requestUrl = $YeePAY['CHECKORDER'];
        $tmp = array(
            'orderid' => $orderId,
            'merchantaccount' => $oid_partner,
        );
        $yeepay = new \yeepayMPay($YeePAY['ACCOUNT'], $YeePAY['PUBLICKEY'], $YeePAY['PRIVATEKEY'], $YeePAY['YEEPAYPUBLICKEY']);
        $request = $yeepay->buildRequest($tmp);

        $requestUrl .= '?' . http_build_query($request);
        $http = new Services();
        $json_data = $http->curlOpen($requestUrl, array(), true);
        if ($json_data) {
            $result = json_decode($json_data, true);
            $data = $result["data"];
            $encryptkey = $result["encryptkey"];
            try {
                $return = $yeepay->callback($data, $encryptkey);
                if (is_array($return)) {
                    return $return;
                }
            } catch (\Exception $e) {
                return array();
            }

        }
        return array();

    }



}



?>
