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

    public function testDecrypt($method,$driver,$acctName,$bankCode,$dtOrder,$idNo,$idType,$infoOrder,$moneyOrder,$noAgree,$noOrder
        ,$oidPartner,$oidPaybill,$payType,$resultPay,$settleData,$sign,$decryptType,$expected){

        $url = 'http://service.9douyu.com/recharge';

        $postData = [
            "method" => $method,
            "driver" => $driver,
            "acct_name"=>$acctName,
            "bank_code"=>$bankCode,
            "dt_order"=>$dtOrder,
            "id_no"=>$idNo,
            "id_type"=>$idType,
            "info_order"=>$infoOrder,
            "money_order"=>$moneyOrder,
            "no_agree"=>$noAgree,
            "no_order"=>$noOrder,
            "oid_partner"=>$oidPartner,
            "oid_paybill"=>$oidPaybill,
            "pay_type"=>$payType,
            "result_pay"=>$resultPay,
            "settle_date"=>$settleData,
            "sign"=>$sign,
            "decrypt_type" => 'notice',
            "sign_type"=>"MD5"
        ];

        $args['expected'] = $expected;


        $this->postRequest($url, $postData,array(), array(), $args);

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
        $callBack = [$this,'searchResponse'];


        $this->postRequest($url, $postData,$callBack, array(), $args);
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
            ['encrypt','YeeAuth','6277830204430492',82692,3,'张爽','410004198005080338','http://www.wlask.com/notify.php','http://www.wlask.com/notify.php','pc','JDY_201605311632074475',200],     //正确的数据
        ];
    }

    /**
     * @return array
     * 易宝查单接口
     */
    public function searchList(){
        return [
            ['search','LLAuth',201606011336236337,'unknow'],     //本地无法调用连连接口
            ['search','LLAuth',201606011156334618,'unknow'],     //本地无法调用连连接口
            ['search','LLAuth',201606010748354030,'unknow'],     //本地无法调用连连接口

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
                "decrypt",
                "LLAuth",
                "宫海波",
                "01020000",
                "20160601093208",
                "370521198706042018",
                "0",
                "九斗鱼在线充值",
                "2000.0",
                "2016032839834369",
                "201606010932082274",
                "201409121000024506",
                "2016060162754963",
                "D",
                "SUCCESS",
                "20160601",
                "ada0d27f004b25f555bd3119052fa757",
                "notice",
                'success'
            ],
            [
                "decrypt",
                "LLAuth",
                "宫海波",
                "01020000",
                "20160601093208",
                "370521198706042018",
                "0",
                "九斗鱼在线充值",
                "2000.0",
                "201603283983469",
                "2016060109320874",
                "201409121000024506",
                "2016060162754963",
                "D",
                "SUCCESS",
                "20160601",
                "ada0d27f004b25f55bd3119052fa757",
                "notice",
                "fail"
            ]
        ];

    }

}