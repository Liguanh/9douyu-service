<?php
/**
 * 微信消息类
 * User: bihua
 * Date: 16/5/9
 * Time: 11:32
 */
namespace App\Services\WeiXin;

use Log;
use App\Services\Services;
use Ares333\CurlMulti;

class WXMsg extends Services
{
    private $token              = "";

    private $appid              = "";

    private $appsecret          = "";

    //请求API前缀
    const  API_URL_FREFIX       = 'https://api.weixin.qq.com/cgi-bin/';

    //发送模块消息链接
    const  TMPLATE_MESSAGE_URL  = 'message/template/send?access_token=';

    public  function __construct( $options = array() )
    {
        $this->token        = isset($options["token"]) ? $options["token"] : "";
        $this->appid        = isset($options["appId"]) ? $options["appId"] : "";
        $this->appsecret    = isset($options["appSecret"]) ? $options["appSecret"] : "";
    }

    /**
     * 向用户推送模板消息
     * @param $data = array(
     *                  'first'=>array('value'=>'您好，您已成功消费。', 'color'=>'#0A0A0A')
     *                  'keynote1'=>array('value'=>'巧克力', 'color'=>'#CCCCCC')
     *                  'keynote2'=>array('value'=>'39.8元', 'color'=>'#CCCCCC')
     *                  'keynote3'=>array('value'=>'2014年9月16日', 'color'=>'#CCCCCC')
     *                  'keynote3'=>array('value'=>'欢迎再次购买。', 'color'=>'#173177')
     * );
     * @param $touser 接收方的OpenId。
     * @param $templateId 模板Id。在公众平台线上模板库中选用模板获得ID
     * @param $url URL
     * @param string $topcolor 顶部颜色， 可以为空。默认是红色
     * @return array("errcode"=>0, "errmsg"=>"ok", "msgid"=>200228332} "errcode"是0则表示没有出错
     *
     * 注意：推送后用户到底是否成功接受，微信会向公众号推送一个消息。
     */
    public function sendTemplateMessage($data, $touser, $templateId, $url, $topcolor = '#0099EE')
    {
        $template = array();
        $template["touser"]      = $touser;
        $template["template_id"] = $templateId;
        $template["url"]         = $url;
        $template["data"]        = $data;
        $result = parent::curlOpen(self::API_URL_FREFIX.self::TMPLATE_MESSAGE_URL.$this->token, json_encode($template));
        Log::info("发送微信模板消息返回结果：".$result.",touser:".$touser);
        if($result){
            $jsonArr = json_decode($result, true);
            if(!$jsonArr || (isset($jsonArr["errcode"]) && $jsonArr["errcode"] > 0)){
                Log::info("Weixin send template message is fail,touser is ".$touser.",time is ".date("Y-m-d H:i:s")."; \n");
            }else{
                return $jsonArr;
            }
        }
        return false;
    }
}
