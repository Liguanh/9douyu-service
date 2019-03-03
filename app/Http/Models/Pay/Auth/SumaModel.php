<?php
/**
 * @desc    丰付支付
 * @date    2017年01月10日
 * @author  @llper
 *
 */

namespace App\Http\Models\Pay\Auth;

use App\Http\Models\Pay\PayModel;
#use App\Services\Pay\Auth\Suma\SumaPay;
use App\Services\Services;
use App\Tools\ToolMoney;

class SumaModel extends PayModel
{
    const   APP_REQUEST_FROM_WAP  = 'wap',
            APP_REQUEST_FROM_PC   = 'pc',
            APP_REQUEST_FROM_APP  = 'app';

    private $service;

    public function __construct()
    {
        parent::__construct('SUMAPAY_CONFIG');
    }

    /**
     * @param   array $params
     * @return  array
     * @desc    生成丰付支付订单
     */
    public function encrypt(array $params)
    {
        header("Content-type: text/html; charset=gbk");
        $config         = $this->config;
        //来源 PC WAP APP
        $from           = strtolower($params['platform']);
        $parameter = [
            'requestId'     => $params['order_id'],                 //请求流水编号
            'totalPrice'    => $params['totalPrice'],               //实际支付金额
            'userIdIdentity'=> $params['user_id'],                  //商户用户-唯一标识
            'noticeurl'     => $params['notify_url'],               //异步后台通知地址
            'fund'          => $params['totalPrice'],               //产品定价

            'passThrough'   => $config['passThrough'],              //透传信息
            'tradeCode'     => $config['tradeCode'],                //交易代码
            'tradeProcess'  => $config['tradeProcess'],             //商户代码
            'totalBizType'  => $config['totalBizType'],             //业务类型



            'rePayTimeOut'  => $config['rePayTimeOut'],             //是否允许重复支付 0 不允许

            'bizType'       => $config['bizType'],                  //产品业务类型
            'productId'     => $config['productId'],                //产品ID
            'productName'   => $config['productName'],              //产品名称
            'productNumber' => $config['productNumber'],            //产品数量
            'goodsDesc'     => $config['goodsDesc'],                //商品描述
            'merAcct'       => $config['merAcct'],                  //产品供应商编码
            'merKey'        => $config['merKey'],                   //签名秘钥
            'encode'        => $config['encode'],                   //签名秘钥

        ];


        $signStr = "";
        $signStr = $signStr . $params['order_id'];
        $signStr = $signStr . $config['tradeProcess'];
        $signStr = $signStr . $config['totalBizType'];
        $signStr = $signStr . $params['totalPrice'];
        $signStr = $signStr . $params['notify_url'];
        $signStr = $signStr . $params['user_id'];
        $signStr = $signStr . $config['passThrough'];

        $signatrue  = self::HmacMd5($signStr, $config['merKey']);

        $parameter['mersignature'] = $signatrue;

        \Log::info(__METHOD__.' -SUMA-ENCRTPT-01-', [$signStr, $signatrue, $parameter ]);
        #$return = [
        #    'url'       => $config['gateway'],
        #    'parameter' => $parameter,
        #];
        #curl   请求-创建订单
        $service    = new Services();
        #\Log::info(__METHOD__.'-ENCRTPT-01-'.$config['gateway']);
        $sumaOrder  = $service->curlOpen($config['gateway'],$parameter,false);
        //$sumaOrder  = iconv("GB2312", "UTF-8", $sumaOrder);
        $sumaOrder  = self::charsetEncode($sumaOrder, "GB2312", "UTF-8");

        $result     = json_decode($sumaOrder,true);
        \Log::info(__METHOD__.' -SUMA-ENCRTPT-02- ', [$parameter, $sumaOrder, $result ]);
        if($result['result'] !="00000"){
            $msg    = self::errorMsgShow($result['result']);
            $result['errorMsg'] = $msg ? $msg : $result['errorMsg'];
        }
        return $result;

    }



