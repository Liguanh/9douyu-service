<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/12/1
 * Time: 上午11:06
 */

namespace App\Http\Models\Contract;

class DriverModel{

    //支付相关配置
    public static $config = [

        'EBQ'             => EBQModel::class,         //易宝全合同
        'JZQ'             => JZQModel::class,         //易宝全的君子签章
    ];


    /**
     * @param $driver
     * @return mixed
     * 获取对应的支付实例
     */
    public static function getInstance($driver){

        return new self::$config[$driver];
    }
}