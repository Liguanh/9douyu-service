<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/31
 * Time: 15:27
 */

class ReaTest extends TestCase{


    /**
     *
     * 融宝签约接口测试
     * @dataProvider signedList
     */

    public function testSigned($method,$driver,$userId,$cardNo,$idCard,$name,$phone,$cash,$notifyUrl,$orderId,$expected){

        $url = 'http://service.9douyu.com/recharge';


        $postData = [
            'method'            => $method, //接口
            'driver'            => $driver, //支付渠道
            'user_id'           => $userId,
            'card_no'           => $cardNo,
            'id_card'           => $idCard,
            'name'              => $name,
            'phone'             => $phone,
            'cash'              => $cash,
            'notify_url'        => $notifyUrl,
            'order_id'          => $orderId

        ];
        $args['expected'] = $expected;

        //$callBack = [$this,'decryptResponse'];

        $this->postRequest($url, $postData,array(), array(), $args);
    }

    /**
     * 发送验证码
     * @dataProvider sendCodeList
     * @param $userId
     */

    public function testSendCode($method,$driver,$orderId,$expected){

        $url = 'http://service.9douyu.com/recharge';


        $postData = [
            'method'            => $method, //接口
            'driver'            => $driver, //支付渠道
            'order_id'              => $orderId,   //回调数据

        ];
        $args['expected'] = $expected;


        $this->postRequest($url, $postData,array(), array(), $args);

    }

    /**
     * @param $method
     * @param $driver
     * @param $orderId
     * @param $smsCode
     * @param $expected
     * @dataProvider submitList
     * 测试支付接口
     */

