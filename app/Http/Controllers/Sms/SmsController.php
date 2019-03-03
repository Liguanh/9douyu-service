<?php
/**
 * 短信模块
 * User: bihua
 * Date: 16/4/22
 * Time: 17:15
 */
namespace App\Http\Controllers\Sms;

use App\Http\Controllers\Controller;
use App\Http\Logics\Logic;
use App\Http\Logics\Sms\SmsLogic;
use Illuminate\Http\Request;

class SmsController extends Controller
{

    private $logic = null;

    public function __construct(Request $request){

        parent::__construct($request);

        $phone = $request->input("phone","");       //手机号
        $msg   = $request->input("msg","");         //发送的内容

        $this->logic = new SmsLogic($phone,$msg);

        if( env('APP_ENV') != 'production'  ){

            \Log::info('testSendNoticeInfo', ['phone' => $phone, 'msg' => $msg]);

            return self::returnJson(Logic::callSuccess());

        }
    }
    /**
     * @return array
     * 发送通知短信
     */
    public function sendNotice(){
        //@todo 上线注释
        //return self::returnJson(Logic::callSuccess([]));

        $result = $this->logic->send('Notice');

        return self::returnJson($result);
    }

    //营销短信
    public function sendMarket(){

        //@todo 上线注释
        //return self::returnJson(Logic::callSuccess([]));

        $result = $this->logic->send('Market');

        return self::returnJson($result);
    }

    //验证码短信
    public function sendVerify(){

        //@todo 上线注释
        //return self::returnJson(Logic::callSuccess([]));

        $result = $this->logic->send('Verify');

        return self::returnJson($result);
    }

    //语音短信验证
    public function sendVoice(){

        //@todo 上线注释
        return self::returnJson(Logic::callSuccess([]));

        $result = $this->logic->sendVoice();

        return self::returnJson($result);
    }

    /**
     * @desc 获取短信黑名单词组
     * @return string
     */
    public function getBlackList()
    {
        $blackList = $this->logic->getBlackList();

        return self::returnJson( $blackList );
    }

}
