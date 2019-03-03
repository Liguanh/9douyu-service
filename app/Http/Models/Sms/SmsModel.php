<?php
/**
 * 短信模块
 * User: bihua
 * Date: 16/4/22
 * Time: 18:20
 */
namespace App\Http\Models\Sms;

use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Models\Mail\EmailModel;
use App\Services\Sms\ClSms;
use App\Services\Sms\MdSms;
use App\Services\Sms\MiSms;
use App\Services\Sms\StongSms;
use App\Services\Sms\WdSms;
use Laravel\Lumen\Application;
use Log;
use Cache;
use App\Http\Models\Model;
use App\Services\Sms\JzSms;
use App\Services\Sms\EmaySms;
use App\Services\Sms\Config;
use App\Services\Sms\LsmVoice;
use App\Lang\LangModel;
use App\Http\Models\Common\ExceptionCodeModel;

class SmsModel extends Model
{
    static protected $driver = [
        'JzSms'   => JzSms::class,
        'EmaySms' => EmaySms::class,   //market
        'MdSms' => MdSms::class,   //market
        'WdSms' => WdSms::class, //market
        'MiSms' => MiSms::class, //验证码类短信[美联]
        'ClSms' => ClSms::class, //创蓝营销短信接口
        'StongSms' => StongSms::class //大汉三通营销短信
    ];

    public static $codeArr = [
        'sendCode'                          => 1,
        'sendVoiceCode'                     => 2,


    ];

    const
        CHANEL_CONFIG = 'SMS_CHANNEL_SERVICE',//短信通道配置

        VERIFY_CHANGE = 'VERIFY_CHANNEL_CHANGE_KEY', //验证码类的短信自动切换通道的数字key

        END = true;

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_SMS;

    /**
     * 发送短信服务
     * @param $driver 通道驱动
     * @param $type   短信类型  Market |Notice |Verify
     * @param $phones 手机号
     * @param $msg    短信内容
     * @return array
     */
    public function sendCode($driver, $type, $phones, $msg)
    {

            $res = self::getSmsObj($driver,$type)->sendCode($phones,$msg);

            //将返回结果记入日志
            //Log::info("手机号:".$phones.",内容:".$msg.",返回结果:", $res);

            //短信平台账户余额不足，发送警告邮件
            if( !empty($res["errorEmail"])){
                $app               = new Application();
                $app->configure("sms");
                $configList        = (array)$app["config"]["sms"];
                $email = new EmailModel();
                $email->sendContent($configList["WARNING_EMAIL"],"短信平台余额不足警告",$res["errorEmail"]);
            }

            if($res["status"] === false){
                throw new \Exception($res['errorMsg'], self::getFinalCode('sendCode'));
            }

    }

    /**
     * 查询当前运营商账户
     * @param string $driver 驱动类名
     * @param string $type   短信类型
     * @return mixed
     */
    static public function getBalance($driver,$type){
        $msg = self::getSmsObj($driver,$type)->queryBalance();
        return $msg;
    }

    /**
     * 获取运营商obj
     * @param string $driver 驱动类名
     * @param string $type   短信类型
     * @return mixed
     */
    static function getSmsObj($driver, $type){
        $type = strtoupper($type);
        $config = Config::$channel;
        $setting = $config[$driver][$type];
        //配置运营商，账号、密码
        $obj     = new self::$driver[$driver]($setting[0],$setting[1]);
        $obj->setBaseUrl($setting[2]);  //配置通道接口

        if( isset( $setting[3] ) )
        {
            $obj->setApiKey( $setting[3] );
        }
        return $obj;
    }

    /**
     * 获取发送短信通道
     * @param string $type   短信类型
     * @return string 通道驱动名
     */
    public function getDriver($type){
        $type = strtoupper($type);
        if(empty(SystemConfigLogic::getConfigByKey(self::CHANEL_CONFIG))){
            //旧的从文件中获取通道配置，不便于及时切换通道注释
            $app               = new Application();
            $app->configure("sms");
            $configList        = (array)$app["config"]["sms"];
        }else{
            $configList["DRIVER"] = SystemConfigLogic::getConfigByKey(self::CHANEL_CONFIG);
        }
        //如果是验证码通道特殊处理
        if( $type == 'VERIFY' )
            return $this->getVerifyChangeDriver( $configList['DRIVER'][$type] );

        return $configList["DRIVER"][$type];
    }

    /**
     * 发送语音验证短信
     * @param string $phone
     * @param string $msg
     * @return array
     */
    public function sendVoice($phone, $msg){
        $lsmVoice = new LsmVoice();
        $res = $lsmVoice->sendCode($phone,$msg);
        if($res["error"] < 0){
            throw new \Exception(LangModel::getLang('ERROR_SEND_VOICE_SMS_FAIL'), self::getFinalCode('sendVoiceCode'));
        }
    }

    /**
     * @desc 获取验证码短信通道type
     * @author linguanghui
     * @param $configList  配置列列表
     * @return string
     */
    public function getVerifyChangeDriver( $configList )
    {
        if( !stripos( $configList, ',' ))
            return $configList;

        $verifyChannel = explode( ',', $configList );

        $channelLenght = count( $verifyChannel );

        $key = $this->getVerifyChannelChangeNum() % $channelLenght;

        return $verifyChannel[$key];
    }

    /**
     * @desc 获取验证码渠道切换的数
     * @author linguanghui
     * @return int
     */
    public function getVerifyChannelChangeNum( )
    {
        $cacheNum = Cache::get( self::VERIFY_CHANGE );

        if( !empty( $cacheNum ) )
        {
            $num = $cacheNum;
        }else{
            $num = 0;
        }

        Cache::put( self::VERIFY_CHANGE, $num+1 , 10 );

        return $num;
    }
}
