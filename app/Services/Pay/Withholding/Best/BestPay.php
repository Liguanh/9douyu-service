<?php
namespace App\Services\Pay\Withholding\Best;

class BestPay {

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 签约
     * @param array $param
     * @return array
     */
    public function signed($param){
        //公共配置参数
        $config = $this->config;
        $url = $config['signUrl'];
        $data['platCode']       =   $config['platCode'];         //长度16内的,平台号(对方提供)
        $data['custCode']       =   $config['custCode'];         //长度64内的,发起客户号(对方提供)
        $data['currencyCode']   =   $config['currencyCode'];                      //支持的币种
        $data['validateType']   =   $config['validateType'];                      //无扣费验证
        $data['netWorkNature']  =   $config['netWorkNature'];
        $data['userFullName']   =   $config['userFullName'];
        $data['ebkType']        =   $config['ebkType'];
        $data['payeeName']      =   $config['payeeName'];
        $data['netWorkAreaCode']=   $config['netWorkAreaCode'];
        $data['arpType']        =   "01";//代收
        //server
        $data['reqIp']          =   $param['user_ip'];    //请求的对方ip(内网ip)
        $data['reqSeq']         =   $this->orderId();          //长度64内的字母和数字字符,请求流水号(自己生成)
        $data['reqTime']        =   date("YmdHis");                     //请求时间
        $data['extOrderSeq']    =   $param['orderId'];           //外部系统订单号
        $data['bankAccount']    =   array(
            "areaCode"      =>  $config['areaCode'],          //网页传递来的银行区域码
            "accountCode"   =>  $param['card_no'],       //网上传递来的账户
            "bankCardName"  =>  $param["real_name"],      //网页传递来的账号名字
            "bankCode"      =>  $config['bankCode'][$param['bankCode']],          //网上传递来的银行编码
            "certNo"        =>  $param["identity_card"],            //网页传递来的身份证号
            "cardType"      =>  1,          //网上传递来的银行卡类型,借记卡
            "certType"      =>  "00",                   //证件类型-身份证
            "perEntFlag"    =>  1,                      //对私
        );
        dd($data);
        $return = $this->doneCode($url,$data);
        dd($return);
        $recv = json_decode($return,true);
        return $recv['data'];
    }

    /**
     * 确认支付
     * @param array $param
     * @return array $res
     */
    public function submit($param){
        $config = $this->config;
        $url                    =   $config['payUrl'];
        $data['currencyCode']   =   $config['currencyCode'];                      //支持的币种
        $data['custCode']       =   $config['custCode'];         //长度64内的,发起客户号(对方提供)
        $data['platCode']       =   $config['platCode'];             //长度16内的,平台号(对方提供)
        $data['payeeAccount']   =   array(
            "accountCode"   =>  $config['accountCode'],
            "accountName"   =>  $config['payeeName'],
        );
        $data['accountCode']    =   $param['card_no'];
        $data['amount']         =   $param['cash']*100;                //交易金额 $order['cash']*100
        $data['extOrderSeq']    =   $param['orderId'];           //外部系统订单号
        $data['reqIp']          =   $param['user_ip'];            //请求的对方ip(内网ip)
        $data['reqSeq']         =   $this->orderId();           //长度64内的字母和数字字符,请求流水号(自己生成)
        $data['reqTime']        =   date("YmdHis");                 //请求时间
        //查询出来银行卡签名id
        $data['signId']         =   $param['signId'];
        $return = $this->doneCode($url,$data);
        $recv = json_decode($return,true);
        return $recv['data'];
    }
    

    /**
     * 翼支付查单
     * @param $orderId
     * @return array
     */
    public function search($param){
        $config  = $this->config;
        $url = $config['checkOrder'];
        $data['reqSeq']         =   $this->orderId();
        $data['custCode']       =   $config['custCode'];         //长度64内的,发起客户号(对方提供)
        $data['extOrderSeq']    =   $param['order_id'];           //外部系统订单号
        $data['platCode']       =   $config['platCode'];             //长度16内的,平台号(对方提供)
        $data['reqIp']          =   $param['user_ip'];    //请求的对方ip(内网ip)

        $return = $this->doneCode($url,$data);
        $recv = json_decode($return,true);
        return $recv['data'];

    }

    /**
     * 生成订单号
     */
    private function orderId(){
        $rand       = time() . getmypid() . rand(100000, 999999) . rand(100000, 999999);
        $str        = md5($rand);
        return  strtoupper(substr($str, 0, 8));
    }

    /**
     * 翼支付接口处理数据
     * $param $url 接口url，$data curl数据
     * @return json
     */
    private function doneCode($url,$data){

        $basePath = base_path();

        //生成JOSN格式的明文字符串,并除掉JSON转换中产生的反斜杠
        $dataString = json_encode($data, JSON_UNESCAPED_UNICODE);
        $dataString = str_replace("\\", "", $dataString);

        //获取私钥，进行对明文字符串进行加签
        $fp = fopen($basePath."/app/Services/Pay/Withholding/Best/cert/server.key","r");
        $private_key = fread($fp,8192);
        fclose($fp);
        $algo = "sha1WithRSAEncryption";
        openssl_sign($dataString, $binary_signature, $private_key, $algo);
        $sign = base64_encode($binary_signature);

        //获取公钥
        $cert = file_get_contents($basePath."/app/Services/Pay/Withholding/Best/cert/server.crt");
        $arr = explode("-----", $cert);
        $arr2 = explode("\n",$arr[2]);
        $temp = implode($arr2);

        //生成发送给后台的报文数组
        $send_array = [
            "platformCode" => $data['platCode'],
            "cert" =>$temp ,
            "sign" =>$sign,
            "data" => $data,
        ];

        //将报文数组进行JSON格式转换，并除掉JSON转换中产生的反斜杠
        $sendString = json_encode($send_array,JSON_UNESCAPED_UNICODE);
        $sendString = str_replace("\\", "", $sendString);

        //request Log
        //TODO 记录文件日志
        //\CustomLog::addLog('BestPay','REQUEST:'.$url."\n".$sendString."\n");
        
        require($basePath."/app/Services/Pay/Withholding/Best/api/utils.class.php");
        //调用方法，发送报文到后台,并获取后台返回的JSON信息bes
        list($return_code, $return_content) = http_post_data($url, $sendString);

        //获取后台返回的JSON报文
        //TODO 记录文件日志
        //\CustomLog::addLog('BestPay','RETURN:'.$return_content."\n");

        return $return_content;
    }
}



?>
