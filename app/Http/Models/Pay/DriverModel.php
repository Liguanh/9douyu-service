<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/10
 * Time: 14:25
 */

namespace App\Http\Models\Pay;

//网银
use App\Http\Models\Pay\Online\JdModel as JdOnlineModel;
use App\Http\Models\Pay\Online\ReaModel as ReaOnlineModel;
use App\Http\Models\Pay\Online\HnaModel as HnaOnlineModel;
use App\Http\Models\Pay\Online\SumaModel as SumaOnlineModel;

//认证支付
use App\Http\Models\Pay\Auth\LLModel as LLAuthModel;
use App\Http\Models\Pay\Auth\YeeModel as YeeAuthModel;
use App\Http\Models\Pay\Auth\BFModel as BFAuthModel;
use App\Http\Models\Pay\Auth\UCFModel as UCFAuthModel;
use App\Http\Models\Pay\Auth\SumaModel  as SumaAuthModel;

//代扣
use App\Http\Models\Pay\Withholding\UmpModel as UmpWithholdingModel;
use App\Http\Models\Pay\Withholding\QdbModel as QdbWithholdingModel;
use App\Http\Models\Pay\Withholding\BestModel as BestWithholdingModel;
use App\Http\Models\Pay\Withholding\ReaModel as ReaWithholdingModel;


class DriverModel{

    //支付相关配置
    public static $config = [

        'JdOnline'             => JdOnlineModel::class,         //京东网银
        'ReaOnline'            => ReaOnlineModel::class,        //融宝网银
        'HnaOnline'            => HnaOnlineModel::class,        //新生网银
        'SumaOnline'           => SumaOnlineModel::class,       //丰付网银
        'LLAuth'               => LLAuthModel::class,           //连连认证
        'BFAuth'               => BFAuthModel::class,           //宝付认证
        'UCFAuth'              => UCFAuthModel::class,
        'SumaAuth'             => SumaAuthModel::class,         //丰付支付
        'YeeAuth'              => YeeAuthModel::class,          //易宝认证
        'QdbWithholding'       => QdbWithholdingModel::class,      //钱袋宝代扣
        'UmpWithholding'       => UmpWithholdingModel::class,      //联动优势代扣
        'BestWithholding'      => BestWithholdingModel::class,     //翼支付代扣
        'ReaWithholding'       => ReaWithholdingModel::class       //融宝代扣

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