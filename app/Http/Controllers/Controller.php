<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Logics\Auth\SecurityAuthLogic;
use App\Http\Logics\Logic;
use Log;

class Controller extends BaseController
{

    private static $env         = '';
    private static $partnerId   = '';
    private static $secretKey   = '';
    private static $request     = '';
    private static $sign        = '';


    public function __construct(Request $request){

        self::$request = $request;


        /*
        //获取环境
        self::$env = App::environment();


        //开发环境url添加参数&getSign=1获取sign
        if(('local' === self::$env) && $request->input('getSign')) {
            return $this->getSign($request);
        }

        //开发环境绕过sign但保留用户获取功能，未指定用户则使用默认
        if('local' === self::$env) {

            self::$partnerId = $request->input('partner_id','');

            //测试商户号
            if('' === self::$partnerId){

                self::$partnerId = '110000901001';
            }
            //根据商户号获取相应的信息
            $info = SecurityAuthLogic::getPartnerInfo(self::$partnerId);

            self::$secretKey = $info['secret_key'];

        }

        //线上环境验证签名
        if( self::$env != 'local' && self::$env !='testing'){

            $return = Logic::callError('验证签名错误');

            self::$partnerId = $request->input('partner_id'); //商户ID

            self::$sign     = $request->input('secret_sign');

            $data           = $request->input();

            unset($data['secret_sign']);
            
            $res = SecurityAuthLogic::checkSignByPartnerId(self::$partnerId, self::$sign, $data);

            if( $res ){
                self::$secretKey    = $res['secret_key'];
            }else{

                $log = $request->input();

                Log::Error('CheckSecurityError', $log);

                self::returnJson($return);

            }

        }
        */

    }


    /**
     * 返回json
     * @param array $data
     * @return array
     */
    protected static function returnJson($data = []){

        //返回信息生成相应的sign
       // $data['sign'] = SecurityAuthLogic::createSign(self::$secretKey,$data);
        //记录请求及响应日志,方便问题的定位
        $log           = self::$request->input(); //请求参数列表
        $log['data']   = $data;                   //应答参数列表
        Log::info("HttpResult",$log);

        if (!headers_sent()) {
            header(sprintf('%s: %s', 'Content-Type', 'application/json'));
        }

        exit(json_encode($data));
    }


    /**
     * @param $request
     * 开发环境获取sign
     */
    public function getSign(Request $request){

        if('local' === self::$env) {

            $info = SecurityAuthLogic::getPartnerInfo($request->input('partner_id'));

            if(empty($info['partner_id'])) {

                self::returnJson(['sign' => false]);
            }

            $data = $request->input();

            $sign = SecurityAuthLogic::createSign(self::$secretKey, $data);

            self::returnJson(['post_sign' => $sign]);
        }

        return true;
    }

}
