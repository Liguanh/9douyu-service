<?php
/**
 * User: zhangshuang
 * Date: 16/5/10
 * Time: 12:57
 * Desc: 京东网银支付
 */

namespace App\Http\Models\Pay\Online;
use App\Http\Models\Pay\PayModel;
use App\Services\Pay\Online\Jd\JdPay;

class JdModel extends PayModel{


    public function __construct()
    {
        parent::__construct('CBPAY_CONFIG');
    }

    /**
     * @param array $params
     * 加密接口
     */
    public function encrypt(array $params){

        $config = $this->config;

        $parameter = array(
            "v_mid"       => $config['PARTNERID'],
            "key"         => $config['KEY'],
            "v_oid"       => $params['order_id'],
            "v_url"       => $params['return_url'],
            "v_amount"    => $params['cash'],
            "v_moneytype" => $config['MONEYTYPE'],
            "pmode_id"    => $params['bank_code'],
            "remark1"     => "9douyu",
            "remark2"     => "[url:={$params['notify_url']}]",
        );
        $text                   = $parameter["v_amount"].$parameter["v_moneytype"].$parameter["v_oid"].$parameter["v_mid"].$parameter["v_url"].$parameter["key"]; //md5加密拼凑串,注意顺序不能变
        $parameter['v_md5info'] = strtoupper(md5($text));

        $return = [
            'parameter' => $parameter,
            'url'       => $this->config['API_GATEWAY']
        ];

        return $return;

    }


    /**
     * @param array $params
     * 解密接口
     */
    public function decrypt(array $params)
    {
        // TODO: Implement decrypt() method.
        $config         = $this->config;

        $v_oid          = $params["v_oid"];       // 商户发送的v_oid定单编号
        $v_pmode        = $params["v_pmode"];    // 支付方式（字符串）
        $v_pstatus      = $params["v_pstatus"];   //  支付状态 ：20（支付成功）；30（支付失败）
        $v_pstring      = $params["v_pstring"];   // 支付结果信息 ： 支付完成（当v_pstatus=20时）；失败原因（当v_pstatus=30时,字符串）；
        $v_amount       = $params["v_amount"];     // 订单实际支付金额
        $v_moneytype    = $params["v_moneytype"]; //订单实际支付币种
        $remark1        = $params["remark1"];      //备注字段1
        $remark2        = $params["remark2"];     //备注字段2
        $v_md5str       = $params["v_md5str"];   //拼凑后的MD5校验值
        //重新计算MD5
        $md5sign        = strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$config["KEY"]));
       
        $this->decryptReturn['order_id']       = $v_oid;     //订单号

        //签名是否正确
        if ($v_md5str === $md5sign){

            $this->decryptReturn['verify_status']  = true;      //签名状态

            //支付成功
            if($v_pstatus == "20"){

                $this->decryptReturn['trade_status'] = self::TRADE_SUCCESS;
                $this->decryptReturn['msg']          = self::TRADE_SUCCESS_MSG;
                
            }
        }else{
            $this->decryptReturn['msg'] = self::TRADE_SIGN_ERROR;
        }

        return $this->decryptReturn;
    }


    /**
     * @param $orderId
     * 主动查单接口
     */
    public function search(array $params)
    {
        // TODO: Implement search() method.
    }

}