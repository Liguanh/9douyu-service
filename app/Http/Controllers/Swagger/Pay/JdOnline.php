<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/4
 * Time: 09:49
 * Desc: 京东网银支付相关接口
 */
namespace App\Http\Controllers\Swagger\Pay;

class HnaOnline{

    /**
     * @SWG\Post(
     *   path="/recharge/index?random=JdOnline_encrypt",
     *   tags={"JdOnline"},
     *   summary="京东网银支付加密",
     *   @SWG\Parameter(
     *      name="partner_id",
     *      in="formData",
     *      description="商户号",
     *      required=true,
     *      type="string",
     *      default="110000901001",
     *   ),
     *    @SWG\Parameter(
     *      name="secret_sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="支付渠道(京东网银)",
     *      required=true,
     *      type="string",
     *      default="JdOnline",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(加密)",
     *      required=true,
     *      type="string",
     *     default="encrypt",
     *   ),
     *    @SWG\Parameter(
     *      name="order_id",
     *      in="formData",
     *      description="订单编号",
     *      required=true,
     *      type="string",
     *   ),
     *    @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="充值金额",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="bank_code",
     *      in="formData",
     *      description="银行编码,根据该编码跳转到指定的银行",
     *      required=true,
     *      type="string",
     *      default="3080",
     *   ),
     *    @SWG\Parameter(
     *      name="notify_url",
     *      in="formData",
     *      description="支付成功回调地址",
     *      required=true,
     *      type="string",
     *      default="http://www.wlask.com/notify.php",
     *   ),
     *    @SWG\Parameter(
     *      name="return_url",
     *      in="formData",
     *      description="支付完成后返回的商户地址",
     *      required=true,
     *      type="string",
     *     default="http://www.wlask.com/notify.php",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="京东网银支付前数据加密。",
     *   )
     * )
     */
    public function encrypt(){

    }


    /**
     * @SWG\Post(
     *   path="/recharge/index?random=JdOnline_decrypt",
     *   tags={"JdOnline"},
     *   summary="京东网银支付解密",
     *   @SWG\Parameter(
     *      name="partner_id",
     *      in="formData",
     *      description="商户号",
     *      required=true,
     *      type="string",
     *      default="110000901001",
     *   ),
     *    @SWG\Parameter(
     *      name="secret_sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="支付渠道(京东网银)",
     *      required=true,
     *      type="string",
     *      default="JdOnline",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(加密)",
     *      required=true,
     *      type="string",
     *     default="decrypt",
     *   ),
     *    @SWG\Parameter(
     *      name="v_moneytype",
     *      in="formData",
     *      description="币种",
     *      required=true,
     *      type="string",
     *     default="CNY",
     *   ),
     *    @SWG\Parameter(
     *      name="v_md5str",
     *      in="formData",
     *      description="验签",
     *      required=true,
     *      type="string",
     *      default="B49E12754F901C29BFE9528C69A2E6F9",
     *   ),
     *   @SWG\Parameter(
     *      name="v_pstring",
     *      in="formData",
     *      description="支付状态说明",
     *      required=true,
     *      type="string",
     *      default="支付成功",
     *   ),
     *   @SWG\Parameter(
     *      name="v_oid",
     *      in="formData",
     *      description="订单号",
     *      required=true,
     *      type="string",
     *      default="201606032051144889",
     *   ),
     *    @SWG\Parameter(
     *      name="v_pstatus",
     *      in="formData",
     *      description="支付状态码",
     *      required=true,
     *      type="string",
     *      default="20",
     *   ),
     *    @SWG\Parameter(
     *      name="v_pmode",
     *      in="formData",
     *      description="支付模式",
     *      required=true,
     *      type="string",
     *      default="SPDB",
     *   ),
     *    @SWG\Parameter(
     *      name="remark1",
     *      in="formData",
     *      description="remark1",
     *      required=true,
     *      type="string",
     *     default="9douyu",
     *   ),
     *   @SWG\Parameter(
     *      name="v_amount",
     *      in="formData",
     *      description="充值金额",
     *      required=true,
     *      type="string",
     *     default="7500.00",
     *   ),
     *   @SWG\Parameter(
     *      name="remark2",
     *      in="formData",
     *      description="回调地址",
     *      required=true,
     *      type="string",
     *     default="[url:=http://api.9douyu.com/API/pay/notice/platform/cbpay]",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="京东网银支付完成后的回调数据解密。",
     *   )
     * )
     */
    public function decrypt(){}



}



