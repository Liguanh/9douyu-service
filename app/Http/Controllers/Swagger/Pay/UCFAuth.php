<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/10/19
 * Time: 下午4:21
 */

namespace App\Http\Controllers\Swagger\Pay;


class UCFAuth
{

    /**
     * @SWG\Post(
     *   path="/recharge/index?random=UCFAuth_encrypt",
     *   tags={"UCFAuth"},
     *   summary="先锋认证支付加密",
     *   @SWG\Parameter(
     *      name="partner_id",
     *      in="formData",
     *      description="商户号",
     *      required=true,
     *      type="string",
     *      default="100000178",
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
     *      description="支付渠道(先锋认证支付)",
     *      required=true,
     *      type="string",
     *      default="UCFAuth",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(加密)",
     *      required=true,
     *      type="string",
     *     default="encrypt",
     *   ),
     *   @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="用户手机号",
     *      required=true,
     *      type="string",
     *     default="18510258037",
     *   ),
     *     @SWG\Parameter(
     *      name="order_id",
     *      in="formData",
     *      description="订单号",
     *      required=true,
     *      type="string",
     *      default="JDY_201611301259337945",
     *   ),
     *    @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户编号",
     *      required=true,
     *      type="string",
     *      default="82692",
     *   ),
     *     @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="姓名",
     *      required=true,
     *      type="string",
     *      default="张爽",
     *   ),
     *    @SWG\Parameter(
     *      name="id_card",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *     default="429004199005040339",
     *   ),
     *
     *    @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="充值金额",
     *      required=true,
     *      type="string",
     *      default="1000",
     *   ),
     *   @SWG\Parameter(
     *      name="bank_code",
     *      in="formData",
     *      description="银行编码",
     *      required=true,
     *      type="string",
     *      default="ICBC",
     *   ),
     *   @SWG\Parameter(
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *     default="6214830104420491",
     *   ),
     *    @SWG\Parameter(
     *      name="bank_name",
     *      in="formData",
     *      description="银行名称",
     *      required=true,
     *      type="string",
     *      default="招商银行",
     *   ),
     *
     *    @SWG\Parameter(
     *      name="platform",
     *      in="formData",
     *      description="三端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="pc",
     *      enum={"pc", "wap", "app"}
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
     *     description="先锋认证支付前数据加密。",
     *   )
     * )
     */
    public function encrypt(){

    }

    /**
     * @SWG\Post(
     *   path="/recharge/index?random=UCFAuth_notice_decrypt",
     *   tags={"UCFAuth"},
     *   summary="先锋认证支付回调数据解密",
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="支付渠道(先锋认证支付)",
     *      required=true,
     *      type="string",
     *      default="UCFAuth",
     *   ),
     *     @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(解密)",
     *      required=true,
     *      type="string",
     *     default="decrypt",
     *   ),
     *    @SWG\Parameter(
     *      name="decrypt_type",
     *      in="formData",
     *      description="解密类型:1-notice 回调数据 2-return 支付返回数据",
     *      required=true,
     *      type="string",
     *     default="notice",
     *   ),
     *     @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="签名",
     *      required=true,
     *      type="string",
     *     default="KdwU5F3vt266i3rlEVGlYNIc2seMNJ7XWTZinTHURvMVmlqMM6E8x+Dot5rFFV0ptBdQ4OObrHdAd/j/qpMxI0/YPHzIGKPpy5htiJYAY57vWYj6FDt1XOPDeT2qdUZV5zo4t6EfHQwxNUZ8nIvLcwk2mRpjw/4Uoa1FYamRc8s=",
     *   ),
     *     @SWG\Parameter(
     *      name="amount",
     *      in="formData",
     *      description="支付金额",
     *      required=true,
     *      type="string",
     *     default="300",
     *   ),
     *     @SWG\Parameter(
     *      name="tranTime",
     *      in="formData",
     *      description="交易日期",
     *      required=true,
     *      type="string",
     *     default="2016-12-01 14:30:10",
     *   ),
     *     @SWG\Parameter(
     *      name="tradeNo",
     *      in="formData",
     *      description="交易流水号",
     *      required=true,
     *      type="string",
     *     default="201612011429531031610000153413",
     *   ),
     *     @SWG\Parameter(
     *      name="merchantId",
     *      in="formData",
     *      description="商户号",
     *      required=true,
     *      type="string",
     *     default="M200000400",
     *   ),
     *     @SWG\Parameter(
     *      name="bankName",
     *      in="formData",
     *      description="银行名称",
     *      required=true,
     *      type="string",
     *     default="招商银行",
     *   ),
     *     @SWG\Parameter(
     *      name="orderStatus",
     *      in="formData",
     *      description="交易编码",
     *      required=true,
     *      type="string",
     *     default="00",
     *   ),
     *     @SWG\Parameter(
     *      name="bankCardNo",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *     default="6214830104420491",
     *   ),
     *     @SWG\Parameter(
     *      name="outOrderId",
     *      in="formData",
     *      description="订单号",
     *      required=true,
     *      type="string",
     *     default="JDY_201612011428366688",
     *   ),
     *     @SWG\Parameter(
     *      name="bankId",
     *      in="formData",
     *      description="银行编码",
     *      required=true,
     *      type="string",
     *     default="CMB",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="先锋认证支付完成后的回调数据解密。",
     *   )
     * )
     */
    public function decryptNotice(){}



