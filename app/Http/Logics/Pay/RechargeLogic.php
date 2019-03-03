<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/10
 * Time: 12:07
 */
namespace App\Http\Logics\Pay;
use App\Http\Logics\Logic;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Pay\DriverModel;
use Log;

class RechargeLogic extends Logic{

    /**
     * @param $params
     * @return array
     * 支付入口logic
     */
    public function index($request){

        try{

            //支付服务是否支持访接口
            $method = $request->input('method','');
            ValidateModel::isMethod($method);

            //是否支持该支付通道
            $driver = $request->input('driver','');
            ValidateModel::isPayDriver($driver);

            //获取对应通道的model
            $model = DriverModel::getInstance($driver);

            //所有传递的参数
            $params = $request->input();
            //获取客户端IP地址
            $params['user_ip'] = $request->ip();

            Log::info(__METHOD__.' -支付请求信息- '.var_export($params,true));

            //调用支付通道具体的操作方法
            $result = $model->$method($params);
        }catch (\Exception $e){

            //记录错误日志
            $log = $request->input();
            $log['errorMsg']    = $e->getMessage();
            $log['errorCode']   = $e->getCode();

            Log::info(__METHOD__.'Error'.var_export($log,true));

            return self::callError($e->getMessage());
        }

        return self::callSuccess($result);
    }
}
