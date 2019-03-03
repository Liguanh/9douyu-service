<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/10/17
 * Time: 10:47
 * Desc: 创蓝营销短信接口[签名默认已经添加过的]
 */

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

class ClSms extends SMS{

    const
        SEND_SMS_URL      = '/msg/HttpBatchSendSM?',
        QUERY_BALANCE_URL = '/msg/QueryBalance?',
        SEND_SMS_URL_NEW  = '/msg/send/json',
        QUERY_BALANCE_URL_NEW = '/msg/balance/json',

        END = True;

    protected static $sendSmsStatus  = [
        '101'  => '无此用户',
        '102'  => '密码错误',
        '103'  => '提交过快(提交速度超过流量限制)',
        '104'  => '系统忙(因平台原因，暂时无法处理提交的短信)',
        '105'  => '敏感短信(关心内容包含敏感词)',
        '106'  => '消息长度错误(>536 or <=0)',
        '107'  => '包含错误的手机号码',
        '108'  => '手机号码个数错（群发>50000或<=0;单发>200或<=0）',
        '109'  => '无发送额度（该用户可用短信数已使用完）',
        '110'  => '不在发送时间内',
        '111'  => '超出该账户当月发送额度限制',
        '112'  => '无此产品，用户没有订购该产品',
        '113'  => 'extno格式错（非数字或者长度不对）',
        '115'  => '自动审核驳回',
        '116'  => '签名不合法，未带签名（用户必须带签名的前提下）',
        '117'  => 'IP地址认证错,请求调用的IP地址不是系统登记的IP地址',
        '118'  => '用户没有相应的发送权限',
        '119'  => '用户已过期',
        '120'  => '测试内容不是白名单',
        '123'  => '发送类型错误',
        '124'  => '白模版匹配错误',
        '125'  => '匹配驳回模版,提交失败',
        '127'  => '定时发送时间格式错误',
        '128'  => '内容编码失败',
        '129'  => 'json格式错误',
        '130'  => '请求参数错误(却少必要的参数)',
    ];

    public function __construct($userName = null, $password = null )
    {
       parent::__construct($userName,$password);
    }
    /**
     * @desc 发送短信的公共函数
     * @param $phones
     * @param $message
     * @return array
     */
    public function sendCode($phones, $message){
        if(stripos($message,'【九斗鱼】') >=0){
            $message = str_replace('【九斗鱼】','',$message);
        }
        $returnArr = array(
            "status"    => true,
            "errorNo"   => 0,
            "errorMsg"  => '发送成功'
        );
        if(empty($phones) || empty($message)){
            $returnArr["status"]   = false;
            $returnArr["errorNo"]  = -10;
            $returnArr["errorMsg"] = '手机号或短信内容不能为空';
            return $returnArr;
        }
        //旧短信接口数据组装
        $res = $this->sendSms($phones, $message);
        $result = $this->execResult($res);

        if($result[1]!=0){
            $returnArr = array(
                "status"    => false,
                "errorNo"   => $result[1],
                "errorMsg"  => self::$sendSmsStatus[$result[1]]
            );
        }


        //创蓝短信新的接口测试
       // $result = json_decode( $this->sendSmsNew( $phones, $message ), true );
       // if($result['code']!=0){
       //     $returnArr = array(
       //         "status"    => false,
       //         "errorNo"   => $result['code'],
       //         "errorMsg"  => self::$sendSmsStatus[$result['code']]
       //     );
       // }

        Log::info('Cl-sendSmsNew-Api-return:', $returnArr);
        return $returnArr;
    }
    /**
     * @desc 发送短信数据包装
     * @param $phones
     * @param $message
     * @return mixed
     */
    public function sendSms($phones,$message){
        if(is_array($phones)) $phones = implode(',', $phones);

        $url = $this->baseUrl.self::SEND_SMS_URL;

        $postData  =  [
            'account'    => $this->getUsername(),
            'pswd'   => $this->password,
            'msg'   => $message,
            'mobile'    => $phones,
        ];
        Log::info('Cl-sendSms-result:'.json_encode($postData));
        $res = $this->postData($url, $postData);
        return $res;
    }
    /**
     * @desc 提交数据
     * @author lgh
     * @param $url
     * @param $postData
     * @return mixed
     */
    public function postData($url, $postData){

        $postData = $this->formatRequestData($postData);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    /**
     * @desc 额度查询接口
     * @return mixed
     */
    public function queryBalance(){
        $url = $this->baseUrl.self::QUERY_BALANCE_URL_NEW;
        $postData  =  [
            'account'    => $this->getUsername(),
            'password'   => $this->password,
        ];

        $result = $this->postData($url, $postData);
        //$result = $this->execResult($res);
        return $result;
    }

    /**
     * @desc 格式化发送的内容
     * @author lgh
     * @param $postData
     * @return String
     */
    public function formatRequestData($postData){
        $data = '';
        foreach($postData as $key=>$val){

            $data .= "$key=".urlencode($val).'&';
        }
        $postData = substr($data, 0, -1);

        return $postData;
    }
    /**
     * @desc 处理返回值
     * @param $result
     * @return array
     */
    public function execResult($result){
        $result=preg_split("/[,\r\n]/",$result);
        return $result;
    }

    /*#####################创蓝短信api接口改版##############################*/

    /**
     * @desc 创蓝短信接口修改[2017-05-27]
     * @param $phones string
     * @param $message string
     * @param $needStatus bool
     * @return mixed
     */
    public function sendSmsNew( $phones,$message, $needStatus = true )
    {
        if(is_array($phones)) $phones = implode(',', $phones);

        $url = $this->baseUrl.self::SEND_SMS_URL_NEW;

        //新Api提交数据
        $postData  =  [
            'account'    => $this->getUsername(),
            'password'   => $this->password,
            'msg'   => urlencode( $message ),
            'phone'    => $phones,
            'report'  => $needStatus
        ];

        Log::info(__CLASS__.__METHOD__.'-Cl-sendSmsNew-result:'.json_encode($postData));
        $res = $this->postDataNew($url, $postData);
        return $res;
    }

    /**
     * @desc 创蓝api更新后提交数据
     * @param $url string
     * @param $postData
     * @return array
     */
    public function postDataNew( $url, $postData )
    {
        $postFields = json_encode($postData);
        $ch = curl_init ();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8'
        ));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt( $ch, CURLOPT_TIMEOUT,1);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec ( $ch );
        if( $ret == false ){
            $result = curl_error( $ch );
        }else{

            $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
            if( $rsp != 200 ){
                //$result = $rsp;
                $result = curl_error( $ch );

            }else{
                $result = $ret;
            }
        }
        return $result;
    }

}
