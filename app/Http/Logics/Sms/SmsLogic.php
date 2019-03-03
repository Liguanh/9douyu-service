<?php
/**
 * Created by PhpStorm.
 * User: bihua
 * Date: 16/5/9
 * Time: 16:15
 */
namespace App\Http\Logics\Sms;

use App\Http\Logics\Logic;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Sms\SmsModel;
use App\Services\Sms\EmaySms;
use Log;

class SmsLogic extends Logic{

    private $phone      = '';
    private $content    = '';
    private $log        = [];

    private $model      = null;

    public function __construct($phone,$content){

        $this->log      = [
            'phone'     => $phone,
            'content'   => $content
        ];

        $this->phone    = $phone;
        $this->content  = $content;

        $this->model    = new SmsModel();

    }

    private function validate($type){

        //验证手机号是否为空
        ValidateModel::isPhone($this->phone);
        //验证是否是合法的类型
        ValidateModel::isType($type);

    }

    /**
     * @param string $type
     * @return array
     * 发送短信统一入口logic
     */
    public function send($type = ''){

        try{
            //数据验证
            $this->validate($type);
            ValidateModel::isMsg($this->content);

            $driver = $this->model->getDriver($type);
            $this->model->sendCode($driver,$type,$this->phone,$this->content);

        }catch (\Exception $e){

            $this->saveLog($e);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }


    /**
     * @param string $type
     * @return array
     * 发送语音短信
     */
    public function sendVoice($type = 'Voice'){

        try{
            //数据验证
            $this->validate($type);
            ValidateModel::isVoiceMsg($this->content);

            $this->model->sendVoice($this->phone,$this->content);

        }catch (\Exception $e){

           $this->saveLog($e);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
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

    /**
     * @desc 获取短信内容检测黑名单数据
     * @return array
     */
    public function getBlackList( )
    {

        $emaySms =  new EmaySms();

        $blackList = $emaySms->getBlackList();

        if( !empty( $blackList ) )
        {
            return self::callSuccess( $blackList );
        }

        return self::callError( '短信内容黑名单数据为空' );

    }

}
