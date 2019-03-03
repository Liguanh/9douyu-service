<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/4
 * Time: 17:13
 */

namespace App\Http\Controllers\Swagger\Email;

class Email{

    /**
     * @SWG\Post(
     *   path="/sendMail",
     *   tags={"Email"},
     *   summary="发送普通文本内容邮件",
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
     *      name="email",
     *      in="formData",
     *      description="收件人,多个邮箱用,分隔",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="subject",
     *      in="formData",
     *      description="邮件标题",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="body",
     *      in="formData",
     *      description="邮件内容",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="attachement",
     *      in="formData",
     *      description="邮件附件",
     *      required=false,
     *      type="file",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="发送文字邮件。",
     *   )
     * )
     */
    public function sendMail(){}


    /**
     * @SWG\Post(
     *   path="/sendMailHtml",
     *   tags={"Email"},
     *   summary="发送HTML内容邮件",
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
     *      name="email",
     *      in="formData",
     *      description="收件人,多个邮箱用,分隔",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="subject",
     *      in="formData",
     *      description="邮件标题",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="body",
     *      in="formData",
     *      description="邮件内容",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="attachement",
     *      in="formData",
     *      description="邮件附件",
     *      required=false,
     *      type="file",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="发送HTML内容邮件。",
     *   )
     * )
     */
    public function sendMailHtml(){}

}