<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/15
 * Time: 下午5:30
 * Desc: 公用model
 */

namespace App\Http\Models;

use App\Http\Models\Common\ExceptionCodeModel;

class Model
{


    public static $defaultNameSpace = ExceptionCodeModel::EXP_MODEL_BASE;

    public static $codeArr = [];

    protected static function getFinalCode($errorText='')
    {

        $codeExt = isset(static::$codeArr[$errorText]) ? static::$codeArr[$errorText] : 0;

        if( isset(static::$expNameSpace)  ){

            return static::$expNameSpace + $codeExt;

        }else{

            return self::$defaultNameSpace;

        }

    }



}
