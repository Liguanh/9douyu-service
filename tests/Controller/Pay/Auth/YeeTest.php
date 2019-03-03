<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/31
 * Time: 15:27
 */

class YeeTest extends TestCase{


    /**
     * @param $method
     * @param $driver
     * @param $cardNo
     * @param $userId
     * @param $cash
     * @param $name
     * @param $idCard
     * @param $notifyUrl
     * @param $returnUrl
     * @param $orderId
     * @param $expected
     * @dataProvider encryptList
     * 加密
     */
    public function testEncrypt($method,$driver,$cardNo,$userId,$cash,$name,$idCard,$notifyUrl,$returnUrl,$orderId,$expected){

        $url = 'http://service.9douyu.com/recharge';

        $postData = [
            'method'        => $method,
            'driver'        => $driver,
            'card_no'       => $cardNo,
            'user_id'       => $userId,
            'cash'          => $cash,
            'name'          => $name,
            'id_card'       => $idCard,
            'notify_url'    => $notifyUrl,
            'return_url'    => $returnUrl,
            'order_id'      => $orderId
        ];
        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);
    }


    /**
     * @param $method
     * @param $driver
     * @param $data
     * @param $encryptkey
     * @param $expected
     * @dataProvider decryptList
     * 解密
     */
    public function testDecrypt($method,$driver,$data,$encryptkey,$expected){

        $url = 'http://service.9douyu.com/recharge';


        $postData = [
            'method'            => $method,
            'driver'            => $driver,
            'data'              => $data,
            'encryptkey'       => $encryptkey,

        ];
        $args['expected'] = $expected;

        $callBack = [$this,'decryptResponse'];

        $this->postRequest($url, $postData,$callBack, array(), $args);

    }


    /**
     * @param $method
     * @param $driver
     * @param $orderId
     * @param $expected
     * @dataProvider searchList
     * 查单
     */
    public function testSearch($method,$driver,$orderId,$expected){
        $url = 'http://service.9douyu.com/recharge';


        $postData = [
            'method'            => $method,
            'driver'            => $driver,
            'order_id'          => $orderId
        ];
        $args['expected'] = $expected;

        $callBack = [$this,'searchResponse'];

        $this->postRequest($url, $postData,$callBack, array(), $args);
    }
    
    /**
     * @param $response
     * 请求响应结果处理
     */
    public function response($response, $args){


        $return = json_decode($response['content'],true);
        $this->assertEquals($return['code'], $args['expected'], $return['msg']);

    }

    /**
     * @param $response
     * 请求响应结果处理
     */
    public function decryptResponse($response, $args){


        $return = json_decode($response['content'],true);
        $this->assertEquals($return['data']['trade_status'], $args['expected'], $return['data']['msg']);

    }

    /**
     * @param $response
     * 请求响应结果处理
     */
    public function searchResponse($response, $args){


        $return = json_decode($response['content'],true);
        $this->assertEquals($return['data']['status'], $args['expected'], $return['data']['msg']);

    }

    /**
     * 添加提现银行卡测试数据
     * @return array
     * method   加密方法
     * driver   支付渠道
     * card_no  银行卡号
     * user_id  用户ID
     * cash     充值金额
     * name     姓名
     * id_card  身份证号
     * notify_url
     * return_url
     * order_id     订单号
     */
    public function encryptList(){

        return [
            ['encrypt','YeeAuth','6277830204430492',82692,3,'张爽','410004198005080338','http://www.wlask.com/notify.php','http://www.wlask.com/notify.php','JDY_201605311632074475',200],     //正确的数据
        ];
    }

    /**
     * @return array
     * 易宝查单接口
     */
    public function searchList(){
        return [
            ['search','YeeAuth',201606011336236337,'success'],     //支付成功
            ['search','YeeAuth',201606011156334618,'waiting'],     //等待付款
            ['search','YeeAuth',201606010748354030,'unknow'],     //订单号不存在

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

        return[
            [
                'decrypt','YeeAuth',
                'GCKbjP4bX2vgaFYESdSAboqDFJQE37UdHkelVg0kpb70IKN4SaYUI6AXR2H8m861S6rrzVb73bqyBMbFxzorV343UrD7xlIa19wxrBoMxUkUgzAuxjlMdQfZIBw+ne08lOJ0x/hzNdXccFlvp1TltyAhR4tJ0AkB5yfVzZadiJXc3USJ+fYfrTfn+b0OsTPtFxi1d8iCuEBna2KVj0LVnEWOm6jpOGtH+9Byh3dm9zF4gwSmi5GbNuWiTfWmoNueC69TA2a5fiAu0/Xkynz5yFE//PpfLurekjQ2XhVyX9TFo2q0dONthWzcCJTuF6l19OaqZhGSTaObPkvnxhGDMlMJNvFtw3jQY2cL1UtLSBraK2bFA0GyyUJskO2AWZ8MmH3i/p/pfM5dyuDWrkHBat1GGFE8leiAFtdlsP9KEa2kmhukStuq4W5uNIwf6/srq5j5tsGyeXpAZfTnUZ/0iDpSmT1E+NJtZ0e6mhjEaC1oH6RcyG0twx4lPrUVdFQQ',
                'RHQFX4QadSDvgexRTswVnGkmgJaFEu9Me5eTHFQ00aT1bHaapRfPLZVF2G3HTDoA9RdbBS9p2eMTC0SgpQszWlDkmdN7aP9aPj9ysHoXPzX3f47pHY3cLK20KgslgVEJYn8UR38BgGLxCPOUWP56sa0oGNmG1Hl3T9jYULqAiJg=',
                'success'
            ],//支付回调成功
            [
                'decrypt','YeeAuth',
                'GCKbjP4bX2vgaFYESdSAboqDFJQE37UdHkelVg0kpb70IKN4SaYUI6AXR2H8m861S6rrzVbbFxzorV343UrD7xlIa19wxrBMxUkUgzAuxjlMdQfZIBw+ne08lOJ0x/hzNdXccFlvp1TltyAhR4tJ0AkB5yfVzZadiJXc3USJ+fYfrTfn+b0OsTPtFxi1d8iCuEBna2KVj0LVnEWOm6jpOGtH+9Byh3dm9zF4gwSmi5GbNuWiTfWmoNueC69TA2a5fiAu0/Xkynz5yFE//PpfLurekjQ2XhVyX9TFo2q0dONthWzcCJTuF6l19OaqZhGSTaObPkvnxhGDMlMJNvFtw3jQY2cL1UtLSBraK2bFA0GyyUJskO2AWZ8MmH3i/p/pfM5dyuDWrkHBat1GGFE8leiAFtdlsP9KEa2kmhukStuq4W5uNIwf6/srq5j5tsGyeXpAZfTnUZ/0iDpSmT1E+NJtZ0e6mhjEaC1oH6RcyG0twx4lPrUVdFQQ',
                'RHQFX4QadSDvgexRTswVnGkmgJaFEu9Me5eTHFQ00aT1bHaapRfPLZVF2G3HTDoA9RdbBS9p2eMTC0SgpQszWlDkmdN7aP9aPj9ysHoXPzX3f47pHY3cLK20KgslgVEJYn8UR38BgGLxCPOUWP56sa0oGNmG1Hl3T9jYULqAiJg=',
                'fail'
            ],//支付回调失败
        ];

    }

}