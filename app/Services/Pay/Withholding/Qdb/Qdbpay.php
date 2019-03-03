<?php
/**
 * Created by PhpStorm.
 * 钱袋宝支付类
 * User: caelyn
 * Date: 16/4/8
 * Time: 下午15:23
 */
namespace App\Services\Pay\Withholding\Qdb;

require_once(base_path()."/app/Services/Pay/Withholding/Qdb/api/Tool.php");

class  Qdbpay{

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 签约
     * @param array $data
     * @return mixed|string
     */
    public function signed($param){
        //公共配置参数
        $conf = $this->config;
        $info = new \stdClass();
        //业务接收签约参数
        $info->cardType=$conf['cardType'];
        $info->credentialsType=$conf['credentialsType'];
        $info->description=$conf['description'];
        $info->no_order=$param['order_id'];
        $info->notify_url= $param['notify_url'];
        $info->sign_type = $conf['sign_type'];
        $info->orderName='快捷充值'.$param['cash'];
        $info->cardNo=$param['card_no'];
        $info->payMoney=(int)$param['cash'];
        $info->phone=$param['phone'];
        $info->name=$param['name'];
        $info->credentialsNo=$param['id_card'];
        $info->cardValidDate = '';
        $info->CVN2 = '';
        $tool=new \QdbTool($conf);
        $result = $tool->send($conf['signUrl'], $info);
        return json_decode($result,true);
    }

    /**
     * 重发验证码
     */
    public function sendCode($orderId){
        $conf = $this->config;
        $info = new \stdClass();
        $info->no_order = $orderId;
        $info->sign_type = $conf['sign_type'];
        $tool=new \QdbTool($conf);
        $result = $tool->send($conf['sendCodeUrl'], $info);
        return json_decode($result,true);
    }

    /**
     * 确认支付
     * @param array $param [$order_no,$check_code]
     * @return array $res
     */
    public function submit($param){
        $conf = $this->config;
        $info = new \stdClass();
        $info->sign_type = $conf['sign_type'];
        $info->no_order = $param['no_order'];
        $info->validCode = $param['validCode'];
        $tool=new \QdbTool($conf);
        $result = $tool->send($conf['payUrl'], $info);
        return json_decode($result,true);
    }



    /**
     * 异步获取结果
     */
    public function decrypt($params){
        $conf = $this->config;
        $tool=new \QdbTool($conf);
        $vo = $tool->deCode($params);
        return $vo;
    }


    /**
     * 查单
     */
    public function search($orderId){
        $conf = $this->config;
        $info = new \stdClass();
        $info->sign_type = $conf['sign_type'];
        $info->no_order = $orderId;
        $tool=new \QdbTool($conf);
        $result = $tool->send($conf['searchUrl'], $info);
        $result = json_decode($result,true);
        return $result;
    }

}
?>
