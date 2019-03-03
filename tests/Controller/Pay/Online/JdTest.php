<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/31
 * Time: 15:27
 */

class LLTest extends TestCase{


    /**
     * 获取用户的绑定银行卡
     * @dataProvider decryptList
     * @param $userId
     */
    
    public function testDecrypt($method,$driver,$moneyType,$sign,$desc,$orderId,$status,$bank,$remark,$cash,$notifyUrl,$expected){

        $url = 'http://service.9douyu.com/recharge';


        $postData = [
            'method'            => $method,
            'driver'            => $driver,
            'v_moneytype'       => $moneyType,
            'v_md5str'          => $sign,
            'v_pstring'         => $desc,
            'v_oid'             => $orderId,
            'v_pmode'           => $bank,
            'remark1'           => $remark,
            'v_amount'          => $cash,
            'v_pstatus'         => $status,
            'remark2'           => $notifyUrl

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
        $this->assertEquals($return['data']['trade_status'], $args['expected'], $return['msg']);

    }


    /**
     * @return array
     * 解密原始数据
     */
    public function decryptList(){

        return[
            //成功
            ['decrypt','JdOnline','CNY', 'FFD1EDC1C1C329C3BF182D011AD9A981', 'Ö§¸¶³É¹¦', 'JDY_201605310831226991', '20', 'CMB', '9douyu', '0.01', '[url:=http://www.wlask.com/notify.php]','success'],
            //失败
            ['decrypt','JdOnline','CNY', 'FFD1EDC1C1C329C3BF182D011AD9A981', 'Ö§¸¶³É¹¦', 'JDY_2016053108312269', '20', 'CMB', '9douyu', '0.01', '[url:=http://www.wlask.com/notify.php]','fail']

        ];

    }

}