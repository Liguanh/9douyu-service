<?php
/**
 * 微信推送模块
 * User: bihua
 * Date: 16/5/11
 * Time: 18:22
 */
namespace App\Http\Logics\Push;

use Log;
use App\Http\Logics\Logic;
use App\Http\Models\Push\WeiXinModel;
use App\Http\Models\Common\ValidateModel;

class WeiXinLogic extends Logic
{

    private $model;

    public function __construct(){

        $this->model = new WeiXinModel();
    }

    private function validate($dataArr){
        //验证配置参数是否为空
        ValidateModel::isOptions($dataArr['options']);
        //验证消息参数是否为空
        ValidateModel::isData($dataArr['data']);
        //验证接收者信息是否为空
        ValidateModel::isTouser($dataArr['touser']);
        //验证模板ID是否为空
        ValidateModel::isTemplateId($dataArr['templateId']);
    }

    public function sendTmpMsg($jsonData){

        //将JSON数据转化为数组
        $jsonData = (array)json_decode($jsonData);
        //验证数据
        $this->validate($jsonData);

        $log = [
            "options"   => $jsonData['options'],
            "data"      => $jsonData['data'],
            "touser"    => $jsonData['touser'],
            "templateId"=> $jsonData['templateId'],
            "url"       => $jsonData['url']
        ];

        $options = (array)$jsonData['options'];
        $data    = (array)$jsonData['data'];

        try{

            $this->model->sendTmpMsg($options,$data,$jsonData['touser'],$jsonData['templateId'],$jsonData['url']);
        }catch (\Exception $e){

            $log["code"] = $e->getCode();
            $log["msg"]  = $e->getMessage();

            Log::error(__METHOD__."Error",$log);
            return self::callError($e->getMessage());
        }
        return self::callSuccess();
    }
}