    /**
     * @param   array $params
     * @return  array
     * 发送短信验证
     *
     **/
    public function sendCode(array $params){
        header("Content-type: text/html; charset=gbk");
        $config         = $this->config;
        //来源 PC WAP APP
        $from           = strtolower($params['platform']);

        $requestOrderId = $params['order_id'];
        $mobilePhone    = $params['mobilePhone'];
        $bankCode       = $params['bankCode'];

        $bankCardType   = $params['bankCardType'];
        $bankAccount    = trim($params['bankAccount']);

        $userId         = $params["userId"];
        $idType         = '0';
        $name           = $params['name'];
        $idCard         = strtoupper(trim($params['idCard']));
        $isFirst        = $params['isFirst'];
        $name   = self::charsetEncode($name, "UTF-8", "GBK");
        $tradeCodeSend  = $config['tradeCodeSend1'];
        $requestId      = $requestOrderId;
        if($isFirst == 2 ){
            $tradeCodeSend  = $config['tradeCodeSend2'];
            $requestId  =   $requestOrderId.'_2';
        }
        $parameter = [

            'tradeCode'     => $tradeCodeSend,                      //交易代码
            'requestId'     => $requestId,                          //请求流水编号
            'requestOrderId'=> $requestOrderId,                     //订单号
            'tradeProcess'  => $config['tradeProcess'],             //商户代码
            'mobilePhone'   => $mobilePhone,                        //预留手机号
            'bankCode'      => $bankCode,                           //银行代码
            'bankAccount'   => $bankAccount,                        //银行卡号
            'bankCardType'  => $bankCardType,                       //银行卡类型

            'userIdIdentity'=> $userId,                             //商户用户-唯一标识
            'idType'        => $idType,                             //证件类型
            'idNumber'      => $idCard,                             //身份证号
            'name'          => $name,                               //姓名
            'encode'        => $config['encode'],                   //请求报文编码
        ];


        $signStr = "";
        $signStr = $signStr . $requestId;
        $signStr = $signStr . $requestOrderId;
        $signStr = $signStr . $config['tradeProcess'];
        $signStr = $signStr . $idType;
        $signStr = $signStr . $idCard;
        $signStr = $signStr . $name;

        $signStr = $signStr . $mobilePhone;
        $signStr = $signStr . $bankCode;
        $signStr = $signStr . $bankAccount;
        $signStr = $signStr . $bankCardType;
        $signStr = $signStr . $userId;

        $signatrue  = self::HmacMd5($signStr, $config['merKey']);

        $parameter['mersignature'] = $signatrue;

        #$return = [
        #    'url'       => $config['gateway'],
        #    'parameter' => $parameter,
        #];
        #curl   请求-创建订单
        $service    = new Services();
        #\Log::info(__METHOD__.'-ENCRTPT-01-'.$config['gateway']);
        $sumaData  = $service->curlOpen($config['gateway'],$parameter,false);

        $sumaData  = self::charsetEncode($sumaData, "GB2312", "UTF-8");

        \Log::info(__METHOD__.' -SUMA-SENDCODE-03-', [$signStr, $signatrue, $parameter ]);
        \Log::info(__METHOD__.' -SUMA-SENDCODE-04- '.var_export($sumaData,true));

        $result     = json_decode($sumaData,true);
        if($result['result'] !="00000"){
            $msg    = self::errorMsgShow($result['result']);
            $result['errorMsg'] = $msg ? $msg : $result['errorMsg'];
        }
        return $result;

    }



