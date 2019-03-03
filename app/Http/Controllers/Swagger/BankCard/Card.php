<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/4
 * Time: 16:31
 */
namespace App\Http\Controllers\Swagger\BankCard;

class Card{

    /**
     * @SWG\Post(
     *   path="/getCardInfo",
     *   tags={"Card"},
     *   summary="连连认证支付卡bin",
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
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *      default="6214830104420491",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="连连认证支付主动查单,有IP白名单的限制,本地无法请求。",
     *   )
     * )
     */
    public function getCardInfo(){}



    /**
     * @SWG\Post(
     *   path="/checkDepositCard",
     *   tags={"Card"},
     *   summary="融宝储蓄卡三\四要素鉴权",
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
     *      description="手机号(传递该参数则为四要素验卡)",
     *      required=false,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="姓名",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="融宝储蓄卡鉴权接口。",
     *   )
     * )
     */
    public function checkDepositCard(){}


    /**
     * @SWG\Post(
     *   path="/checkCreditCard",
     *   tags={"Card"},
     *   summary="融宝信用卡四\六要素鉴权",
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
     *   ),
     *   @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
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
     *      name="cvv2",
     *      in="formData",
     *      description="信用卡后三位校验码",
     *      required=false,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="validthru",
     *      in="formData",
     *      description="信用卡用效期",
     *      required=false,
     *      type="string",
     *     default="02/21"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="融宝信用卡鉴权接口。",
     *   )
     * )
     */
    public function checkCreditCard(){}

}