    /**
     * @SWG\Post(
     *   path="/recharge/index?random=UCFAuth_return_decrypt",
     *   tags={"UCFAuth"},
     *   summary="先锋认证支付返回数据解密",
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
     *      description="支付渠道(先锋认证支付)",
     *      required=true,
     *      type="string",
     *      default="UCFAuth",
     *   ),
     *     @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(解密)",
     *      required=true,
     *      type="string",
     *     default="decrypt",
     *   ),
     *    @SWG\Parameter(
     *      name="decrypt_type",
     *      in="formData",
     *      description="解密类型:1-notice 回调数据 2-return 支付返回数据",
     *      required=true,
     *      type="string",
     *     default="return",
     *   ),
     *    @SWG\Parameter(
     *      name="payStatus",
     *      in="formData",
     *      description="支付结果编码",
     *      required=true,
     *      type="string",
     *      default="00",
     *   ),
     *   @SWG\Parameter(
     *      name="outOrderId",
     *      in="formData",
     *      description="商户订单号",
     *      required=true,
     *      type="string",
     *      default="JDY_201612011122106844",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="先锋认证支付完成后的返回数据解密。",
     *   ),
     *   @SWG\Response(
     *     response="500",
     *     description="失败状态,存在参数的缺失.",
     *   )
     * )
     */
    public function decryptReturn(){}




    /**
     * @SWG\Post(
     *   path="/recharge/index?random=UCFAuth_search",
     *   tags={"UCFAuth"},
     *   summary="先锋认证支付主动查单",
     *   @SWG\Parameter(
     *      name="partner_id",
     *      in="formData",
     *      description="商户号",
     *      required=true,
     *      type="string",
     *      default="100000178",
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
     *      description="支付渠道(先锋认证支付)",
     *      required=true,
     *      type="string",
     *      default="UCFAuth",
     *   ),
     *     @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(查单)",
     *      required=true,
     *      type="string",
     *     default="search",
     *   ),
     *   @SWG\Parameter(
     *      name="order_id",
     *      in="formData",
     *      description="查询订单号",
     *      required=true,
     *      type="string",
     *      default="JDY_201606041316533538",
     *   ),

     *   @SWG\Response(
     *     response=200,
     *     description="先锋认证支付主动查单",
     *   )
     * )
     */
    public function search(){}

    /**
     * @SWG\Post(
     *   path="/recharge/index?random=UCFAuth_unbind",
     *   tags={"UCFAuth"},
     *   summary="先锋认证解绑银行卡",
     *   @SWG\Parameter(
     *      name="driver",
     *      in="formData",
     *      description="支付渠道(先锋认证支付)",
     *      required=true,
     *      type="string",
     *      default="UCFAuth",
     *   ),
     *     @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(查单)",
     *      required=true,
     *      type="string",
     *     default="unbind",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="string",
     *      default="82692",
     *   ),
     *   @SWG\Parameter(
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *      default="6214830104420491",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="先锋认证银行卡解绑成功",
     *   )
     * )
     */
    public function unbind(){}

}