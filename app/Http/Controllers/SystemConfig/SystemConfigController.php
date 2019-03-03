<?php
/**
 * Created by PhpStorm.
 * User: lgh189491
 * Date: 16/10/28
 * Time: 09:43
 * Desc: 系统配置模版
 */

namespace App\Http\Controllers\SystemConfig;

use App\Http\Controllers\Controller;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use Illuminate\Http\Request;

class SystemConfigController extends Controller
{
    public $logic = null;


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->logic = new SystemConfigLogic();

    }

    /**
     * @return array
     * @desc 获取系统配置的列表
     */
    public function getList(){

       $list = $this->logic->getList();

       self::returnJson($list);
    }

    /**
     * @param Request $request
     * @desc 通过id获取服务配置信息
     */
    public function getInfoById(Request $request){

        $id = $request->input('id');

        $info = $this->logic->getSystemConfigById($id);

        self::returnJson($info);
    }

    /**
     * @param Request $request
     * @desc 添加服务配置信息
     */
    public function addSystemConfigInfo(Request $request){

        $data = $request->all();

        $addInfo = $this->logic->addSysConfigInfo($data);

        self::returnJson($addInfo);
    }

    /**
     * @param Request $request
     * @desc 更改配置
     */
    public function updateSystemConfigInfo(Request $request){

        $data = $request->all();

        $id = isset($data['id']) ? $data['id'] : 0;

        $editInfo = $this->logic->updateInfo($id, $data);

        self::returnJson($editInfo);
    }

}