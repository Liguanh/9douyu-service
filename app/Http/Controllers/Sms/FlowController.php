<?php
/**
 * Created by PhpStorm.
 * User: scofie
 * Date: 2017/9/15
 * Time: PM4:36
 */

namespace App\Http\Controllers\Sms;


use App\Http\Controllers\Controller;
use App\Http\Logics\Logic;
use App\Http\Logics\Sms\FLowLogic;
use Illuminate\Http\Request;

class FlowController extends Controller
{

    private $logic = null;

    public function __construct(Request $request){

        parent::__construct($request);

        $phone      = $request->input("phone","");       //手机号
        $packPrice  = $request->input("packPrice","");         //充值的价格
        $orderId    = $request->input("orderId","");         //订单号

        $this->logic = new FLowLogic($phone, $packPrice, $orderId);

        if( env('APP_ENV') != 'production'  ){

            \Log::info('testSendFlowInfo', ['phone' => $phone, 'packPrice' => $packPrice,'orderId' => $orderId,]);

            return self::returnJson(Logic::callSuccess());

        }
    }

    /**
     * @return array
     * @desc 流量充值
     */
    public function sendFlow()
    {
        //@todo 上线注
        $result = $this->logic->sendFlow('Flow');

        return self::returnJson($result);
    }

    /**
     * @return array
     * @desc 话费充值
     */
    public function sendCalls()
    {
        //@todo 上线注释

        $result = $this->logic->sendCalls('Calls');

        return self::returnJson($result);
    }
}