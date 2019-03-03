<?php
/**
 * User: zhangshuang
 * Date: 16/4/28
 * Time: 17:12
 */
namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Controller;
use App\Http\Logics\Pay\RechargeLogic;
use Illuminate\Http\Request;

class RechargeController extends Controller{

    /**
     * @param Request $request
     * @return array
     * 支付相关服务入口
     */
    public function index(Request $request){

        $logic = new RechargeLogic();
        
        $result = $logic->index($request);
        return self::returnJson($result);
    }


}