    public function testSubmit($method,$driver,$orderId,$smsCode,$expected){

        $url = 'http://service.9douyu.com/recharge';


        $postData = [
            'method'            => $method, //接口
            'driver'            => $driver, //支付渠道
            'order_id'          => $orderId,   //回调数据
            'sms_code'          => $smsCode,//验证码

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
     * 查单接口
     */

    public function testSearch($method,$driver,$orderId,$expected){

        $url = 'http://service.9douyu.com/recharge';


        $postData = [
            'method'            => $method, //接口
            'driver'            => $driver, //支付渠道
            'order_id'          => $orderId,   //回调数据

        ];
        $args['expected'] = $expected;


        $this->postRequest($url, $postData,array(), array(), $args);
    }


    /**
     * @param $method
     * @param $driver
     * @param $data
     * @param $memertId
     * @param $encryptkey
     * @param $expected
     * @dataProvider decryptList
     */
    public function testDecrypt($method,$driver,$data,$memertId,$encryptkey,$expected){

        $url = 'http://service.9douyu.com/recharge';


        $postData = [
            'method'            => $method, //接口
            'driver'            => $driver, //支付渠道
            'data'              => $data,   //回调数据
            'encryptkey'       => $encryptkey,  //回调的加密KEY
            'merchant_id'       => $memertId       //商户号

        ];
        $args['expected'] = $expected;

        $callBack = [$this,'decryptResponse'];

        $this->postRequest($url, $postData,$callBack, array(), $args);

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
     * @param $response
     * 请求响应结果处理
     */
    public function decryptResponse($response, $args){

        $return = json_decode($response['content'],true);
        $this->assertEquals($return['data']['trade_status'], $args['expected'], $return['data']['trade_status']);

    }


    /**
     * 融宝签约接口测试数据
     */
    public function signedList(){

        return[
            ['signed','ReaWithholding',82692,'6277830204430492','410004198005080338','张爽','12742984378',2,'http://www.wlask.com/notify.php',date("YmdHis") . rand(1000,9999),'success'],//成功
            ['signed','ReaWithholding',82692,'621483010442049','410004198005080338','张爽','12742984378',2,'http://www.wlask.com/notify.php',date("YmdHis") . rand(1000,9999),'fail'],//失败

        ];
    }


    /**
     * @return array
     * 发送验证码测试接口
     */
    public function sendCodeList(){

        return [
            ['sendCode','ReaWithholding','201606011632198823','success'],//成功
            ['sendCode','ReaWithholding',date("YmdHis") . rand(1000,9999),'fail'],//订单号不存在

        ];
    }

    /**
     * @return array
     * 发送验证码测试接口
     */
    public function submitList(){

        return [
            ['submit','ReaWithholding','201606011632198823','725094','fail'],//失败
            ['submit','ReaWithholding',date("YmdHis") . rand(1000,9999),'723459','fail'],//订单号不存在

        ];
    }

    /**
     * @return array
     * 查单接口测试数据
     */
    public function searchList(){

        return [
            ['search','ReaWithholding','201606011632198823','fail'],//失败
            ['search','ReaWithholding',date("YmdHis") . rand(1000,9999),'fail'],//订单号不存在

        ];
    }

    /**
     * @return array
     * method       解密方法
     * driver       支付渠道
     * data         需要解密的数据
     * encryptkey   用于解密的KEY
     */
    public function decryptList(){
        return [
            [
                'decrypt','ReaWithholding',
                'bgvAue9Szbgzvg8smSY2QG4yVCfhgKWNlWV83t6x+GDf7TBge0wmbfhizpB6Zk32QaNrYAhqkvMQ8rYToMTOOveXPxQS3YdD5tIu89uhDngVQpf4yiRAia0CHxjN7dMV389zwnzIrFw1NUFwUCQOK7My9EP/1iPu+PMbOyAM3QnyPnl2TtPwzOFwo+h/EvaZ02WEskXXIziCV/OSeIZ8ae6O7FMx5MPHPd7ZJnXs5SYpLiK0zSHAeFRunAYdcFD42II2brAKSaBNkSsR26G1xk9/F4+fu5eWIN3I6XadecK+ar0VnPatB/187oE0nxYBDU5v9GAAZMUQqHqXqip63cFdQkuLHDN3suFhAJ+ToPw=',
                '100000000032858',
                'ryLQ9oFGlu6N/dTnEB3l+DAoSokQHLKFw2vJqXxmaXZpoSwnR8ltBWaFn5pJqXunBbroHN66PBNj49zcCXHPtV06mPGw7BJ7pIuM2kGlF8HViHqP3PwK40nxQndOp55Gj9E0K8zUWkl0rcYCiyHF/zm6bUc0mCvXgJsLnQ4fewg=',
                'fail'
            ],

            [
            'decrypt','ReaWithholding',
            'yH0Ij8YCTL2ot2eQDPAA6hEvpjcicmoZ0t2upgyTNZBdQj7vj/96bDP/R3LDLScUcNa8VkLuuAquEzOQwrruFMzLd+mxY2sMf34/8tXjy6sGL3jJyOwJ41x6jKqMckXNmyOixS66CcF62jto8XBcAdI/n1s4yvl6x2WbeevBsoHkKE0RXlbb5O48GVs0i3vy7iV3m78kjN80vgAimNrfo8yCmcirhnUTF6a+YJBfJpXOW+unswyleOyXD4JfMbGavHXDTul+bYE07jAS2NdqgEh3IpVc7e7uQGCZMF6faYrNF6CoRXna7wgvJUMcY0RURwLx1u8bXNiswiJMTnR5iiKJ55Kq6BZEaF1G3FfSV7c=',
             '100000000032858',
            'x/rHJwN43Q/vMTCFh8rGO3T8QoO7Y6RqoAfd3BumkLB7iZrJnowV4Bw5qEc8KLD/r0LAtka4KcNdztSGeLNvAUt99yGast0wOXFbsa44LDw6AGikFaQqZNc9tovf0B7p9SUIwywpJlUYJ5FFrOOFCXSipC8UDkkN0vi9djgD774=',
            'fail'
            ],
            [
                'decrypt','ReaWithholding',
                'Zq3ZDvMUZFXpUEZIbYcgRm49Nb8cvg6BC9tHcLYu2QAe761c2+XmC/dG5GFRBV6odqOELvP7ITzfGq9wMsJK1FH+DT416f5kJMUTp+gnsSN661xabINo/os959G2xTl7ms8UP8BMnGmhjqiP9EUAdMb5mV2K0GagegOuDW1zEm1z8C8Dt7IHRBlJImoihJplFRxYJX86/U61Xn04vIMpDYkrE1tHdh/4DsGBV5UeVNLhMVUez/HQgPwHhziI8gjQIK3hMdbIh0eQ2OrX6EmmhA==',
                 '100000000032858',
                'K/vwrOZoaCvnEJqPJ4nPBzzA2Gn8QxRJErDMgZnUiLpUC2ebRR5g9geriDAH2tnR95svz2nKDOxEbgxfyuOEnYuFzSmIiRGO2/akj1jVIsswGpWTk4nboSXyykr20Nah0cqrIB/coYTkKqWMi2ou6I8LLM3SSZuLBOhWlvJWy6g=',
                 'success'
            ]
        ];



    }

}