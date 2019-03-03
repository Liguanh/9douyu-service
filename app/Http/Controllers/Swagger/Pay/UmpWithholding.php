<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/4
 * Time: 11:27
 */
namespace App\Http\Controllers\Swagger\Pay;

class UmpWithholding{

    /**
     * @SWG\Post(
     *   path="/recharge/index?random=UmpWithholding_checkCard",
     *   tags={"UmpWithholding"},
     *   summary="联动优势代扣验卡",
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
     *      description="支付渠道(联动优势代扣)",
     *      required=true,
     *      type="string",
     *      default="UmpWithholding",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(验卡)",
     *      required=true,
     *      type="string",
     *     default="checkCard",
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
     *      required=false,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="姓名",
     *      required=true,
     *      type="string",
     *  ),
     *   @SWG\Response(
     *     response=200,
     *     description="联动优势代扣验卡。",
     *   )
     * )
     */
    public function checkCard(){}
    

    /**
     * @SWG\Post(
     *   path="/recharge/index?random=UmpWithholding_submit",
     *   tags={"UmpWithholding"},
     *   summary="联动优势代扣提交支付",
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
     *      description="支付渠道(联动优势代扣)",
     *      required=true,
     *      type="string",
     *      default="UmpWithholding",
     *   ),
     *   @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(支付)",
     *      required=true,
     *      type="string",
     *     default="submit",
     *   ),
     *   @SWG\Parameter(
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="姓名",
     *      required=true,
     *      type="string",
     *  ),
     *   @SWG\Parameter(
     *      name="id_card",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
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
     *  @SWG\Parameter(
     *      name="notify_url",
     *      in="formData",
     *      description="支付成功回调地址",
     *      required=true,
     *      type="string",
     *      default="http://www.wlask.com/notify.php",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="联动优势代扣提交支付。",
     *   )
     * )
     */
    public function submit(){}


    /**
     * @SWG\Post(
     *   path="/recharge/index?random=UmpWithholding_search",
     *   tags={"UmpWithholding"},
     *   summary="联动优势代扣主动查单",
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
     *      description="支付渠道(联动优势代扣)",
     *      required=true,
     *      type="string",
     *      default="UmpWithholding",
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
     *     description="联动优势主动查单。",
     *   )
     * )
     */
    public function search(){}

}