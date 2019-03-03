<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/10/19
 * Time: 上午11:37
 */

namespace App\Http\Models\Pay\Auth;


use App\Http\Models\Pay\PayModel;
use App\Services\Pay\Auth\BF\BFPay;
use App\Tools\ToolMoney;

class BFModel extends PayModel
{
    const APP_REQUEST_FROM_WAP  = 'wap',
          APP_REQUEST_FROM_PC   = 'pc',
          APP_REQUEST_FROM_APP  = 'app';

    private $service;

    public function __construct()
    {
        parent::__construct('BFPAY_CONFIG');

        $this->service = new BFPay($this->config);

    }

    /**
     * @param array $params
     * @return array
     * 宝付支付加密方法
     */
    public function encrypt(array $params)
    {

        $config         = $this->config;

        $from           = strtolower($params['platform']);  //来源 PC WAP APP

        //明文参数
        $parameters         = [
            'version'       => $config['version'],
            'input_charset' => $config['input_charset'],
            'language'      => $config['language'],
            'terminal_id'   => $config['terminal_id'],
            'txn_type'      => $config['txn_type'],
            'member_id'     => $config['member_id'],
            'data_type'     => $config['data_type'],
            //'data_content'  => $data_content,
            'back_url'      => $params['return_url'],
        ];

        $payUrl = '';

        if($from == self::APP_REQUEST_FROM_WAP){
            $txnSubType = '01';
            $payUrl     = $config['wap_pay_url'];
        }elseif($from == self::APP_REQUEST_FROM_APP){
            unset($parameters['back_url']);
            $txnSubType = '02';
            $payUrl     = $config['sdk_pay_url'];
        }elseif($from == self::APP_REQUEST_FROM_PC){
            $txnSubType = '03';
            $payUrl     = $config['pc_pay_url'];
        }

        $parameters['txn_sub_type']  = $txnSubType;


        //加密参数
        $enParams           = [
            'txn_sub_type'  => $txnSubType,
            'biz_type'      => $config['biz_type'],
            'terminal_id'   => $config['terminal_id'],
            'member_id'     => $config['member_id'],
            'pay_code'      => $params['bank_code'],
            'acc_no'        => $params['card_no'],
            'id_card_type'  => $config['id_card_type'],
            'id_card'       => strtoupper($params['id_card']),
            'id_holder'     => $params['name'],
            'trans_id'      => $params['order_id'],
            'txn_amt'       => ToolMoney::formatDbCashAdd($params['cash']),
            'trade_date'    => $this->return_time(),
            'return_url'    => $params['notify_url'],
            'page_url'      => $params['return_url'],
        ];


        $server = new BFPay($config);
        $dataContent = $server->encrypt($enParams);
        $parameters['data_content'] = $dataContent;

        if($from == self::APP_REQUEST_FROM_APP){
            $parameters = $server->sdkGetTradeNo($parameters);
        }


        $return = [
            'url'       => $payUrl,
            'parameter' => $parameters,
        ];

        return $return;

    }

    /**
     * @param array $params
     * @return array
     * 宝付支付解密方法
     */
    public function decrypt(array $params)
    {

        $config         = $this->config;
        $enData         = $params["data_content"];
        $server         = new BFPay($config);
        $enData         = $server->decrypt($enData);

        if(!empty($enData)){

            $this->decryptReturn['verify_status']  = true;      //签名状态

            if($enData['resp_code'] == '0000'){

                $this->decryptReturn['trade_no']       = $enData['trans_no'];  //交易流水号
                $this->decryptReturn['amount']         = $enData['succ_amt'];//订单金额
                $this->decryptReturn['trade_status']   = self::TRADE_SUCCESS;

            }
            $this->decryptReturn['msg']             = $enData['resp_msg'];
            $this->decryptReturn['order_id']        = $enData['trans_id'];  //订单号

        }

        return $this->decryptReturn;

    }

    /**
     * @param array $params
     * @return array
     * 宝付查单方法
     */
    public function search(array $params){

        $config         = $this->config;

        //加密参数
        $enParams = [
            'orig_trans_id'     => $params['order_id'],
            'trans_serial_no'   => $params['order_id'].$this->get_transid().$this->rand4(),
            'terminal_id'       => $config['terminal_id'],
            'member_id'         => $config['member_id'],
        ];

        $server = new BFPay($config);
        $dataContent = $server->encrypt($enParams);

        //明文参数
        $parameters = [
            'version'       => $config['version'],
            'input_charset' => $config['input_charset'],
            'language'      => $config['language'],
            'terminal_id'   => $config['terminal_id'],
            'member_id'     => $config['member_id'],
            'data_type'     => $config['data_type'],
            'data_content'  => $dataContent,
        ];

        $result = $server->search($parameters);

        $this->format($result);

        return $this->searchReturn;

    }

    /**
     * @param array $returnData
     * @desc 格式化
     */
    public function format(array $returnData){

        $code = $returnData['resp_code'];
        //查询订单成功
        if($code == '0000'){

            $tradeStatus = self::TRADE_SUCCESS;
            $this->searchReturn['trade_no']     = $returnData['trade_no'];

        }elseif($code == 'FI00054'){

            $tradeStatus = self::TRADE_WAITING;
        }else{

            $tradeStatus = self::TRADE_FAIL;
        }

        $msg = $returnData['resp_msg'];
        $cash   = isset($returnData["succ_amt"])?intval($returnData["succ_amt"]):"-1";
        $this->searchReturn['status']       = $tradeStatus;     //支付状态
        $this->searchReturn['msg']          = $msg;
        $this->searchReturn['order_id']     = $returnData['orig_trans_id'];
        $this->searchReturn['cash']         = $cash;

    }

    /**
     * @return int
     * 生成时间戳
     */
    function get_transid(){
        return strtotime(date('Y-m-d H:i:s',time()));
    }

    /**
     * @return int
     * 生成四位随机数
     */
    function rand4(){
        return rand(1000,9999);
    }

    /**
     * @return bool|string
     * 生成时间
     */
    function return_time(){
        return date('YmdHis',time());
    }

}