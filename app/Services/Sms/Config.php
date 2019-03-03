<?php

namespace App\Services\Sms;

class Config
{
   static $channel = array(
        'JzSms' => array(
            'NOTICE' => ['sdk_notify', '20150818', 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl'],
            'MARKET' => ['sdk_market8', '20150826', 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl'],
            'VERIFY' => ['sdk_notify', '20150818', 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl'],
        ),
        'EmaySms' => array(
            'NOTICE' => ['6SDK-EMY-6688-KGXNO', '735766', 'http://sdk4report.eucp.b2m.cn:8080/sdkproxy'],
            'MARKET' => ['6SDK-EMY-6666-REZSM', '094945', 'http://sdktaows.eucp.b2m.cn:8080/sdkproxy'],
            'VERIFY' => ['9SDK-EMY-0999-RFSNT', '959529', 'http://sdk999ws.eucp.b2m.cn:8080/sdkproxy'],
            /*'MARKET' => ['6SDK-EMY-6688-KGZSR', '064841', 'http://sdk4report.eucp.b2m.cn:8080/sdkproxy'],*/
            /*'VERIFY' => ['9SDK-EMY-0999-JFQNN', '411571', 'http://sdk999ws.eucp.b2m.cn:8080/sdkproxy'],*/
        ),
       'MdSms' => array(
           'MARKET' => ['SDK-BBX-010-24864', '62=d-b30', 'http://sdk2.entinfo.cn'],
           /*'MARKET' => ['6SDK-EMY-6688-KGZSR', '064841', 'http://sdk4report.eucp.b2m.cn:8080/sdkproxy'],*/
           /*'VERIFY' => ['9SDK-EMY-0999-JFQNN', '411571', 'http://sdk999ws.eucp.b2m.cn:8080/sdkproxy'],*/
       ),
       //沃动营销短信接口
       'WdSms' => array(
          // 'MARKET' => ['XGSD', '741852', 'http://218.244.136.70:8888'],
           'MARKET' => ['WEB-A1259-1259', '741852', 'http://client.movek.net:8888'],
       ),
       //美联验证码短信
       'MiSms' => array(
           'MARKET' => ['jiudouyu', 'asdf1234', 'http://m.5c.com.cn', '12841f2fb806fd3731a4a8d37f290076'],
           'VERIFY' => ['jiudouyu01', 'asdf123', 'http://m.5c.com.cn', '4157ae2d8ef465f6a88eff54abf00288'],
       ),
       //创蓝营销短信接口
       'ClSms' => array(
           //'MARKET' => ['BC2xingg', 'Xingg1688', 'http://222.73.117.169'],
           'MARKET' => ['M3653525', 'gHLwj2hmZy4b40', 'http://smssh1.253.com'],
           //'MARKET' => ['BK9882110', '1Zpjg0GQ2', 'http://vsms.253.com'],
       ),
       //大汉三通
       'StongSms'=> array(
           'MARKET' => ['dh36931', '3VupQPoc', 'http://wt.3tong.net/json/sms'],
           'VERIFY' => ['dh36932', 'f100ZmOh', 'http://wt.3tong.net/json/sms'],
       )
    );

    /**
     * @var array
     * @desc 流量银行的配置
     */
   static $flowChannel  =   [
        'DhFlow'    =>  [
            'FLOW'  =>  ['Adminxgsd' , 'Jdy201709' , 'http://hyif.dahanbank.cn/FCOrderNewServlet'],
            'CALLS' =>  ['Adminxgsd' , 'Jdy201709' , 'http://hyif.dahanbank.cn/FCPhoneBillOrderNewServlet']
        ],
   ];
}
