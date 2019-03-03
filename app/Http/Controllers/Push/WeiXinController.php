<?php
/**
 * 微信模块
 * User: bihua
 * Date: 16/5/9
 * Time: 11:51
 */
namespace App\Http\Controllers\Push;

use App\Http\Controllers\Controller;
use App\Http\Logics\Push\WeiXinLogic;
use Illuminate\Http\Request;

class WeiXinController extends Controller
{
    function sendTemplateMessage(Request $request)
    {
        $jsonData   = $request->input("data");

        $logic = new WeiXinLogic();
        $res   = $logic->sendTmpMsg($jsonData);
        return $this->returnJson($res);

    }
}