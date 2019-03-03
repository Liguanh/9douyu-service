<?php
/**
 * Date: 16/5/10
 * Time: 12:57
 * Desc: 融宝网银支付
 */
namespace App\Http\Models\Pay\Online;

use App\Services\Pay\Withholding\Rea\Reapay;

use App\Http\Models\Pay\PayModel;

class ReaModel extends PayModel{

    private $service;

    public function __construct()
    {
        parent::__construct('REAPAY_CONFIG');
        $this->service = new Reapay($this->config);

    }

    /**
     * @param array $params
     * 解密接口
     */
    public function decrypt(array $params)
    {
        // TODO: Implement decrypt() method.
        $sign = $params['sign'];

        unset($params['sign'],$params['sign_type'],$params['driver'],$params['method']);

        $md5sign = $this->service->decrypt($params,$this->config['apiKey']);

        $this->decryptReturn['trade_no']       = $params['trade_no'];  //交易流水号
        $this->decryptReturn['order_id']       = $params['order_no'];     //订单号

        //判断签名是否正确
        if($sign == $md5sign){

            $orderNo = $params['order_no'];
            $tradeNo = $params['trade_no'];

            if($params['trade_status']=='TRADE_FINISHED'){

                $this->decryptReturn['trade_status'] = self::TRADE_SUCCESS;
                $this->decryptReturn['msg']          = self::TRADE_SUCCESS_MSG;
            }

        }

        return $this->decryptReturn;

    }


    /**
     * @param array $params
     * 加密接口
     */
    public function encrypt(array $params)
    {
        // TODO: Implement encrypt() method.

        $config = $this->config;

        $returnUrl      = $params['return_url'];
        $order_id       = $params['order_id'];

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service"       => 'online_pay',
            "merchant_ID"   => $config['merchant_id'],
            "notify_url"    => $params['notify_url'],
            "order_no"      => $order_id,
            "return_url"    => $params['return_url'],
            "charset"       => "utf-8",
            "title"         => '11',
            "body"          => '111',
            "total_fee"     => $params['cash'],
            "payment_type"  => "1",
            "paymethod"     => "directPay",
            "defaultbank"   => $params['bank_code'],
            "seller_email"  => $config['seller_email'],
        );
        $sign = $this->service->createSign($parameter,$config['apiKey']);
        $parameter['sign'] = $sign;
        $parameter['sign_type'] = 'MD5';

        return [
            'url'       => $config['online_pay_url'],
            'parameter' => $parameter,
        ];
    }




    /**
     * @param $orderId
     * 订动查单
     * 暂未对接
     */
    public function search(array $params)
    {
        // TODO: Implement search() method.


    }



    public function format(array $returnData)
    {
        // TODO: Implement format() method.

    }
}