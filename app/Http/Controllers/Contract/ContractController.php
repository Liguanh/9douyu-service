<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/11/30
 * Time: 下午5:21
 */

namespace App\Http\Controllers\Contract;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Logics\Contract\ContractLogic;

class ContractController extends Controller
{

    /**
     * @param Request $request
     * @return array
     * 合同相关服务入口
     */
    public function index(Request $request){


        $logic = new ContractLogic();

        $result = $logic->index($request);
        return self::returnJson($result);
    }

}