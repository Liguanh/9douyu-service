<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/10/19
 * Time: 下午4:21
 */

namespace App\Http\Controllers\Swagger\Pay;


class BFAuth
{

    /**
     * @SWG\Post(
     *   path="/recharge/index?random=BFAuth_encrypt",
     *   tags={"BFAuth"},
     *   summary="宝付认证支付加密",
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
     *      description="支付渠道(宝付认证支付)",
     *      required=true,
     *      type="string",
     *      default="BFAuth",
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
     *      description="银行",
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
     *   ),
     *   @SWG\Parameter(
     *      name="id_card",
     *      in="formData",
     *      description="身份证号",
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
     *     description="宝付认证支付前数据加密。",
     *   )
     * )
     */
    public function encrypt(){

    }

    /**
     * @SWG\Post(
     *   path="/recharge/index?random=BFAuth_decrypt",
     *   tags={"BFAuth"},
     *   summary="宝付认证支付返回数据解密",
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
     *      description="支付渠道(宝付认证支付)",
     *      required=true,
     *      type="string",
     *      default="BFAuth",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(解密)",
     *      required=true,
     *      type="string",
     *      default="decrypt",
     *   ),
     *   @SWG\Parameter(
     *      name="data_content",
     *      in="formData",
     *      description="报文(解密)",
     *      required=true,
     *      type="string",
     *   ),
     *
     *   @SWG\Response(
     *     response=200,
     *     description="连连认证支付完成后的返回数据解密。",
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
     *   path="/recharge/index?random=BFAuth_search",
     *   tags={"BFAuth"},
     *   summary="宝付认证支付主动查单",
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
     *      description="支付渠道(宝付认证支付)",
     *      required=true,
     *      type="string",
     *      default="BFAuth",
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
     *     description="宝付认证支付主动查单",
     *   )
     * )
     */
    public function search(){}

}