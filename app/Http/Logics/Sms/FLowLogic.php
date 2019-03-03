<?php
/**
 * Created by PhpStorm.
 * User: scofie
 * Date: 2017/9/15
 * Time: PM4:40
 */

namespace App\Http\Logics\Sms;


use App\Http\Logics\Logic;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Sms\FlowModel;
use Log;

class FLowLogic extends Logic
{
    private $phone      = '';
    private $orderId    = '';
    private $packPrice  = '';
    private $log        = [];

    private $model      = null;

    public function __construct($phone, $packPrice, $orderId){

        $this->log      = [
            'phone'     =>  $phone,
            'packPrice' =>  $packPrice,
            'orderId'   =>  $orderId,
        ];

        $this->phone    = $phone;

        $this->orderId  = $orderId;

        $this->packPrice = $packPrice;

        $this->model    = new FlowModel();

    }

    private function validate($type){

        //验证手机号是否为空
        ValidateModel::isPhone($this->phone);
        //验证是否是合法的类型
        ValidateModel::isType($type);
        //验证参数的值是否存在
        ValidateModel::isPackPrice ($this->packPrice);
    }

    /**
     * @param string $type
     * @return array
     * 发送流量统一入口logic
     */
    public function sendFlow($type = ''){

        try{
            //数据验证
            $this->validate($type);

            $driver = $this->model->getDriver($type);

           $return  =   $this->model->doSendFlow(
                $driver,
                $type,
                $this->phone,
                $this->orderId,
                $this->packPrice
            );

        }catch (\Exception $e){

            $this->saveLog($e);

            return self::callError($e->getMessage());
        }

        return $return;
    }
    /**
     * @param string $type
     * @return array
     * 发送流量统一入口logic
     */
    public function sendCalls($type = ''){

        try{
            //数据验证
            $this->validate($type);

            $driver = $this->model->getDriver($type);

            $return  =   $this->model->doSendCalls(
                $driver,
                $type,
                $this->phone,
                $this->orderId,
                $this->packPrice
            );

        }catch (\Exception $e){

            $this->saveLog($e);

            return self::callError($e->getMessage());
        }

        return $return;
    }

    /**
     * @param \Exception $e
     * 保存日志
     */
    private function saveLog(\Exception $e){

        $this->log['code'] = $e->getCode();
        $this->log['msg'] = $e->getMessage();

        Log::error(__METHOD__.'Error',$this->log);
    }

}