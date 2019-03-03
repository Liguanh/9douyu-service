<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/4
 * Time: 11:16
 */

namespace App\Http\Controllers\Swagger\Pay;

class YeeAuth{

    /**
     * @SWG\Post(
     *   path="/recharge/index?random=YeeAuth_encrypt",
     *   tags={"YeeAuth"},
     *   summary="易宝认证支付加密",
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
     *      description="支付渠道(易宝认证支付)",
     *      required=true,
     *      type="string",
     *      default="YeeAuth",
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
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *      default="82692",
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
     *     description="易宝认证支付前数据加密。",
     *   )
     * )
     */
    public function encrypt(){

    }


    /**
     * @SWG\Post(
     *   path="/recharge/index?random=YeeAuth_decrypt",
     *   tags={"YeeAuth"},
     *   summary="易宝认证支付回调数据解密",
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
     *      description="支付渠道(易宝认证支付)",
     *      required=true,
     *      type="string",
     *      default="YeeAuth",
     *   ),
     *     @SWG\Parameter(
     *      name="method",
     *      in="formData",
     *      description="接口名称(解密)",
     *      required=true,
     *      type="string",
     *     default="decrypt",
     *   ),
     *  @SWG\Parameter(
     *      name="data",
     *      in="formData",
     *      description="回调返回的数据",
     *      required=true,
     *      type="string",
     *     default="cTWHGQGQy0SJ4Coa2/Xzhbv6ypbhs8gjxVaavnfYUZQA7xwRNwoxKRSnGtZR3a32g80Qw5SyrKaGYQedC+MCqpoJph7qEOBf+h8uATEP+fcscNB0DPZSQ6SsxqaZAgwIBglo837IE7oIGXC7pWJWaeHA0zT4trheH88piQX1edqLUOcoxk5EI2HfPIJpuhEhsVysDkknlTZ/6q9pcasgwA8bE3a2X/YE6+LPuaCeVMzjkJB21JCrwQksoR51j7zVNh8lZSzbxVK111Q61PPt/cyxoqpIodxSjTTqnvQBSY/rzuRGIwBu/QaGurtpfwWdEzbu4+oFy23kQjRoCF3UZR67k3oTOUzacdxPVAA/diraP4VKfUprDsVEjpPSuDbXsYoNJt+cBbdnkz56pDGb06dDFB8N/FUTRrMj0dAD+NEd6RPk67BHk0bTf3+F+1jU9e2hqdtAPYI4I7XstGm6MVQg4auOcB/MkR/zPG1lLUj1LG5DgJTGsWNQuUNsTXLK",
     *   ),
     *   @SWG\Parameter(
     *      name="encryptkey",
     *      in="formData",
     *      description="数据的加密KEY",
     *      required=true,
     *      type="string",
     *     default="DyNF2DYwFgpHAxvUgL4LM4pa73FMoly7gVhbNHh22wDfmzXiragq50SEwCGPkD8EPrAOLq8qF0hGNMVvhhFkq/fCYLjjjnv1KDgJlK4tQnwBjdRG0e1gh2LJTR/UFziK/VVrR2zBMDAvJoIUuFHuLgc9gJTIzhuhdqlSU+3mdBM",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="易宝认证支付完成后的回调数据解密。",
     *   )
     * )
     */
    public function decrypt(){}




    /**
     * @SWG\Post(
     *   path="/recharge/index?random=YeeAuth_search",
     *   tags={"YeeAuth"},
     *   summary="易宝认证支付主动查单",
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
     *      description="支付渠道(易宝认证支付)",
     *      required=true,
     *      type="string",
     *      default="YeeAuth",
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
     *      default="JDY_201606041343568593",
     *   ),

     *   @SWG\Response(
     *     response=200,
     *     description="易宝认证支付主动查单。",
     *   )
     * )
     */
    public function search(){}





}