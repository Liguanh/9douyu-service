<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/13
 * Time: 上午10:43
 */

namespace App\Http\Logics;

use Illuminate\Support\Facades\DB;

/**
 *
 * Class Logic
 * @package App\Http\Logics
 */
class Logic
{


    /**
     * @todo 不同的业务场景，可以定义不同code
     */
    const   CODE_SUCCESS    = 200,  //成功
            CODE_ERROR      = 500;  //失败

    /**
     * @param $data
     * @param $msg
     * @return array
     * @desc 统一返回成功数据
     */
    public static function callSuccess($data = null, $msg ='')
    {

        $data = [
            'status'    => true,
            'code'      => self::CODE_SUCCESS,
            'msg'       => $msg ? $msg : '成功',
            'data'      => empty($data) ? [] : $data
        ];

        return $data;

    }

    /**
     * @param string $msg
     * @param int $code
     * @param array $data
     * @return array
     * @desc 统一返回失败数据
     */
    public static function callError($msg = '', $code = self::CODE_ERROR, $data = [])
    {

        $data = [
            'status'    => false,
            'code'      => $code,
            'msg'       => $msg,
            'data'      => empty($data) ? [] : $data
        ];

        return $data;

    }

}
