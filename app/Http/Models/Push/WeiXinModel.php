<?php
/**
 * 微信推送模块
 * User: bihua
 * Date: 16/5/11
 * Time: 18:25
 */
namespace App\Http\Models\Push;

use App\Http\Models\Model;
use App\Services\WeiXin\WXMsg;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;
class WeiXinModel extends Model
{
    public static $codeArr = [
        'sendFail'  => 1
    ];

    public static $expNameScape = ExceptionCodeModel::EXP_MODEL_WEIXIN;

    /**
     * @param array $options
     * @param array $data
     * @param string $touser
     * @param string $templateId
     * @param string $url
     * @throws \Exception
     * 发送模板消息
     */
    public function sendTmpMsg($options = array(),$data,$touser,$templateId = '',$url = ''){

        $wxMsg = new WXMsg($options);

        $res = $wxMsg->sendTemplateMessage($data,$touser,$templateId,$url);
        if(!$res){
            throw new \Exception(LangModel::ERROR_SEND_WEIXIN_TMPLATE_MSG_FAIL,$this->getFinalCode("sendFail"));

        }
    }
}