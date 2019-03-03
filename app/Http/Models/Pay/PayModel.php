<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/10
 * Time: 13:03
 */
namespace App\Http\Models\Pay;
use Laravel\Lumen\Application;

abstract class PayModel{

    protected $config = [];

    /**
     * trade_status
     * 1、success        成功
     * 2、fail           失败
     * 3、waiting        待支付
     * 4、unkonw         未知
     */

    /**
     * 支付状态
     */
    const   TRADE_SUCCESS   = 'success',        //支付成功
            TRADE_FAIL      = 'fail',           //支付失败
            TRADE_WAITING   = 'waiting',        //等待付款
            TRADE_DEALING   = 'dealing',        //处理中
            TRADE_UNKNOW    = 'unknow';         //未知状态


    /**
     * 支付状态说明
     */
    const   TRADE_SUCCESS_MSG   = '支付成功',
            TRADE_FAIL_MSG      = '支付失败',
            TRADE_WAITING_MSG   = '等待支付',
            TRADE_DEALING_MSG   = '处理中',
            TRADE_UNKNOW_MSG    = '状态未知',
            TRADE_NOT_FONUD     = '订单号不存在',
            TRADE_SIGN_ERROR    = '签名错误';
    /**
     * @var array
     * 解密接口 返回结果
     */
    protected $decryptReturn = [

        'verify_status' => false,        //签名状态
        'trade_status'  => 'fail',      //支付状态
        'msg'           =>  '支付失败',   //返回消息说明
        'trade_no'      => '',          //交易号
        'order_id'      => ''           //订单号
    ];


    /**
     * @var array
     * 查单 支付接口 返回结果
     */
    protected $searchReturn = [

        'status'        => 'fail',      //支付状态
        'msg'           => '支付失败',   //返回消息说明
        'trade_no'      => '',          //交易号
        'order_id'      => ''           //订单号
    ];

    /**
     * @var array
     * 支付返回结果
     */
    protected $submitReturn = [

        'status'        => 'fail',      //支付状态
        'msg'           => '支付失败',   //返回消息说明
        'trade_no'      => '',          //交易号
        'order_id'      => ''           //订单号
    ];

    /**
     * @var array
     * 验卡返回结果
     */
    protected $checkCardReturn = [

        'status'        => 'success',      //支付状态
        'msg'           => '鉴权成功',   //返回消息说明
    ];


    /**
     * @var array
     * 约签接口 返回结果
     */
    protected $signedReturn = [

        'order_id'  => '',
        'status'    => 'fail',
        'msg'       => '签约失败',
        'sign_id'   => '',
    ];

    public function __construct($key)
    {
        $app        = new Application();
        $app->configure('pay');
        $config     = $app['config']['pay'][$key];

        $this->config =  (array)$config;
    }

    /**
     * @param $orderId
     * @return mixed
     * 根据订单号查询接口
     */
    abstract protected function search(array $params);


    /**
     * @param array $params
     * @return mixed
     * 加密接口
     */
    protected function encrypt(array $params){}


    /**
     * @param array $params
     * @return mixed
     * 解密接口
     */
    protected function decrypt(array $params){}


    /**
     * @param array $params
     * 发送验证码接口
     */
    protected function sendCode(array $params) {}

    /**
     * @param array $params
     * 签约接口
     */
    protected function signed(array $params) {}


    /**
     * @param array $params
     * @return mixed
     * 支付接口
     */
    protected function sumbit(array $params){}

    /**
     * @param array $returnData
     * 格式化查单结果
     */
     protected function format(array $returnData){}


}