<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/15
 * Time: 下午4:37
 * Desc: Model 操作提示信息
 */

namespace App\Lang;

class LangModel
{

    const

        SUCCESS_COMMON                              = '操作成功',

        /******************************************这里是分割线************************************************/
        //Common
        ERROR_COMMON                                = '信息不正确',


        //Validate
        ERROR_PARAMS_IS_EMPTY                       = '参数不能为空',
        ERROR_INVALID_PARTNER_ID                    = '未传递商户ID或格式错误',
        ERROR_INVALID_SIGN                          = '未传递签名或格式错误',
        ERROR_INVALID_METHOD                        = '支付服务不支持该接口',
        ERROR_INVALID_DRIVER                        = '无效的支付通道',
        ERROR_INVALID_NAME                          = '无效的姓名',
        ERROR_INVALID_BANK_CARD                     = '无效的银行卡号',
        ERROR_INVALID_ID_CARD                       = '无效的身份证号',
        ERROR_INVALID_CONTRACT_METHOD               = '合同服务不支持该接口',
        ERROR_INVALID_CONTRACT_DRIVER               = '无效的合同通道',




        //SMS
        ERROR_INVALID_PHONE                         = '未传递手机号或格式错误',
        ERROR_INVALID_VOICE_MSG                     = '无效的语音验证码内容',
        ERROR_INVALID_SMS_TYPE                      = '无效的短信类型',
        ERROR_INVALID_SMS_MSG                       = '短信内容为空或含敏感字',
        ERROR_INVALID_SMS_KEYWORD                   = '未包含标识关键字',
        ERROR_INVALID_PACK_PRICE                    = '金额不正确',

        //EMAIL
        ERROR_INVALID_EMAIL                         = '邮箱不能为空',
        ERROR_INVALID_EMAIL_TITLE                   = '主题不能为空',
        ERROR_INVALID_EMAIL_SUBJECT                 = '内容不能为空',

        //WEIXIN
        ERROR_INVALID_OPTIONS                       = '配置参数不能为空',
        ERROR_INVALID_DATA                          = '模板消息参数不能为空',
        ERROR_INVALID_TEMPLATE                      = '模板ID不能为空',
        ERROR_INVALID_TOUSER                        = '接收人信息不能为空',

        /******************************************验证结束***************************************************/

        //SMS
        ERROR_SEND_SMS_FAILED                       = '发送短信失败',
        ERROR_SEND_VOICE_SMS_FAIL                   = '发送语音短信失败',

        //WEIXIN
        ERROR_SEND_WEIXIN_TMPLATE_MSG_FAIL          = '发送微信模块消息失败',

        //EMAIL
        ERROR_SEND_EMAIL_FAILED                     = '发送邮件失败'


    ;


    /**
     * @param $name
     * @return string
     */
    public static function getLang($name)
    {

        $className = __CLASS__;

        $lang = defined("$className::$name") ? constant("$className::$name") : $name;

        return $lang;

    }

}