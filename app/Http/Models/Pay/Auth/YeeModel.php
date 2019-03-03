<?php
/**
 * User: zhangshuang
 * Date: 16/5/10
 * Time: 12:58
 * Desc: 易宝认证支付
 */

namespace App\Http\Models\Pay\Auth;

use App\Http\Models\Pay\PayModel;
use App\Services\Pay\Auth\Yee\YeePay;
use App\Tools\ToolMoney;


class YeeModel extends PayModel{


    public $service;

    public function __construct()
    {
        parent::__construct('YEEPAY_CONFIG');
        $this->service = new YeePay($this->config);
    }


    /**
     * @param array $params
     * @return string
     * 加密接口
     */
    public function encrypt(array $params)
    {
        // TODO: Implement encrypt() method.

        $config         = $this->config;

        $userId         = $params['user_id'];
        $cardNo         = $params['card_no'];
        $cash           = $params['cash'];
        $idcard         = $params['id_card'];
        $name           = $params['name'];
        $orderId        = $params['order_id'];

        $yeePay         = new \yeepayMPay($config['ACCOUNT'],$config['PUBLICKEY'],$config['PRIVATEKEY'],$config['YEEPAYPUBLICKEY']);

        $transtime      = time();//交易时间，是每次支付请求的时间，注意此参数在进行多次支付的时候要保持一致。
        $product_catalog= $config['PRODUCTCATALOG'];//商品类编码是我们业管根据商户业务本身的特性进行配置的业务参数。
        $identity_id    = $yeePay->getIdentityId($userId, $cardNo);
        $identity_type  = $config['IDENTITYTYPE'];//支付身份标识类型码
        $user_ip        = $params['user_ip'];
        $user_ua        = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $callbackurl    = $params['notify_url'];

        $fcallbackurl   = $params['return_url'];

        $product_name   = $config['PRODUCTNAME'];
        $product_desc   = $config['PRODUCTDESC'];
        $terminaltype   = $config['TERMINALTYPE'];
        $terminalid     = $identity_id;
        $amount         = $yeePay->getFormatAmount($cash);
        $paytypes       = $config['PAYTYPES'];
        $orderexp_date  = $config['ORDEREXPDATE'];
        $currency       = $config['CURRENCY'];
        $idcardtype     = $config['IDCARDTYPE'];
        $owner          = $name;

        $url            = $yeePay->webPay($orderId,$transtime,$amount,$cardNo,
            $idcardtype,$idcard,$owner,$product_catalog,
            $identity_id,$identity_type,$user_ip,$user_ua,
            $callbackurl,$fcallbackurl,$currency,$product_name,
            $product_desc,$terminaltype,$terminalid,$orderexp_date);

        return [
            'url' => $url,
            'parameter' => []
        ];
    }

    /**
     * @param array $params
     * 解密接口
     */
    public function decrypt(array $params)
    {
        // TODO: Implement decrypt() method.

        $data       = $params["data"];
        $encryptkey = $params["encryptkey"];

        try {

            $config         = $this->config;
            $yeePay         = new \yeepayMPay($config['ACCOUNT'],$config['PUBLICKEY'],$config['PRIVATEKEY'],$config['YEEPAYPUBLICKEY']);
            $return         = $yeePay->callback($data, $encryptkey);
            $this->decryptReturn['verify_status']  = true;      //签名状态
            $this->decryptReturn['trade_no']       = $return['yborderid'];  //交易流水号
            $this->decryptReturn['order_id']       = $return['orderid'];  //订单号
            $this->decryptReturn['amount']         = ToolMoney::formatDbCashDelete($return['amount']);//订单金额

            $status        = (int)$return['status'];    //支付状态

            //支付成功
            if($status === 1) {

                $this->decryptReturn['trade_status'] = self::TRADE_SUCCESS;
                $this->decryptReturn['msg']          = self::TRADE_SUCCESS_MSG;
            }

        }catch (\Exception $e) {

                $this->decryptReturn['msg']          = self::TRADE_SIGN_ERROR;

        }

        return $this->decryptReturn;
    }

    /**
     * @param $orderId
     * 查单接口
     */
    public function search(array $params)
    {
        // TODO: Implement search() method.

        $orderId    = $params['order_id'];
        $returnData = $this->service->search($orderId);
        $this->searchReturn['order_id']       = $orderId;

        $this->format($returnData);

        return $this->searchReturn;
    }

    /**
     * @param array $returnData
     * 格式化查询结果
     */
    protected function format(array $returnData){

        if($returnData){
            /**
             * 易宝主动查单结果状态列表
             * 0-待付（创建的订单未支付成功）
             * 1-已付（订单已经支付成功）
             * 2-已撤销（待支付订单有效期为1天，过期后自动撤销）
             * 3-阻断交易（订单因为高风险而被阻断）
             * 4-失败
             * 5-处理中
             */

            switch($returnData['status']){
                case '0':{
                    $msg = self::TRADE_WAITING_MSG;
                    $tradeStatus = self::TRADE_WAITING;
                    break;
                }
                case '1':{
                    $msg = self::TRADE_SUCCESS_MSG;
                    $tradeStatus = self::TRADE_SUCCESS;
                    break;
                }
                case '4':{
                    $msg = self::TRADE_FAIL_MSG;
                    $tradeStatus = self::TRADE_FAIL;
                    break;
                }
                case '5':{
                    $msg = self::TRADE_DEALING_MSG;
                    $tradeStatus = self::TRADE_DEALING;
                    break;
                }
                default :{
                    $msg = self::TRADE_UNKNOW_MSG;
                    $tradeStatus = self::TRADE_UNKNOW;
                    break;
                }
            }

        }else{
            $msg         = self::TRADE_NOT_FONUD;
            $tradeStatus = self::TRADE_UNKNOW;
        }

        $this->searchReturn['status']   = $tradeStatus;
        $this->searchReturn['msg']            = $msg;
    }
}