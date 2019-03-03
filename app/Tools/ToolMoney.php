<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/14
 * Time: 上午11:53
 * Desc: 公共工具类
 */

namespace App\Tools;

class ToolMoney
{

    /**
     * @param $cash
     * @return mixed
     * @desc 格式化金额，金额 * 100
     */
    public static function formatDbCashAdd($cash)
    {

        return $cash * 100;

    }

    /**
     * @param $cash
     * @return float
     * @desc 格式化金额，金额 / 100
     */
    public static function formatDbCashDelete($cash)
    {

        return $cash / 100;

    }



}