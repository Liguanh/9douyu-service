<?php
/**
 * User: zhangshuang
 * Date: 16/5/10
 * Time: 12:59
 * Desc: 联动优势代扣
 */

namespace App\Http\Models\Pay\Withholding;

use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Pay\PayModel;
use App\Lang\LangModel;
use App\Services\Pay\Withholding\Ump\Umpay;

class UmpModel extends PayModel{


    private $service;

    public function __construct()
    {
        parent::__construct('UMPAY_CONFIG');

        $this->service = new Umpay($this->config);
    }


    /**
     * @param $name
     * @param $cardNo
     * @param $idCard
     * @param $phone
     * 验卡接口
     */
    public function checkCard($param){
        
        $data = [
            'bank_account'  => $param['card_no'],
            'account_name'  => $param['name'],
            'identity_code' => $param['id_card'],
            //'phone'         => $param['phone'],
        ];

        if(isset($param['phone']) && $param['phone']){

            $data['phone'] = $param['phone'];
        }
        $result = $this->service->checkCard($data);
        //鉴权失败
        if($result['ret_code'] != '0000'){

            $this->checkCardReturn['result_code'] = $result['ret_code'];
            $this->checkCardReturn['status'] = self::TRADE_FAIL;
        }

        $this->checkCardReturn['msg'] = $result['ret_msg'];

        return $this->checkCardReturn;
    }

    

    /**
     * @param array $params
     * @return array
     * 支付接口
     */
    public function submit(array $params){

        //组装数据
        $config   = $this->config;
        $order_id = $params['order_id'];
        $cash     = $params['cash'];

        $params = [
            'order_id' => $order_id,
            'cash'     => $cash,
            'id_card'  => $params['id_card'],
            'card_no'  => $params['card_no'],
            'name'     => $params['name'],
            'notify_url'  => $params['notify_url']
        ];
        $return = $this->service->submit($params);
        //解析结果
        $this->submitReturn['order_id']       = $order_id;                //订单号
        $this->submitReturn['trade_no']       = isset($return['trade_no']) ? $return['trade_no'] : '';      //交易流水号

        if($return['ret_code'] == '0000'){

            $this->submitReturn['msg']          = self::TRADE_SUCCESS_MSG;
            $this->submitReturn['status']       = self::TRADE_SUCCESS;

        }else{

            $this->submitReturn["msg"]      = $return['ret_msg'];
        }

        return $this->submitReturn;
    }


    /**
     * @param array $params
     * @return array
     * 主动查单接口
     */
    public function search(array $params)
    {
        // TODO: Implement search() method.
        $orderId    = $params['order_id'];
        $this->searchReturn['order_id']       = $orderId;
        $res = $this->service->search($orderId);
        $this->format($res);

        return $this->searchReturn;

    }

    /**
     * @param array $returnData
     * 查单接口结果格式化
     */
    public function format(array $returnData)
    {
        // TODO: Implement format() method.
        if($returnData['ret_code'] == '0000'){
            switch($returnData['trade_state']){
                case 'TRADE_FAIL':{
                    $msg = self::TRADE_FAIL_MSG;
                    $tradeStatus = self::TRADE_FAIL;
                    break;
                }
                case 'TRADE_SUCCESS':{
                    $msg = self::TRADE_SUCCESS_MSG;
                    $tradeStatus = self::TRADE_SUCCESS;
                    break;
                }
                case 'WAIT_BUYER_PAY':{
                    $msg = self::TRADE_WAITING_MSG;
                    $tradeStatus = self::TRADE_WAITING;
                    break;
                }
                default :{
                    $msg = $returnData['ret_msg'];
                    $tradeStatus = self::TRADE_UNKNOW;
                    break;
                }
            }

        }else{

            $msg = $returnData['ret_msg'];
            $tradeStatus = self::TRADE_UNKNOW;
        }

        $this->searchReturn['status']   = $tradeStatus;
        $this->searchReturn['msg']      = $msg;
    }


}