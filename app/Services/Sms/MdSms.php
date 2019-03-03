<?php
/**
 * @desc 漫道短信通道Api
 * @author lgh
 * Date 16/09/12 Time 10:32
 */

namespace App\Services\Sms;

use Log;

class MdSms{

    //短信接口的序列号
    protected $apiSn;

    //短信接口密码
    protected $apiPassword;

    //短信接口地址
    protected $apiUrl      = 'sdk.entinfo.cn';
    //备用地址
    protected $apiUrlBak   = 'sdk2.entinfo.cn';

    //端口号
    protected $apiPort     = '8061';

    protected $baseUrl;
    protected static $statusTextMap   = [
        '-2'=> '帐号/密码不正确',
        '-4'=> '余额不足支持本次发送',
        '-5'=> '数据格式错误',
        '-6'=> '参数有误',
        '-7'=> '权限受限',
        '-8'=> '流量控制错误',
        '-9'=> '扩展码权限错误',
        '-10'=> '内容长度长',
        '-11'=> '内部数据库错误',
        '-12'=> '序列号状态错误',
        '-14'=> '服务器写文件失败',
        '-17'=> '没有权限',
        '-19'=> '禁止同时使用多个接口地址',
        '-20'=> '相同手机号，相同内容重复提交',
        '-22'=> 'Ip鉴权失败',
        '-23'=> '缓存无此序列号信息',
        '-601'=> '序列号为空，参数错误',
        '-602'=> '序列号格式错误，参数错误',
        '-603'=> '密码为空，参数错误',
        '-604'=> '手机号码为空，参数错误',
        '-605'=> '内容为空，参数错误',
        '-606'=> 'ext长度大于9，参数错误',
        '-607'=> '参数错误 扩展码非数字',
        '-608'=> '参数错误 定时时间非日期格式',
        '-609'=> 'rrid长度大于18,参数错误 ',
        '-610'=> '参数错误 rrid非数字',
        '-611'=> '参数错误 内容编码不符合规范',
        '-623'=> '手机个数与内容个数不匹配',
        '-624'=> '扩展个数与手机个数数',
        '-625'=> '定时时间个数与手机个数数不匹配',
        '-626'=> 'rrid个数与手机个数数不匹配',
    ];

    public function __construct($apiSn = false, $apiPassword = false) {

        if(!empty($apiSn)) {
            $this->apiSn = $apiSn;
        }
        if(!empty($apiPassword)) {
            $this->apiPassword = $apiPassword;
        }

    }

    /**
     * @desc 发送短信的通用接口
     * @param $phones
     * @param $msg
     * @return bool
     */
    public function sendCode($phones, $msg){
        $flag = 0;
        $errno = 0;
        $errstr = '';
        $params = '';

        if( empty($phones) ){
            return false;
        }
        if(is_array($phones)) $phones = implode(",", $phones);
        //md5加密序列号和密码
        $md5Pwd  = strtoupper(md5($this->apiSn.$this->apiPassword));
        //组装发送短信接口的数据
        $smsArgv = [
            'sn'  => $this->apiSn,  //序列号
            'pwd' => $md5Pwd,  //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
            'mobile'   => $phones, //手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
            'content'  => $msg, //短信内容
            'ext'      => '',
            'stime'    => '', //定时时间 格式为2011-6-29 11:09:21
            'msgfmt'=>'',
            'rrid'=>''
        ];

        foreach($smsArgv as $key=>$value){
            if($flag != 0){
                $params .= "&";
                $flag    = 1;
            }
            $params .= $key."=";
            $params .= urldecode($value);
            $flag = 1;
        }
        Log::info('Md-sendSms-Content:', $smsArgv);
        //创建socket连接
        $fp = fsockopen($this->apiUrl,$this->apiPort,$errno,$errstr,10) or exit($errstr."--->".$errno);

        $header = $this->formatPostHeader($params);

        fputs($fp,$header);
        //格式化返回的结果
        $this->formatSendResult($fp);
    }

    /**
     * @desc 构造post请求的header信息
     * @param $params
     * @return string
     */
    public function formatPostHeader($params){
        $length = strlen($params);
        //构造post请求的头
        $header = "POST /webservice.asmx/mdsmssend HTTP/1.1\r\n";
        $header .= "Host:sdk.entinfo.cn\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: ".$length."\r\n";
        $header .= "Connection: Close\r\n\r\n";
        //添加post的字符串
        $header .= $params."\r\n";
        return $header;
    }

    /**
     * 设置当前接口地址
     */
    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @desc 格式化短信发送的结果
     * @param $fp
     * @return mixed
     */
    public function formatSendResult($fp){

        while (!feof($fp)) {
            $getFp = fgets($fp, 1024); //去除请求包的头只显示页面的返回数据
        }

        $returnStatus=str_replace("<string xmlns=\"http://entinfo.cn/\">","",$getFp);
        $returnStatus=str_replace("</string>","",$returnStatus);

        $result=explode("-",$returnStatus);

        if(count($result)>1){
            $msg = '发送失败:'.$returnStatus.', '.(!empty(self::$statusTextMap[$returnStatus]) ? self::$statusTextMap[$returnStatus] : '联系漫道短信渠道服务商' );
        }else{
            $msg = '发送成功 返回序列号为:'.$returnStatus;
        }
        //\CustomLog::addLog('Md-sendSms:',$msg,1);
        Log::info(json_encode('Md-sendSms:'.$msg));
        return $returnStatus;
    }



}
