<?php
namespace App\Services\Pay\Withholding\Ump;

use App\Services\Services;

$basePath = base_path();
require_once($basePath."/app/Services/Pay/Withholding/Ump/api/plat2Mer.class.php");
require_once($basePath."/app/Services/Pay/Withholding/Ump/api/mer2Plat.class.php");
require_once($basePath."/app/Services/Pay/Withholding/Ump/api/simple_html_dom.class.php");


class Umpay {

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 签约验卡
     * @param array $param 业务数据
     * @return array $return
     */
    public function checkCard($param){
        $config = $this->config;
        $data['charset']    = $config['charset'];                      //字符编码【charset】
        $data['mer_id']     = $config['mer_id'];                       //商户编号【mer_id】
        $data['res_format'] = $config['res_format'];                       //响应数据格式【res_format】
        $data['version']    = $config['version'];                        //版本号 【version】
        $data['sign_type']  = $config['sign_type'];
        $data['service'] = 'comm_auth';
        $data['auth_type']  = 1;      //验证类型,直接验证
        $data['auth_mode']  = 0;      //验证模式,银行卡验证
        $data['identity_type']  = 1;  //证件类型,身份证
        $data['version']    = "1.0";  //验卡的版本号是1.0
        //业务接收签约参数
        $data['order_id']   = $this->orderId();         //订单号
        $data['bank_account']   = $param['bank_account']; //银行卡号
        $data['account_name']   = $param['account_name']; //账户姓名
        $data['identity_code']  = $param['identity_code'];   //身份证号

        if(isset($param['phone']) && $param['phone']){
            $data['mobile_id']      = $param['phone']; //四要素,手机号

        }
        //请求接口
        $map = new \HashMap();
        foreach($data as $key => $value){
            $map->put($key,$value);
        }
        $url = $this->getUrl($map);
        $http = new Services();
        $res = $http->curlOpen($url);
        $return = $this->parseUMPResult($res);
        return $return;
    }

    /**
     * 支付
     * @param array  $data 业务数据
     * @return array $return
     */
    public function submit($param){
        $config = $this->config;
        $data = array(
            'service' => $config['service'],
            'amt_type' => $config['amt_type'],
            'pay_type' => $config['pay_type'],
            'identity_type' => $config['identity_type'],
            'goods_id' => $config['goods_id'],
            'goods_inf' => $config['goods_inf'],
            'media_id' => $config['media_id'],
            'media_type' => $config['media_type'],
            'settle_date' => $config['settle_date'],
            'mer_priv' => $config['mer_priv'],
            'expand' => $config['expand'],
            'expire_time' => $config['expire_time'],
            'risk_expand' => $config['risk_expand'],
            'charset' => $config['charset'],
            'mer_id' => $config['mer_id'],
            'res_format' => $config['res_format'],
            'version' => $config['version'],
            'sign_type' => $config['sign_type'],
            'notify_url' =>  $param['notify_url'],
            'mer_date' => date('Ymd'),
            'order_id' => $param['order_id'],
            'amount' => $param['cash']*100,//单位：分 $order['cash']*100
            'card_id' => $param['card_no'],
            'identity_code' => $param['id_card'],
            'card_holder' => $param["name"],
        );
        //请求接口
        $map  = new \HashMap();
        $risk = 'A003:20#B002:01#';
        foreach($data as $key => $value){
            $map->put($key,$value);
            //添加风控信息
            if($key == 'identity_code'){$risk.="B0003:".$value."#";}
            if($key == 'card_holder'){$risk.="B0005:".$value."#";}
        }
        $risk       .=  "D0003:".$_SERVER["REMOTE_ADDR"];
        $map->put('risk_expand',$risk);
        $url = $this->getUrl($map);
        $http = new Services();
        $res = $http->curlOpen($url);
        $return = $this->parseUMPResult($res);
        return $return;
    }

