<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/4
 * Time: 17:13
 */

namespace App\Http\Controllers\Swagger\Sms;

class Sms{
    
    /**
     * @SWG\Post(
     *   path="/notice",
     *   tags={"Sms"},
     *   summary="发送通知短信",
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
     *     @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="msg",
     *      in="formData",
     *      description="短信内容",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="发送通知类短信。",
     *   )
     * )
     */
    public function notice(){}

    /**
     * @SWG\Post(
     *   path="/verify",
     *   tags={"Sms"},
     *   summary="发送验证码短信",
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
     *     @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="msg",
     *      in="formData",
     *      description="短信内容",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="发送验证码短信。",
     *   )
     * )
     */
    public function verify(){}

    /**
     * @SWG\Post(
     *   path="/market",
     *   tags={"Sms"},
     *   summary="发送营销类短信",
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
     *     @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="msg",
     *      in="formData",
     *      description="短信内容",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="发送营销短信。",
     *   )
     * )
     */
    public function market(){}

    
    public function voice(){}

    /**
     * @SWG\Post(
     *   path="/send/flow",
     *   tags={"Sms"},
     *   summary="发送营销类短信",
     *    @SWG\Parameter(
     *      name="secret_sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *     default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *     @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="15701288783"
     *   ),
     *   @SWG\Parameter(
     *      name="packPrice",
     *      in="formData",
     *      description="流量值，10,30,50,100",
     *      required=true,
     *      type="string",
     *      default="10"
     *   ),
     *   @SWG\Parameter(
     *      name="orderId",
     *      in="formData",
     *      description="订单号",
     *      required=true,
     *      type="string",
     *      default="2017091801"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="发送营销短信。",
     *   )
     * )
     */
    public function sendFlow(){}


    /**
     * @SWG\Post(
     *   path="/send/calls",
     *   tags={"Sms"},
     *   summary="发送营销类短信",
     *    @SWG\Parameter(
     *      name="secret_sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *     default="b926ab99d913f7bacbb3e526ebf75c98"
     *   ),
     *     @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="15701288783"
     *   ),
     *   @SWG\Parameter(
     *      name="packPrice",
     *      in="formData",
     *      description="流量值，10,30,50,100",
     *      required=true,
     *      type="string",
     *      default="10"
     *   ),
     *   @SWG\Parameter(
     *      name="orderId",
     *      in="formData",
     *      description="订单号",
     *      required=true,
     *      type="string",
     *      default="2017091801"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="发送营销短信。",
     *   )
     * )
     */
    public function sendCalls(){}
}