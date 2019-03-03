<?php
/**
 * User: zhangshuang
 * Date: 16/5/10
 * Time: 12:58
 * Desc: 钱袋宝代扣支付
 */

namespace App\Http\Models\Pay\Withholding;

use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Pay\PayModel;
use App\Lang\LangModel;
use App\Services\Pay\Withholding\Qdb\Qdbpay;


class QdbModel extends PayModel{

    private $service;

    public function __construct()
    {
        parent::__construct('QDBPAY_CONFIG');

        $this->service = new Qdbpay($this->config);
    }


    /**
     * @param array $params
     * @return array
     * 签约接口
     */
    public function signed(array $params){

        //$orderId = date("YmdHis") . rand(1000,9999);

        //$params['order_id'] = $orderId;


        $this->signedReturn['order_id'] = $params['order_id'];

        $res = $this->service->signed($params);

        //签约成功
        if($res['orderStatus'] == 1){

            $this->signedReturn['status'] = self::TRADE_SUCCESS;
        }

        $this->signedReturn['msg'] = $res['retMsg'];

        return $this->signedReturn;

    }

    /**
     * @param array $params
     * 钱袋宝发送验证码接口
     */
    public function sendCode(array $params)
    {
        $orderId = $params['order_id'];
        $return = [
            'status' => self::TRADE_FAIL,
            'order_id'  => $orderId
        ];

        $res = $this->service->sendCode($orderId);
        if($res['orderStatus'] == 1){
            $return['status'] = self::TRADE_SUCCESS;
        }

        $return['msg'] = $res['retMsg'];

        return $return;
    }

    /**
     * @param array $params
     * @return array
     * 支付接口
     */
    public function submit(array $params){

        //组装参数
        $order_id = $params['order_id'];
        $data = array(
            'no_order'  => $order_id,
            'validCode' => $params['sms_code'],
        );

        //解析结果
        $return = $this->service->submit($data);

        $this->submitReturn['order_id']       = $order_id;    //订单号

        //支付成功
        if($return['orderStatus'] == 1){
            $this->submitReturn['status'] = self::TRADE_SUCCESS;
        }

        $this->submitReturn['msg'] = $return['retMsg'];
        
        return $this->submitReturn;
    }

    public function encrypt(array $params)
    {
        // TODO: Implement encrypt() method.
    }

    /**
     * @param array $params
     * @return array
     * 回调解密接口
     */
    public function decrypt(array $params)
    {


        // TODO: Implement decrypt() method.
        $vo = $this->service->decrypt($params);

        if(is_array($vo) && isset($vo['info'])){

            $orderInfo = json_decode($vo['info'],true);

            $this->decryptReturn['verify_status']  = true;                     //签名状态
            $this->decryptReturn['trade_no']       = $orderInfo['orderId'];  //交易流水号
            $this->decryptReturn['order_id']       = $orderInfo['no_order'];     //订单号


            if($orderInfo['retCode'] == '0000'){
                $this->decryptReturn['trade_status'] = self::TRADE_SUCCESS;
                $this->decryptReturn['msg']          = self::TRADE_SUCCESS_MSG;

            }
        }else{
            $this->decryptReturn['msg'] = self::TRADE_SIGN_ERROR;
        }

        return $this->decryptReturn;
        
    }

    /**
     * @param array $parmas
     * @return array
     * 订动查单接口
     */
    public function search(array $parmas){

        $orderId = $parmas['order_id'];
        $res = $this->service->search($orderId);

        $this->searchReturn['order_id'] = $orderId;
        $this->searchReturn['trade_no'] = isset($res['orderId']) ? $res['orderId'] : '';
        $this->format($res);

        return $this->searchReturn;

    }


    /**
     * @param array $returnData
     * 查单接口结果格式化
     */
    public function format(array $returnData)
    {
        // TODO: Implement encrypt() method.

        if($returnData['orderStatus'] == 1){
            $msg = self::TRADE_SUCCESS_MSG;
            $tradeStatus = self::TRADE_SUCCESS;
        }else{
            $tradeStatus = self::TRADE_FAIL;
            $msg = $returnData['retMsg'];
        }

        $this->searchReturn['status']   = $tradeStatus;
        $this->searchReturn['msg']      = $msg;
    }
    
}