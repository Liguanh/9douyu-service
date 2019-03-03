<?php
/**
 * @desc    丰付网银支付
 * @date    2017-04-10
 * @author  @linglu
 *
 */

namespace App\Http\Models\Pay\Online;
use App\Http\Models\Pay\PayModel;
use App\Services\Pay\Online\Jd\JdPay;

class SumaModel extends PayModel{


    public function __construct()
    {
        parent::__construct('SUMAPAY_CONFIG');
    }

    /**
     * @param array $params
     * 加密接口
     */
    public function encrypt(array $params){
        header("Content-type: text/html; charset=gbk");
        $config = $this->config;

        $parameter = array(
            "requestId"     => $params['order_id'],
            "tradeProcess"  => $config['tradeProcess'], //商户代码
            "totalBizType"  => $config['totalBizType'], //业务类型
            "totalPrice"    => $params['totalPrice'],   //实际支付金额
            "bankcode"      => $params['bankCode'],
            "backurl"       => $params['back_url'],     //取消返回地址
            "returnurl"     => $params['return_url'],   //同步回调地址
            "noticeurl"     => $params['notify_url'],   //异步回调地址
            "description"   => "",  //透传信息
            #"rnaName"       => "",
            #"rnaIdNumber"   => "",
            #"rnaMobilePhone"=> "",
            "goodsDesc"     => $config['goodsDesc'],    //商品描述
            #"userIdIdentity"=> "",  //用户ID
            "payType"       => "1", //支付类型 1-网银，5-银联
            "allowRePay"    => "0", //是否允许重新支付
            "rePayTimeOut"  => "1", //重新支付有效期
            "bankCardType"  => "1", //网银支付借贷分离标记：1-借记卡
            "productId"     => $config['productId'],        //产品ID
            "productName"   => $config['productName'],      //产品名称
            "fund"          => $params['totalPrice'],       //产品定价
            "productNumber" => $config['productNumber'],    //产品数量
            "bizType"       => $config['bizType'],                  //产品业务类型
            "merAcct"       => $config['merAcct'],                  //产品供应商编码
            'encode'        => $config['encode'],                   //签名秘钥

        );

        $signStr = "";
        $signStr = $signStr . $params['order_id'];
        $signStr = $signStr . $config['tradeProcess'];
        $signStr = $signStr . $config['totalBizType'];
        $signStr = $signStr . $params['totalPrice'];
        $signStr = $signStr . $params['back_url'];
        $signStr = $signStr . $params['return_url'];
        $signStr = $signStr . $params['notify_url'];
        $signStr = $signStr . $config['passThrough'];

        $signatrue  = self::HmacMd5($signStr, $config['merKey']);

        $parameter['mersignature'] = $signatrue;

        $return = [
            'parameter' => $parameter,
            'url'       => $config['API_GATEWAY']
        ];

        return $return;

    }


    /**
     * @param array $params
     * 解密接口
     */
    public function decrypt(array $params)
    {
        header("Content-type: text/html; charset=gbk");
        //获取配置信息
        $config     = $this->config;


        $orderId = isset($params["requestId"])  ? $params["requestId"]:"";
        $tradeNo = isset($params['payId'])      ? $params['payId']:"";
        $amount  = isset($params['totalPrice']) ? $params['totalPrice']:"-1";
        \Log::info(__METHOD__." : ".__LINE__." : ".var_export($params,true));

        $this->decryptReturn['order_id']        = $orderId;             //订单号
        $this->decryptReturn['trade_no']        = $tradeNo;             //流水号
        $this->decryptReturn['amount']          = $amount;              //订单金额
        $this->decryptReturn['verify_status']   = true;                 //签名状态

        if( isset($params['status']) && $params['status'] == 2 ){

            $this->decryptReturn['trade_status']   = self::TRADE_SUCCESS;
            $this->decryptReturn['msg']            = '支付成功';
        }

        \Log::info(__METHOD__." : ".__LINE__." : ".var_export($this->decryptReturn,true));

        return $this->decryptReturn;

    }


    /**
     * @param $orderId
     * 主动查单接口
     */
    public function search(array $params)
    {
        // TODO: Implement search() method.
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


}