<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/31
 * Time: 15:27
 */

class UmpTest extends TestCase{



    /**
     * 获取用户的绑定银行卡
     * @dataProvider checkCardList
     * @param $userId
     */


    public function testCheckCard($method,$driver,$cardNo,$idCard,$name,$phone,$expected){

        $url = 'http://service.9douyu.com/recharge';


        $postData = [
            'method'        => $method, //接口
            'driver'        => $driver, //支付渠道
            'card_no'       => $cardNo,   //回调数据
            'id_card'       => $idCard,  //回调的加密KEY
            'name'          => $name ,      //商户号
            'phone'         =>$phone
        ];
        $args['expected'] = $expected;


        $this->postRequest($url, $postData,array(), array(), $args);

    }


    /**
     * @param $method
     * @param $driver
     * @param $cardNo
     * @param $idCard
     * @param $name
     * @param $phone
     * @param $notifyUrl
     * @param $cash
     * @param $expected
     * 联动优势支付测试
     * @dataProvider submitList
     */
    
    public function testSubmit($method,$driver,$cardNo,$idCard,$name,$phone,$notifyUrl,$cash,$orderId,$expected){

        $url = 'http://service.9douyu.com/recharge';


        $postData = [
            'method'        => $method, //接口
            'driver'        => $driver, //支付渠道
            'card_no'       => $cardNo,   //回调数据
            'id_card'       => $idCard,  //回调的加密KEY
            'name'          => $name ,      //姓名
            'phone'         => $phone,
            'notify_url'    => $notifyUrl,
            'cash'          => $cash,
            'order_id'      => $orderId,
        ];
        $args['expected'] = $expected;


        $this->postRequest($url, $postData,array(), array(), $args);

    }


    /**
     * @param $method
     * @param $driver
     * @param $orderId
     * @param $expected
     * @dataProvider searchList
     * 主动查单接口
     */
    public function testSearch($method,$driver,$orderId,$expected){

        $url = 'http://service.9douyu.com/recharge';


        $postData = [
            'method'        => $method, //接口
            'driver'        => $driver, //支付渠道
            'order_id'      => $orderId,
        ];
        $args['expected'] = $expected;


        $this->postRequest($url, $postData,array(), array(), $args);
    }

    /**
     * @param $response
     * 请求响应结果处理
     */
    public function response($response, $args){

        //dd($response['content']);
        $return = json_decode($response['content'],true);
        $this->assertEquals($return['data']['status'], $args['expected'], $return['msg']);

    }


    /**
     * @return array
     * method       解密方法
     * driver       支付渠道
     */
    public function checkCardList(){
        return [
            ['checkCard','UmpWithholding','6277830204430492','410004198005080338','张爽','12742984378','success'],//成功
            ['checkCard','UmpWithholding','621483010442049','410004198005080338','张爽','12742984378','fail']    //失败

        ];
    }


    /**
     * @return array
     * method       解密方法
     * driver       支付渠道
     * data         需要解密的数据
     * encryptkey   用于解密的KEY
     */
    public function searchList(){
        return [
            ['search','UmpWithholding','201605312204035037','success'],//成功
            ['search','UmpWithholding','201606011806122314','unknow']    //订单号不存在

        ];
    }


    /**
     * @return array
     * method       解密方法
     * driver       支付渠道
     * data         需要解密的数据
     * encryptkey   用于解密的KEY
     */
    public function submitList(){
        return [
            ['submit','UmpWithholding','6277830204430492','410004198005080338','张爽','12742984378','http://www.wlask.com/notify.php',0.01,date("YmdHis") . rand(1000,9999),'success'],//成功
            ['submit','UmpWithholding','6277830204430492','4290041990050439','张爽','12742984378','http://www.wlask.com/notify.php',0.01,date("YmdHis") . rand(1000,9999),'fail'],//成功

        ];
    }

}