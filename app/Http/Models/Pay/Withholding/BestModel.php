<?php
/**
 * User: zhangshuang
 * Date: 16/5/10
 * Time: 13:02
 * Desc: 翼支付代扣
 */

namespace App\Http\Models\Pay\Withholding;

use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Pay\PayModel;
use App\Lang\LangModel;
use App\Services\Pay\Withholding\Best\BestPay;


class BestModel extends PayModel{

    private $service;

    public function __construct()
    {
        parent::__construct('BEST_CONFIG');

        $this->service = new Bestpay($this->config);
    }


    //鉴权方法,得到签约sign
    public function signed(array $params){

        $orderId = $params['order_id'];

        $this->signedReturn['order_id'] = $orderId;

        //3.组装参数开始签约
        $data = array(
            'orderId'       => $orderId,
            'card_no'       => $params['card_no'],
            'identity_card' => $params['id_card'],
            'real_name'     => $params['name'],
            'bankCode'      => $params['bank_code'],
            'user_ip'       => $params['user_ip']
        );
        $result = $this->service->signed($data);
        //签约成功
        if($result['code']=='000000'){

            $this->signedReturn['sign_id']      = $result['result']['signId'];
            $this->signedReturn['status']       = self::TRADE_SUCCESS;

        }

        return $this->signedReturn;


    }


    /**
     * @param array $params
     * @return array
     * 支付接口
     */
    public function submit(array $params){

        //组装参数
        $order_id = $params['order_id'];
        $data     = array(
            'orderId'   => $order_id,
            'cash'      => $params['cash'],
            'card_no'   => $params['card_no'],
            'signId'    => $params['sign_id'],
            'user_ip'   => $params['user_ip']
        );

        //解析结果
        $return = $this->service->submit($data);

        $this->submitReturn['order_id']       = $order_id;    //订单号

        if($return['code'] == '000000'){

            $this->submitReturn['status']       = self::TRADE_SUCCESS;
            $this->submitReturn['msg']          = self::TRADE_SUCCESS_MSG;
        }

        return $this->submitReturn;
    }

    /**
     * @param array $params
     * @return array
     * 订动查单接口
     */
    public function search(array $params){

        $orderId = $params['order_id'];

        $data = array(
            'order_id'  => $params['order_id'],
            'user_ip'   => $params['user_ip']
        );
        $res = $this->service->search($data);
        $this->searchReturn['order_id'] = $orderId;

        $this->format($res);

        return $this->searchReturn;
    }

    /**
     * @param array $returData
     * 格式化查单结果
     */
    public function format(array $returnData){

        if($returnData['code'] == '000000'){
            $tradeStatus = self::TRADE_SUCCESS;
        }else{
            $tradeStatus = self::TRADE_FAIL;
        }

        $msg = $returnData['msg'];
        $this->searchReturn['status']       = $tradeStatus;
        $this->searchReturn['msg']          = $msg;
    }



}