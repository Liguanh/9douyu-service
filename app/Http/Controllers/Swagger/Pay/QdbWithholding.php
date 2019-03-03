<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/4
 * Time: 11:19
 */

namespace App\Http\Controllers\Swagger\Pay;

class QdbWithholding{

    /**
     * @SWG\Post(
     *   path="/recharge/index?random=QdbWithholding_signed",
     *   tags={"QdbWithholding"},
     *   summary="钱袋宝代扣加密",
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
     *     default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="支付渠道(钱袋宝代扣)",
     *      required=true,
     *      type="string",
     *      default="QdbWithholding",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(签约)",
     *      required=true,
     *      type="string",
     *     default="signed",
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
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="id_card",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="姓名",
     *      required=true,
     *      type="string",
     *   ),
     *    @SWG\Parameter(
     *      name="notify_url",
     *      in="formData",
     *      description="支付成功回调地址",
     *      required=true,
     *      type="string",
     *      default="http://www.baidu.com",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="钱袋宝代扣前数据加密。",
     *   )
     * )
     */
    public function signed(){}


    /**
     * @SWG\Post(
     *   path="/recharge/index?random=QdbWithholding_submit",
     *   tags={"QdbWithholding"},
     *   summary="钱袋宝代扣提交支付",
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
     *     default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="支付渠道(钱袋宝代扣)",
     *      required=true,
     *      type="string",
     *      default="QdbWithholding",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(提交支付)",
     *      required=true,
     *      type="string",
     *     default="submit",
     *   ),
     *    @SWG\Parameter(
     *      name="order_id",
     *      in="formData",
     *      description="订单编号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="sms_code",
     *      in="formData",
     *      description="短信验证码",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="钱袋宝支付。",
     *   )
     * )
     */
    public function submit(){}



    /**
     * @SWG\Post(
     *   path="/recharge/index?random=QdbWithholding_sendCode",
     *   tags={"QdbWithholding"},
     *   summary="钱袋宝代扣发送短信",
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
     *     default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="支付渠道(钱袋宝代扣)",
     *      required=true,
     *      type="string",
     *      default="QdbWithholding",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(发送短信)",
     *      required=true,
     *      type="string",
     *     default="sendCode",
     *   ),
     *    @SWG\Parameter(
     *      name="order_id",
     *      in="formData",
     *      description="订单编号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="钱袋宝发送短信。",
     *   )
     * )
     */
    public function sendCode(){}

    /**
     * @SWG\Post(
     *   path="/recharge/index?random=QdbWithholding_decrypt",
     *   tags={"QdbWithholding"},
     *   summary="钱袋宝代扣回调数据解密",
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
     *     default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="支付渠道(钱袋宝代扣)",
     *      required=true,
     *      type="string",
     *      default="QdbWithholding",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(解密)",
     *      required=true,
     *      type="string",
     *     default="decrypt",
     *   ),
     *    @SWG\Parameter(
     *      name="data",
     *      in="formData",
     *      description="回调返回的加密数据",
     *      required=true,
     *      type="string",
     *     default="HUiNgrUoeJqakE0XIWJWl18IY7/AEmTeUoZsaQOlz3ORDr++WjT5Dkh0G+o5BupaqVDIeBA5j3FL7KKyIUUqwni6RtSc9Rv2be9spEsa+r/GhCIIoprtdq2JY/Io4ukOjkBH2uT7NRrzpFzAh9UCq+xvdMdsbMIEIIMI1PXyKYukTHquvi/dt1EkRplTroKIUqI0BeSyJdAjBQskBm7bJbrO0Ss38+vph8VYnp/FRquIXfpPefg/DuUP4VPJjpUp6wnbd2EuTwa5UGD8MiHbDw6EVTHxW5PEoLSH3gPD8Gs/qp8S93K/Phuof5Upyq50flaTNgNph3xSA7D2Sux3pQ+DvBQuvf5Sp55ET8KZ7ZuXRc5hkHmjPRJG8OLTu9mhenFAhdIdcO2j+X/1UCkaFGrL5xtJlVV0LDQuFixhInq6UEYYgLxFokZ5WD4YflyN1Vvs6UNP6txgJ6NfXBVWkVMeoonHu612IrvF2er5JaPjtPocjYqNQ4X87SOdsZRgcDPJAR5tFpdEoOkzPsWr9Z6IcbuksdM52KTLudPHUSi8dwlpVq/B9Hejdn38cmQiXOY0h8F3lV/Zrhtn46zHx5Ibr5RqXiMt0/7LosG+92csQBkm5JaRK2ymZ7QTbwNEAVBwV9OmnD0ZLe5ZhJMTE8GJFLZ/2Q1SvRVg3QaueiU=",
     *   ),
     *   @SWG\Parameter(
     *      name="charset_name",
     *      in="formData",
     *      description="字符集",
     *      required=true,
     *      type="string",
     *     default="utf-8",
     *   ),
     *   @SWG\Parameter(
     *      name="userNo",
     *      in="formData",
     *      description="商户号",
     *      required=true,
     *      type="string",
     *     default="90010000022"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="钱袋宝回调解密。",
     *   )
     * )
     */
    public function decrypt(){}


    /**
     * @SWG\Post(
     *   path="/recharge/index?random=QdbWithholding_search",
     *   tags={"QdbWithholding"},
     *   summary="钱袋宝代扣主动查单",
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
     *     default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="支付渠道(钱袋宝代扣)",
     *      required=true,
     *      type="string",
     *      default="QdbWithholding",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(查单)",
     *      required=true,
     *      type="string",
     *     default="search",
     *   ),
     *    @SWG\Parameter(
     *      name="order_id",
     *      in="formData",
     *      description="订单编号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="钱袋宝主动查单。",
     *   )
     * )
     */
    public function search(){}
}