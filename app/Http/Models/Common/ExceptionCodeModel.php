<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/13
 * Time: 下午1:38
 * Desc: 错误码
 */

namespace App\Http\Models\Common;

class ExceptionCodeModel
{

    /**
     * @todo 逐步完善model错误码，定义2——4——3规则
     */

    const

        //错误码切分：2_4_3

        //BASE 100
        EXP_MODEL_BASE                                      = 101000100,


        //VALIDATE
        EXP_MODEL_COMMON_VALIDATE                           = 101000200,

        //SMS
        EXP_MODEL_SMS                                       = 101000300,

        //EMAIL
        EXP_MODEL_EMAIL                                     = 101000400,

        //WEIXIN
        EXP_MODEL_WEIXIN                                    = 101000500,

        //移动流量
        EXP_MODEL_FLOW                                      = 101000600,
        //最后一个，新增请在这个上面添加
        EXP_LAST_ITEM                                       = 100000000;



}