    /**
     * @param array $params
     * @return array
     */
    public function submit(array $params){
        header("Content-type: text/html; charset=gbk");
        $config         = $this->config;
        //来源 PC WAP APP
        $from           = strtolower($params['platform']);

        $requestOrderId = $params['order_id'];
        $mobilePhone    = $params['mobilePhone'];
        $bankCode       = $params['bankCode'];

        $bankCardType   = $params['bankCardType'];
        $bankAccount    = trim($params['bankAccount']);

        $userId         = $params["userId"];
        $idType         = '0';
        $name           = $params['name'];
        $idCard         = strtoupper(trim($params['idCard']));

        $randomValidateId   = $params['randomValidateId'];
        $randomCode         = $params['randomCode'];
        $tradeId            = $params['tradeId'];

        $validDate  = '';
        $cvnCode    = '';
        $name       = self::charsetEncode($name, "UTF-8", "GBK");
        $parameter  = [

            'tradeCode'     => $config['tradeCodePay'],             //交易代码
            'requestId'     => $requestOrderId,                     //请求流水编号
            'requestOrderId'=> $requestOrderId,                     //订单号
            'tradeProcess'  => $config['tradeProcess'],             //商户代码

            'bankCode'      => $bankCode,                           //银行代码
            'bankAccount'   => $bankAccount,                        //银行卡号
            'bankCardType'  => $bankCardType,                       //银行卡类型

            'validDate'     => $validDate,                          //信用卡有效期
            'cvnCode'       => $cvnCode,                            //信用卡VCN码

            'userIdIdentity'=> $userId,                             //商户用户-唯一标识
            'idType'        => $idType,                             //证件类型
            'idNumber'      => $idCard,                             //身份证号
            'name'          => $name,                               //姓名
            'mobilePhone'   => $mobilePhone,                        //预留手机号
            'isNeedBind'    => $config['isNeedBind'],               //是否绑定
            'passThrough'   => $config['passThrough'],              //透传信息

            'randomValidateId'  => $randomValidateId,               //发送短信校验码编号
            'randomCode'        => $randomCode,                     //短信校验码
            'tradeId'           => $tradeId,                        //交易流水号

            'encode'        => $config['encode'],                   //请求报文编码
        ];


        $signStr = "";
        $signStr = $signStr . $requestOrderId;
        $signStr = $signStr . $requestOrderId;
        $signStr = $signStr . $config['tradeProcess'];
        $signStr = $signStr . $bankCode;
        $signStr = $signStr . $bankAccount;
        $signStr = $signStr . $bankCardType;


        $signStr = $signStr . $validDate;
        $signStr = $signStr . $cvnCode;

        $signStr = $signStr . $idType;
        $signStr = $signStr . $idCard;
        $signStr = $signStr . $name;
        $signStr = $signStr . $mobilePhone;
        $signStr = $signStr . $userId;
        $signStr = $signStr . $config['passThrough'];
        $signStr = $signStr . $tradeId;

        $signatrue  = self::HmacMd5($signStr, $config['merKey']);

        $parameter['mersignature'] = $signatrue;

        #$return = [
        #    'url'       => $config['gateway'],
        #    'parameter' => $parameter,
        #];
        #curl   请求
        $service    = new Services();
        #\Log::info(__METHOD__." :".__LINE__.$config['gateway']);
        $sumaData  = $service->curlOpen($config['gateway'],$parameter,false);
        $sumaData  = self::charsetEncode($sumaData, "GB2312", "UTF-8");

        \Log::info(__METHOD__.' -SUMA-SUBMIT-05-', [$signStr, $signatrue, $parameter ]);
        \Log::info(__METHOD__.' -SUMA-SUBMIT-06-'. var_export($sumaData,true) );

        $sumaData  = json_decode($sumaData,true);

        $this->submitReturn['order_id']       = $requestOrderId;    //订单号
        $this->submitReturn['trade_no']       = $tradeId;           //交易号

        $this->submitReturn['msg']            = isset($sumaData['errorMsg'])?$sumaData['errorMsg']:"";
        if($sumaData['result']=='00000'){

            if($sumaData["status"] == 2 ){

                $this->submitReturn['status'] = self::TRADE_SUCCESS;

            }

        }else{
            $msg    = self::errorMsgShow($sumaData['result']);
            $this->submitReturn['msg']    = $msg ? $msg : $sumaData['errorMsg'];
        }

        \Log::info(__METHOD__." : ".__LINE__." : ".var_export($this->submitReturn,true));

        return $this->submitReturn;
    }