    /**
     * 查单接口
     */
    public function search($orderId){
        $config = $this->config;
        $data['charset']    = $config['charset'];                      //字符编码【charset】
        $data['mer_id']     = $config['mer_id'];                    //商户编号【mer_id】
        $data['res_format'] = $config['res_format'];                  //响应数据格式【res_format】
        $data['version']    = $config['version'];                     //版本号 【version】
        $data['sign_type']  = $config['sign_type'];                     //签名方式【sign_type】

        $data['service']       =  "mer_order_info_query";  //接口名称【service】
        $data['order_id']      =  $orderId;
        $data['mer_date'] = substr($orderId,0,8);

        $map = new \HashMap();
        foreach($data as $key => $value){
            $map->put($key,$value);
        }
        $url = $this->getUrl($map);
        $http = new Services();
        $res = $http->curlOpen($url);
        $return = $this->parseUMPResult($res);
        return $return;
    }

    /**
     * 获取接口url
     */
    private function getUrl($map){
        $reqData     = \MerToPlat::makeRequestDataByGet($map);
        $url         = $reqData->getUrl();
        return $url;
    }

    /**
     * 处理返回数据
     */
    private function deCode($res){
        $html       = str_get_html($res);
        $str        = $html->find("meta[name='MobilePayPlatform']", 0)->content;
        $details    = explode("&", $str);
        $res = [];

        foreach($details as $detail){
            $info = explode("=", $detail);
            if($info[0]=='ret_code'){
                $res['ret_code'] = $info[1];
            }
            if($info[0]=='order_id'){
                $res['order_id'] = $info[1];
            }
            if($info[0]=='trade_no'){
                $res['trade_no'] = $info[1];
            }
            if($info[0]=='ret_msg'){
                unset($info[0]);
                $res['ret_msg'] = implode('',$info);
            }
        }
        return $res;
    }

    /**
     * 异步获取结果
     */
    public function decrypt($params){
        
        $map = new \HashMap();
        unset($params['platform']);
        unset($params['_URL_']);
        foreach($params as $key => $value){
            $map->put($key,$value);
        }

        //获取UMPAY平台请求商户的支付结果通知数据,并对请求数据进行验签,此时商户接收到的支付结果通知会存放在这里,商户可以根据此处的trade_state订单状态来更新订单。
        $resData = new \HashMap ();
        try{
            //验签支付结果通知 如验签成功，则返回ret_code=0000
            $res = \PlatToMer::getNotifyRequestData ( $map );
            $verify_result = true;
        } catch (\Exception $e){
            $verify_result = false;
        }

        //验签后的数据都组织在resData中。
        //生成平台响应UMPAY平台数据,将该串放入META标签，以下几个参数为结果通知必备参数，实际响应参数请参照接口规范填写。
        $resData->put("mer_id", $map->get("mer_id" ));
        $resData->put("sign_type", $map->get("sign_type"));
        $resData->put("version", $map->get("version"));

        //签名结果
        if($verify_result){
            $resData->put("ret_code","1111");
            $resData->put("ret_msg", "失败" );
            $notice['error'] = \MerToPlat::notifyResponseData ( $resData );

            $resData->put("ret_code","0000");
            $resData->put("ret_msg","成功");
            $notice['ok'] = \MerToPlat::notifyResponseData ( $resData );
            return array($params['trade_state'], $notice);
        }else{
            $resData->put("ret_code","1111");
            $resData->put("ret_msg", "签名错误" );
            $result = \MerToPlat::notifyResponseData ( $resData );
            return '<html><head><META NAME="MobilePayPlatform" CONTENT="'.$result.'"></META></head><body></body></html>';
        }
    }

    /**
     * 生成订单id
     */
    public function orderId(){
        $rand       = time() . getmypid() . rand(100000, 999999) . rand(100000, 999999);
        $str        = md5($rand);
        return  strtoupper(substr($str, 0, 8));
    }


    /**
     * @param $str
     * @return array
     * 将字段串的结果解析成数组
     */
    public function parseUMPResult($res){

        $html       = str_get_html($res);
        $str        = $html->find("meta[name='MobilePayPlatform']", 0)->content;

        $result = explode('&',$str);
        $return = array();
        foreach($result as $val){
            $arr = explode('=',$val);
            $return[$arr[0]] = $arr[1];
        }
        return $return;
    }

}
?>
