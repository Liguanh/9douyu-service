<?php
/**
 * 建周发送短信接口类
 * @author  caelyn
 */

namespace App\Services\Sms;


use Log;
use nusoap_client;


//require_once "/Users/bihua/Documents/lumen/vendor/nusoap/lib/nusoap.php";

class JzSms {

    private $username;
    private $password;
    private $baseUrl = 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl';
    private $callUrl = 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService';


    public function __construct($username = false, $password = false) {

        if(!empty($username)) {
            $this->username = $username;
        }
        if(!empty($password)) {
            $this->password = $password;
        }

    }

    /**
     * 设置当前接口地址
     */
    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;
    }

    //发送短信
    public function sendCode($phones, $msg){

        //为空判断
        $returnArr = array(
            "status"    => true,
            "errorNo"   => 0,
            "errorMsg"  => '发送成功',
            "errorEmail"=> ''
        );
        if( empty($phones) || empty($msg)){
            $returnArr["status"]   = false;
            $returnArr["errorNo"]  = -30;
            $returnArr["errorMsg"] = '缺少参数';
            return $returnArr;
        }

        $phoneArr = is_array($phones) ? $phones : explode(",",$phones);
        //$phoneArr = array_chunk($phoneArr, 3000);
        if(count($phoneArr) > 3000){
            $returnArr["status"]   = false;
            $returnArr["errorNo"]  = -31;
            $returnArr["errorMsg"] = '超出最大限制数';
            return $returnArr;
        }

        $client                     = new nusoap_client($this->baseUrl, true);
        $client->soap_defencoding   = 'utf-8';
        $client->decode_utf8        = false;
        $client->xml_encoding       = 'utf-8';

        //foreach($phoneArr as $phones){
            //$phones  = implode(";", $phones);
            $phones  = implode(";", $phoneArr);

            $params = array(
                'account'   => $this->username,
                'password'  => $this->password,
                'destmobile'=> $phones,
                'msgText'   => $msg,
            );

            Log::info( "Jz-sendsms-content", $params );

            $result = $client->call('sendBatchMessage', $params, $this->callUrl);
            //余额不足
            if($result['sendBatchMessageReturn'] == -1){
                $returnArr["errorEmail"] = '建周短信账户余额不足';
            }
            Log::info("Jz-sendsms 手机号:{$phones},内容:{$msg},返回码：".$result["sendBatchMessageReturn"]);
            $status = $result['sendBatchMessageReturn']>0?'success':'fail';
            if($status == 'fail'){
                $returnArr["status"]   = false;
                $returnArr["errorNo"]  = $result['sendBatchMessageReturn'];
                $returnArr["errorMsg"] = '发送失败';
            }
            Log::info("Jz-sendsms-Api-return", $returnArr );
        //}
        return $returnArr;
    }

    //查询余额
    public function queryBalance(){
        return '';
    }
}