    /**
     * @param   array $params
     * @return  array
     * 支付解密方法
     */
    public function decrypt(array $params)
    {
        header("Content-type: text/html; charset=gbk");
        $orderId = isset($params["requestId"])?$params["requestId"]:"";
        $tradeNo = isset($params['payId'])?$params['payId']:"";
        $amount  = isset($params['totalPrice'])?$params['totalPrice']:"-1";
        \Log::info(__METHOD__." : ".__LINE__." : ".var_export($params,true));
        $this->decryptReturn['verify_status']   = true;                 //签名状态
        $this->decryptReturn['order_id']        = $orderId;             //订单号
        $this->decryptReturn['trade_no']        = $tradeNo;             //流水号
        $this->decryptReturn['amount']          = $amount;              //订单金额

        if( isset($params['status']) && $params['status'] == 2 ){

            $this->decryptReturn['trade_status']   = self::TRADE_SUCCESS;
            $this->decryptReturn['msg']            = '支付成功';
        }
        \Log::info(__METHOD__." : ".__LINE__." : ".var_export($this->decryptReturn,true));
        return $this->decryptReturn;
    }


    /**
     * @param   array $params
     * @return  array
     * @desc    丰付查单方法
     */
    public function search(array $params){

        $config         = $this->config;

        $requestId          = "JDY_SUMA_".date('YmdHis').rand(1000,9999);
        $originalRequestId  = $params["order_id"];
        $merchantCode       = $config['tradeProcess'];
        $merKey             = $config["merKey"];

        $parameter = [
            'merchantCode'      => $merchantCode,                   //商户编号
            'requestId'         => $requestId,                      //请求流水号
            'originalRequestId' => $originalRequestId,              //订单号
            'encode'            => $config['encode'],               //请求报文编码
        ];

        $signStr = "";
        $signStr = $signStr . $requestId;
        $signStr = $signStr . $merchantCode;
        $signStr = $signStr . $originalRequestId;

        $signatrue  = self::HmacMd5($signStr, $merKey);

        $parameter['signature'] = $signatrue;

        #curl   请求
        $service    = new Services();

        #\Log::info(__METHOD__." :".__LINE__.$config['searchway']);

        $sumaData  = $service->curlOpen($config['searchway'],$parameter,false);
        $sumaData  = self::charsetEncode($sumaData, "GB2312", "UTF-8");

        \Log::info(__METHOD__." : ".__LINE__." : ". var_export($parameter,true) );
        \Log::info(__METHOD__." : ".__LINE__." : ". var_export($sumaData,true) );

        $sumaData  = json_decode($sumaData,true);

        $this->searchReturn['order_id'] = $originalRequestId;     //订单号
        $this->searchReturn['trade_no'] = $requestId;             //请求流水号

        $msg    = isset($sumaData['errorMsg']) ? $sumaData['errorMsg'] : "支付失败";
        $this->searchReturn['msg']      = $msg;
        if($sumaData['result']=='00000'){
            $this->format($sumaData);
        }

        \Log::info(__METHOD__." : ".__LINE__." : ".var_export($this->searchReturn,true));

        return $this->searchReturn;

    }


    /**
     * @param   array $returnData
     * @desc    格式化
     */
    public function format(array $returnData){

        $status = isset($returnData['status'])?$returnData['status']:"";
        $originalRequestId = isset($returnData['originalRequestId'])?$returnData['originalRequestId']:"";
        $cash   = isset($returnData['tradeSum'])?$returnData['tradeSum']:"-1";
        $tradeStatus= self::TRADE_FAIL;
        $msg        = self::TRADE_FAIL_MSG;
        switch ($status){
            case 0:
                $tradeStatus= self::TRADE_WAITING;
                $msg        = self::TRADE_WAITING_MSG;
                break;
            case 1:
                $tradeStatus= self::TRADE_DEALING;
                $msg        = self::TRADE_DEALING_MSG;
                break;
            case 2:
                $tradeStatus= self::TRADE_SUCCESS;
                $msg        = self::TRADE_SUCCESS_MSG;
                break;
            case 3:
                $tradeStatus= self::TRADE_FAIL;
                $msg        = self::TRADE_FAIL_MSG;
                break;
        }

        $this->searchReturn['status']       = $tradeStatus;         //支付状态
        $this->searchReturn['msg']          = $msg;
        $this->searchReturn['order_id']     = $originalRequestId;
        $this->searchReturn['cash']         = $cash;

    }

