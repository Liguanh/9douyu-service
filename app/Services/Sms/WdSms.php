<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/10/14
 * Time: 18:26
 * @desc 沃动短信接口class
 */

namespace App\Services\Sms;

use App\Services\Xml\SimpleXML;
use Illuminate\Support\Facades\Log;

class WdSms extends SMS{
    //url
    protected $size = 200;

    const
        // API参数
        SEND_SMS_URL = '/sms.aspx?action=send',
        OVERAGE_URL = '/sms.aspx?action=overage',
        //USER_ID      = '2886';
        USER_ID      = '1259';

    public function __construct($username = false, $password = false)
    {
        parent::__construct($username, $password);


    }

    /**
     * @desc       发送短信的统一接口
     * @param      $phones
     * @param      $message
     * @param null $sendTime
     * @return array
     */
    public function sendCode($phones, $message){
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

        $result = $this->doSendSms($phones, $message);
        $simpleXml = new SimpleXML();
        $res = $simpleXml->xmlToArray($result);

        if($res['returnsms']['returnstatus'] == 'Faild'){
            $returnArr["status"]   = false;
            $returnArr["errorNo"]  = -11;
            $returnArr["errorMsg"] = $res['returnsms']['message'];
        }
        Log::info('Wd-sendSms-Api-Return:', $returnArr);

        return $returnArr;
    }

    /**
     * @desc 执行短信发送操作
     * @param $phones
     * @param $message
     * @return mixed
     */
    public function doSendSms($phones, $message){

        if(is_array($phones)) $phones = implode(',', $phones);
        $url = $this->baseUrl.self::SEND_SMS_URL;

        $postData  =  [
            'userid'    => self::USER_ID,
            'account'   => $this->getUsername(),
            'password'  => $this->password,
            'content'   => $message,
            'mobile'    => $phones,
        ];
        Log::info('Wd-SendSms'. json_encode($postData));

        $result = $this->postData($url, $postData);

        return $result;
    }

    /**
     * @desc 提交数据
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    /**
     * @desc 格式化请求的数据
     * @param $postData
     * @return string
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
     * @desc 查询短信账户余额
     * @return array
     */
    public function queryBalance(){
        $returnArr = array(
            "status"    => true,
            "errorNo"   => 0,
            "errorMsg"  => ''
        );
        $postData  =  [
            'userid'    => self::USER_ID,
            'account'   => $this->getUsername(),
            'password'  => $this->password,
        ];
        $url = $this->setBaseUrl().self::OVERAGE_URL;
        $result = $this->postData($url,$postData);
        //$postData = $this->formatRequestData($postData);
        $simpleXml = new SimpleXML();
        $res = $simpleXml->xmlToArray($result);

        if($res['returnsms']['returnstatus'] == 'Faild'){
            $returnArr["status"]   = false;
            $returnArr["errorNo"]  = -11;
            $returnArr["errorMsg"] = $res['returnsms']['message'];
        }
        $returnArr["errorMsg"] = $res['returnsms'];
        return $returnArr;
    }


}
