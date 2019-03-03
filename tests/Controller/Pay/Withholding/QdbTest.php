<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/31
 * Time: 15:27
 */

class QdbTest extends TestCase{


    /**
     * @param $method
     * @param $driver
     * @param $cardNo
     * @param $cash
     * @param $name
     * @param $idCard
     * @param $notifyUrl
     * @param $orderId
     * @param $phone
     * @param $expected
     * @dataProvider signedList
     * 签约
     */
    public function testSigned($method,$driver,$cardNo,$cash,$name,$idCard,$notifyUrl,$orderId,$phone,$expected){

        $url = 'http://service.9douyu.com/recharge';

        $postData = [
            'method'        => $method,
            'driver'        => $driver,
            'card_no'       => $cardNo,
            'cash'          => $cash,
            'name'          => $name,
            'id_card'       => $idCard,
            'notify_url'    => $notifyUrl,
            'order_id'      => $orderId,
            'phone'         => $phone
        ];
        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);
    }


    /**
     * @dataProvider submitList
     * 钱袋宝支付接口
     */

    public function testSubmit($method,$driver,$orderId,$smsCode,$expected){

        $url = 'http://service.9douyu.com/recharge';

        $postData = [
            'method'        => $method,
            'driver'        => $driver,
            'order_id'      => $orderId,
            'sms_code'      => $smsCode
        ];
        $args['expected'] = $expected;


        $this->postRequest($url, $postData, array(), array(), $args);
    }


    /**
     * @dataProvider searchList
     * @param $orderId
     * @param $expected
     * 查单接口
     */
    public function testSearch($method,$driver,$orderId,$expected){
        $url = 'http://service.9douyu.com/recharge';


        $postData = [
            'method'            => $method,
            'driver'            => $driver,
            'order_id'          => $orderId
        ];
        $args['expected'] = $expected;


        $this->postRequest($url, $postData,array(), array(), $args);
    }


    /**
     * @param $response
     * 请求响应结果处理
     */
    public function response($response, $args){

        $return = json_decode($response['content'],true);
        $this->assertEquals($return['data']['status'], $args['expected'], $return['msg']);

    }


    /**
     * @return array
     * 钱袋宝支付接口
     */
    public function submitList(){
        return [
            ['submit','QdbWithholding',date("YmdHis") . rand(1000,9999),'6243','fail'],//验证码格式错误
            ['submit','QdbWithholding',date("YmdHis") . rand(1000,9999),'624356','fail'],//订单号不存在

        ];
    }


    /**
     * @return array
     * 钱袋宝查单测试数据
     */
    public function searchList(){
        return [
            ['search','QdbWithholding','201606011556213460','success'],//支付成功
            ['search','QdbWithholding',date("YmdHis") . rand(1000,9999),'fail'],//订单号不存在

        ];
    }

    /**
     * @return array
     * 钱袋宝签约接口
     */
    public function signedList(){
        return [

            //金额小于手续费
            ['signed','QdbWithholding','6277830204430492','1','张爽','410004198005080338','https://api.9douyu.com/API/pay/notice/platform/qdbauthpay',date("YmdHis") . rand(1000,9999),'12742984378','fail'],
            //成功
            ['signed','QdbWithholding','6277830204430492','3','张爽','410004198005080338','https://api.9douyu.com/API/pay/notice/platform/qdbauthpay',date("YmdHis") . rand(1000,9999),'12742984378','success'],
            //手机号码错误
            ['signed','QdbWithholding','6214830104420493','3','张爽','410004198005080338','https://api.9douyu.com/API/pay/notice/platform/qdbauthpay',date("YmdHis") . rand(1000,9999),'1851025803','fail'],

        ];
    }



}