     /**
      * @desc   HmacMd5 加密
      * @param  $data   签名串
      * @param  $key    签名秘钥
      **/
     public static function HmacMd5($data, $key) {

        $key 	= self::charsetEncode($key, 'GB2312', 'UTF-8');
        $data 	= self::charsetEncode($data, 'GB2312', 'UTF-8');

        $b = 64;    // byte length for md5
        if (strlen($key) > $b) {
            $key = pack("H*", md5($key));
        }
        $key = str_pad($key, $b, chr(0x00));
        $ipad = str_pad('', $b, chr(0x36));
        $opad = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad;
        $k_opad = $key ^ $opad;

        return md5($k_opad . pack("H*", md5($k_ipad . $data)));
    }


    /**
     * 实现多种字符编码方式
     * @param   $str            需要编码的字符串
     * @param   $_output_charset输出的编码格式
     * @param   $_input_charset 输入的编码格式
     * return   编码后的字符串
     */
    public static function charsetEncode($str,$input_charset ,$output_charset) {
        $outStr = "";
        $input_charset  = strtoupper($input_charset);
        $output_charset = strtoupper($output_charset);
        if($input_charset == $output_charset || empty($str)) {
            $outStr = $str;
        } elseif (function_exists("mb_convert_encoding")) {

            $str_charset = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));

            if($input_charset != $str_charset){
                $input_charset = $str_charset;
            }
            $outStr = mb_convert_encoding($str,$output_charset,$input_charset);

        } elseif(function_exists("iconv")) {

            $outStr = iconv($input_charset, $output_charset, $str);

        }else{
            $outStr = $str;
        }
        return $outStr;
    }


    /**
     * @desc    丰付支付错误提示-转换
     * 因部分提示信息-包含英文，所以转换为纯中文
     *
     **/
    public static function errorMsgShow($errorCode=''){

        $errorArr   = [
            "100130001"=>"交易代码不正确",
            "100130002"=>"交易号不正确",
            "100130003"=>"商户代码不正确",
            "100130004"=>"签名错误",
            "100130005"=>"身份证号码不准确",
            "100130006"=>"报文编码错误",
            "100130011"=>"业务类型不正确",
            "100130013"=>"透传信息不正确",
            "100130014"=>"商品描述不正确",
            "100130015"=>"不允许重复支付",
            "100130017"=>"回调链接错误",
            "100130021"=>"商品编号错误",
            "100130022"=>"商品名错误",
            "100130024"=>"签名错误",
            "100130025"=>"产品类型错误",
            "100130026"=>"产品数量错误",
            "100130031"=>"订单号不正确",
            "100130032"=>"系统繁忙，请稍后再试",
            "100130034"=>"支付验证不正确",
            "100130035"=>"验证码错误",
            "100130036"=>"银行卡不准确",
            "100130037"=>"银行卡号错误",
            "100130038"=>"银行卡类型不正确",
            "100130039"=>"系统繁忙，请稍后再试",
            "100130040"=>"系统繁忙，请稍后再试",
            "100130041"=>"系统繁忙，请稍后再试",
            "100130042"=>"身份证错误",
            "100130043"=>"用户姓名不准确",
            "100130044"=>"手机号不准确",
            "100130045"=>"系统忙，请稍后再试",
            "100130046"=>"系统忙，请稍后再试",
            "100130047"=>"系统忙，请稍后再试",
        ];

        return isset($errorArr[$errorCode]) ? $errorArr[$errorCode] :'';

    }

}