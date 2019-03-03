<?php
/**
 * Created by PhpStorm.
 * User: scofie
 * Date: 2017/9/15
 * Time: PM4:43
 */

namespace App\Http\Models\Sms;


use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Services\Sms\DhFlowSms;
use App\Services\Sms\Config;
use Laravel\Lumen\Application;

class FlowModel extends Model
{
    static protected $driver = [
        'DhFlow' => DhFlowSms::class //大汉流量银行通道
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_FLOW;

    const
        CHANEL_CONFIG = 'FLOW_CHANNEL_SERVICE',//手机充值流量的配置

        END = true;

    /**
     * @desc 充值入口
     */
    public static function doSendFlow( $driver, $type,$phone,$orderId,$packPrice)
    {
        $res = self::getSmsObj($driver,$type)->sendRequest($type ,['phone'=>$phone,'orderId' =>$orderId,'packPrice'=>$packPrice]);

        if($res["status"] === false){
            throw new \Exception($res['msg'], self::getFinalCode('sendPost'));
        }
        return $res ;
    }

    /**
     * @desc 充值入口
     */
    public static function doSendCalls( $driver, $type,$phone,$orderId,$packPrice)
    {
        $res = self::getSmsObj($driver,$type)->sendRequest($type, ['phone'=>$phone,'orderId' =>$orderId,'packPrice'=>$packPrice]);

        if($res["status"] === false){
            throw new \Exception($res['msg'], self::getFinalCode('sendPost'));
        }
        return $res ;
    }

    /**
     * 查询当前运营商账户
     * @param string $driver 驱动类名
     * @param string $type   通道类型
     * @return mixed
     */
    static public function getBalance($driver,$type)
    {
        return self::getSmsObj($driver,$type)->queryBalance();
    }

    /**
     * 获取运营商obj
     * @param string $driver 驱动类名
     * @param string $type   短信类型
     * @return mixed
     */
    static function getSmsObj($driver, $type)
    {
        $setting = self::getChannel( $driver, $type );
        //配置运营商，账号、密码
        $obj     = new self::$driver[$driver]($setting[0],$setting[1],$setting[2]);

        return $obj;
    }

    /**
     * 获取发送短信通道
     * @param string $type   短信类型
     * @return string 通道驱动名
     */
    public function getDriver($type = '')
    {
        $config     =   $this->getConfig();
        if(empty($config) ) {
            $config =   $this->defaultConfig();
        }
        return $config[strtoupper($type)];
    }

    protected static function getChannel( $driver, $type )
    {
        $config = Config::$flowChannel;

        return  $config[$driver][ strtoupper($type)];
    }
    /**
     * @return array
     * @desc 默认的通道
     */
    protected static function defaultConfig()
    {
        return ['FLOW' => 'DhFlow', 'CALLS' =>'DhFlow' ] ;
    }

    /**
     * @return bool|mixed
     * @desc 通道的配置文件
     */
    protected static function getConfig()
    {
        return SystemConfigLogic::getConfigByKey(self::CHANEL_CONFIG) ;
    }
}