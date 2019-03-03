<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/11/30
 * Time: 下午5:25
 */

namespace App\Http\Logics\Contract;

use App\Http\Logics\Logic;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Contract\DriverModel;
use Illuminate\Http\Request;
use Log;

class ContractLogic extends Logic
{

    /**
     * @param $params
     * @return array
     * 合同入口logic
     */
    public function index(Request $request ){

        try{

            //支付服务是否支持访接口
            $method = $request->input('method','');
            ValidateModel::isContractMethod($method);

            //是否支持该支付通道
            $driver = $request->input('driver','');
            ValidateModel::isContractDriver($driver);

            //获取对应通道的model
            $model = DriverModel::getInstance($driver);
            //所有传递的参数
            $params = $request->input();
            //获取客户端IP地址
            $params['user_ip'] = $request->ip();
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