<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/31
 * Time: 20:19
 */

return [

    'HNAPAY_CONFIG' => array(
        'version'         => '2.6',
        'displayName'     => '九斗鱼',
        'goodsName'       => '网银在线充值',
        'type'            => '1000',
        'payType'         => 'BANK_B2C',
        'currencyCode'    => '1',
        'directFlag'      => '1',
        'borrowingMarked' => '0',
        'couponFlag'      => '1',
        'platformID'      => '',
        'returnUrl'       => '/pay/return/platform/hnapay',
        'partnerID'       => '11000000701',
        'remark'          => 'extend',
        'buyerMarked'     => '',
        'charset'         => '1',
        'signType'        => '2',
        'payUrl'          => 'https://www.hnapay.com/website/pay.htm',
        'searchUrl'       => 'https://www.hnapay.com/website/queryOrderResult.htm',
        'pkey'            => '402dc848f35de6fabc5f1a8abcef56d0f54bb798cee70ff441d7d3097170a6f1842945356cbddc4ce0d753b0c46549f7c9f8abcd2e9596b9b7c296e3e1ae9dc0286f38f6499570437169ea3f4c533898bfcb4fe6971383902d2d99e2c78aa0f69a0c5fc89b04442312bfa5cb5c1577de6842c999f9f0a0e19a09defd127665f2a848c6fba2355aefcb9126f69ad1ecda241e6ba78d78a68a7d6c1f9e34cd7e895f07'
    ),

    //网银在线支付配置参数
    'CBPAY_CONFIG' => array(
        //'PARTNERID'       => '22978666',   //旧的商户ID
        'PARTNERID'       => '110184776001',   //新的商户ID，费率较低
        'KEY'             => 'GukqnVljhbvretgmbQFlze5zpDiiugay',
        'API_GATEWAY'     => 'https://tmapi.jdpay.com/PayGate ',
        'CHECK_ORDER_URL' => 'https://pay3.chinabank.com.cn/receiveorder.jsp',
        'MONEYTYPE'       => 'CNY',
        'NOTICEURL'       =>'/pay/noticeCbpay',
        //'RETURNURL'       =>'/pay/returnCbpay',
        'RETURNURL'      => '/pay/return/platform/cbpay'
    ),

    //连连认证支付配置参数
    'LLPAY_CONFIG' => array(
        'OID_PARTNER'     => '201409121000024506',//商户ID
        'KEY'             => 'jdyM2014@sdk09123',
        'VERSION'         => '1.0',
        'SIGN_TYPE'       => 'MD5',
        'CHECK_BANK_URL'  => 'https://yintong.com.cn/traderapi/userbankcard.htm',
        'BANK_CARD_QUERY' => 'https://yintong.com.cn/traderapi/bankcardquery.htm',
        #'BANK_CARD_QUERY' => 'https://queryapi.lianlianpay.com/bankcardbin.htm',
        'QUERY_VERSION'   => '1.1',
        'PCBANK'          => array(
            'VERSION'         => '1.0',
            'API_GATEWAY'     => 'https://yintong.com.cn/payment/bankgateway.htm',
            //'CHECK_ORDER_URL' => 'https://yintong.com.cn/traderapi/orderquery.htm',
            //'CHECK_BANK_URL'  => 'https://yintong.com.cn/traderapi/userbankcard.htm',
            'NOTICEURL'       => '/user/pay/toNotice/platform/llpay',
            'RETURNURL'       => '/user/pay/toReturn/platform/llpay',
        ),
        'PCAUTH'          => array(
            #'API_GATEWAY'     => 'https://yintong.com.cn/payment/authpay.htm',
            #'CHECK_ORDER_URL' => 'https://yintong.com.cn/traderapi/orderquery.htm',
            'API_GATEWAY'     => 'https://cashier.lianlianpay.com/payment/authpay.htm',
            'CHECK_ORDER_URL' => 'https://queryapi.lianlianpay.com/orderquery.htm',
            'CHECK_BANK_URL'  => 'https://yintong.com.cn/traderapi/userbankcard.htm',
            'NOTICEURL'       => '/user/pay/toNotice/platform/llauthpay',
            'RETURNURL'       => '/user/pay/toReturn/platform/llauthpay',
        ),
        'WAPAUTH'         => array(
            'VERSION'     => '1.1',
            #'API_GATEWAY' => 'https://yintong.com.cn/llpayh5/authpay.htm',
            'API_GATEWAY' => 'https://wap.lianlianpay.com/authpay.htm',
            //'NOTICEURL'   => '/pay/toNotice/platform/llwappay',
            'NOTICEURL'   => '/user/pay/toNotice/platform/llauthpay',
            'RETURNURL'   => '/pay/toReturn/platform/llwappay',
        ),
        'APPAUTH'         => array(
            'NOTICEURL'   => '/user/pay/toNotice/platform/llauthpay',
        ),
    ),

    //钱袋宝认证支付配置参数
    'QDBPAY_CONFIG' => array(
        'userNo'            => '90010000022',
        'privateKey'        => '/app/Services/Pay/WithHold/Qdb/cert/rsa_private_key.pem',
        'publicKey'         => '/app/Services/Pay/WithHold/Qdb/cert/rsa_public_key.pem',
        'charset_name'      => 'utf-8',
        'signUrl'           => 'https://qpay.qiandai.net/QdbPayWithhold/createWithholdingOrderAndSendVaildCode.do',
        'sendCodeUrl'       => 'https://qpay.qiandai.net/QdbPayWithhold/sendValidCode.do',
        'payUrl'            => 'https://qpay.qiandai.net/QdbPayWithhold/commitWithholding.do',
        'searchUrl'         => 'https://qpay.qiandai.net/QdbPayWithhold/queryWithholdResult.do',
        'sign_type'         => 'RSA',
        'cardType'          => 1,
        'credentialsType'   => '01',
        'description'       => '九斗鱼快捷充值',
    ),

    //易宝认证支付配置参数
    'YEEPAY_CONFIG' => array(
        'CHECKORDER'        => 'https://ok.yeepay.com/merchant/query_server/pay_single',
        'ACCOUNT'           => '10012431805',//商户ID
        'PRIVATEKEY'        => 'MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAJ1d30/uPJNEac3yPg8vnD/wa+20J/Gw52LfYHoZJFYdWJfd4vHhy61FGcUnIFQ5+nApRyYmQ+1mYzCVzgNMD8Fl7CAc9NoUUoOgmXidlBcJ6HYBAL0obN2OemjBcTJe1BkRBuPISROaqgs32ljGRZGNfCf44bDB+wbRv8Hn7dH9AgMBAAECgYBybIBk2UCYgGVCh4KSfjbp0wJXS+jDd7M3lF7NSRy/tQTnIELVSC5WJemQDlwWgjXUel8uoSJBK3KyMCslAJhvDKhNqbT9OvLSm7FKkOuKdoTQBFYC1T5EmTTMvbtsOnphU5dLA0CRrYDVb62l4MjBxfwDFoLdzHzU/EjUBOJfIQJBAOHK9rC3EM8HmD04ZEJVeSZRpvHqpjQe/xUuVh5SsA7ywLdgmyMbqof7HMa0TUVjkiE25XLDtoueZMpZjD/ZjtUCQQCya2zKLlSwhrgwS7lH6Np3HN5SChvblLZy19G/z6EyeaAtHyYjCJAntm5o4WFX0ITil/8KkBSi2tP5LLyK49qJAkASAikYwRETIgzvXQ8KB10pRDvncYqd/5birpZpxriKCKx8M7VL4IoCXHHYG0tKbH2cLo+wTpHBovlw9iFsekKdAkBLppj+MR7fqn+2mqT5BJZ9ItRxXK/rLucdUr0w40yqJj/wYeC9wge9jvDJr6aioVt26JPPWsAlPTvbz0gya+JhAkBx1Qc0i8PqKOEShp+kv1miVOUNtkaQxEp/UeroSg1KB/y0+ncQ+uPq0hg5sWxkXnfLWjYlJPGXL47udkjrHr4T',//商户ID
        'PUBLICKEY'         => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCdXd9P7jyTRGnN8j4PL5w/8GvttCfxsOdi32B6GSRWHViX3eLx4cutRRnFJyBUOfpwKUcmJkPtZmMwlc4DTA/BZewgHPTaFFKDoJl4nZQXCeh2AQC9KGzdjnpowXEyXtQZEQbjyEkTmqoLN9pYxkWRjXwn+OGwwfsG0b/B5+3R/QIDAQAB',//商户ID
        'YEEPAYPUBLICKEY'   => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDYU7ipdKvfjTUO8MZ4n4X/V1c18FS8JeIuhIwiLUHj7Ig1EfiTR6Rxb8OdvvfWlDTsahHv7xLAQ9nffBbzU9mnuIh0VwDgVxu01qHJ5q1kmZcD6Bzre7UHulddwxudseYyUgc7/V2XOkfohGYWAe3iInvG9eIfpJnl98B6nsXQBQIDAQAB',//商户ID
        'PRODUCTNAME'       => '九斗鱼 在线充值',
        'PRODUCTDESC'       => '在线充值',
        'PAYTYPES'          => '1',
        'PRODUCTCATALOG'    => '18',//商品类编码是我们业管根据商户业务本身的特性进行配置的业务参数。
        'IDENTITYTYPE'      => 0,//支付身份标识类型码
        'TERMINALTYPE'      => 3,
        'IDCARDTYPE'        => "01",
        'CURRENCY'          => 156,
        'ORDEREXPDATE'      => 60,
        'NOTICEURL'         => '/user/pay/toNotice/platform/yeeauthpay',
        'RETURNURL'         => '/user/pay/toReturn/platform/yeeauthpay',
        'WAPRETURNURL'      => '/yeePay/toReturn/platform/yeeauthpay',
        'APPRETURNURL'      => '/yeePay/toReturn/platform/yeeauthpay/from/app',
    ),
    'UMPAY_CONFIG' => array(
        'service'           => 'debit_direct_pay',
        'notify_url'         => '/user/pay/toNotice/platform/umpay',
        'amt_type'          => 'RMB',
        'pay_type'          => 'DEBITCARD',
        'identity_type'     => 'IDENTITY_CARD',
        'goods_id'          => '',
        'goods_inf'         => '',
        'media_id'          => '',
        'media_type'        => '',
        'settle_date'       => '',
        'mer_priv'          => '',
        'expand'            => '',
        'expire_time'       => '',
        'risk_expand'       => '',
        'charset'           => 'UTF-8',
        'mer_id'            => '8765',
        'res_format'        => 'HTML',
        'version'           => '4.0',
        'sign_type'         => 'RSA',
    ),


    'BEST_CONFIG' => array(
        'signUrl'           =>'https://finance.bestpay.com.cn/fas/service/sign', //签约url
        'payUrl'            =>'https://finance.bestpay.com.cn/fas/service/signAgentReceiveToAccount', //代扣url
        'checkOrder'            =>'https://finance.bestpay.com.cn/fas/service/transIntegratedQuery', //代扣url
        'platCode'          => '0020000000006002', //长度16内的,平台号(对方提供)
        'custCode'          => "0000000000008005",         //长度64内的,发起客户号(对方提供)
        'currencyCode'      => 'RMB',//支持的币种
        'validateType'      => '01',//无扣费验证
        'netWorkNature'     => '公网',
        'userFullName'      => '耀盛汇融投资管理有限公司',
        'ebkType'           => '130',
        'payeeName'         => '耀盛汇融投资管理北京有限公司',
        'netWorkAreaCode'   => '430422',
        'bankCode'          => array(
            2=>866300,
            3=>866100,
            6=>866900,
            9=>867600,
            10=>867200,
            17=>865700,
            8=>866600,
            14=>866000,
            12=>866800,
            13=>867400,
            7=>867100
        ),
        'areaCode'          => '110000',//默认北京区域码
        'accountCode'       => '000100051486',
    ),

    //融宝支付配置
    'REAPAY_CONFIG' => array(
        'merchant_id'       => '100000001300212',
        'seller_email'      => 'weixin@9douyu.com',
        'privateKey'        => 'Lib/ORG/Util/ReaPay/cert/private.pem',
        'publicKey'         => 'Lib/ORG/Util/ReaPay/cert/public.pem',
        'apiKey'            => '44038972e37ba3c97b23f89f021b44210e11agdd9671dcfega8efdda694b4e84',
        'signUrl'           => 'http://api.reapal.com/fast/debit/portal',
        'payUrl'            => 'http://api.reapal.com/fast/pay',
        'cert_type'         => '01',
        'notify_url'        => '/user/pay/toNotice/platform/reapay',
        'online_pay_url'    => 'http://epay.reapal.com/portal?charset=utf-8',
        'online_return_url' => '/pay/return/platform/reapay',
        'sendCodeUrl'       => 'http://api.reapal.com/fast/sms',
        'selectOrderUrl'    => 'http://api.reapal.com/fast/search',
        'cardAuthUrl'       => 'http://reagw.reapal.com/reagw/bankcard/cardAuth.htm',
        'cardIdentifyUrl'   => 'http://api.reapal.com/fast/identify',
        'merchantIdCheckCard'=> '100000000236345',
        'apiKeyCheckCard'   => 'g4dae656472g13a0fdc198e9f71eb0eb611f9be4d5g3b0ae83bee01edad45e5c',
        'checkPublicKey'    => 'Lib/ORG/Util/ReaPay/check/public.pem',
        'checkPrivateKey'   => 'Lib/ORG/Util/ReaPay/check/private.pem',
        'certificateUrl'    => 'http://api.reapal.com/fast/certificate',//招行卡密接口
        'bindCardUrl'       => 'http://api.reapal.com/fast/cancle/bindcard',//解绑的接口
    ),

    'BFPAY_CONFIG' => [
        'version'               => '4.0.0.1',           //版本号
        'input_charset'         => 1,                   //字符集 固定选择值: 1、 2、 3 ;1 代表 UTF-8;  2 代表 GBK; 3 代表 GB2312;
        'language'              => 1,                   //固定值:1 1 代表中文
        'member_id'             => '836286',            //商户号
        'terminal_id'           => '29674',             //终端号
        'data_type'             => 'json',              //加密报文的数据类型（xml/json）
        'txn_type'              => '03311',             //交易类型
        'biz_type'              => '0000',              //接入类型 默认 0000 为储蓄卡支付
        'id_card_type'          => '01',                //身份证类型 固定值:01  ;01 认为身份证号
        'pc_pay_url'            => 'https://gw.baofoo.com/apipay/pc',      //pc 接口请求地址
        'wap_pay_url'           => 'https://gw.baofoo.com/apipay/wap',     //wap 接口请求地址
        'sdk_pay_url'           => 'https://gw.baofoo.com/apipay/sdk',     //sdk 接口请求地址
        'search_order'          => 'https://gw.baofoo.com/apipay/queryQuickOrder', //查单接口
        'private_key_password'  => '9douyu_123456',  //商户私钥证书密码
        'pfx_filename'          => '/app/Services/Pay/Auth/BF/cert/9douyu_baofoo_pri.pfx', //注意证书路径是否存在
        'cer_filename'          => '/app/Services/Pay/Auth/BF/cert/bfkey_836286@@29674.cer', //注意证书路径是否存在

    ],

    'UCFPAY_CONFIG' => [

        'version'   => '3.0.0',

        /**
         * 测试地址
         */
        'gateway'    => 'https://mapi.ucfpay.com/gateway.do',

        /**
         * RSA加密公钥
         */
        'public_key' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCD0pI4mvs1mvgK87b0WdTUMZcKAtYLAES0cKXgonGZmyUXIx2WQvqrKN9bn/sp8F2ey6tTlKpks05pdlZPxjlQ8WMINuaqogaP4JR8Nm7JOhWcQj+V/+hhy4LJ78CqSn/EvuMzJHkXMJC0ZVtQYQrwssOHc8vnMZk6tjJ5Elq/4QIDAQAB',

        /**
         * 商户号
         */
        'merchant_id' => 'M200002683',

        'card_type'   => '01',
        /**
         * 加密算法
         */
        'sec_id'      => 'RSA',

        'merchant_name' => '星果时代信息技术有限公司',

        'product_name'  => '认证充值'


    ],
    /**
     * @desc    丰付支付-配置信息
     * @author  @llper
     * @date    2017-01-10
     **/
    'SUMAPAY_CONFIG' => [
        'version'       => '3.0.0',
        'tradeCode'     => 'IFZ1000',
        'tradeCodeSend1'=> 'IFY0001',
        'tradeCodeSend2'=> 'IFY0002',
        'tradeCodePay'  => 'IFZ0001',
        'tradeProcess'  => '1110000250',         //商户代码-支付系统为外部系统生成唯一标示符
        #'tradeProcess' => 'fbp100091',         //商户代码-支付系统为外部系统生成唯一标示符
        #'totalBizType' => 'BIZ01106',          //业务类型
        'totalBizType'  => 'BIZ01101',          //业务类型
        'rePayTimeOut'  => 0,                   //是否允许重新支付 0 不允许
        'isNeedBind'    => 0,                   //是否需要绑定，   0 不 1 是

        'productId'     => 'jdyauthpay',        //产品ID
        'productName'   => 'JDY-PAY',           //产品名称
        'productNumber' => 1,                   //产品数量
        'goodsDesc'     => 'JDY-PAY',           //商品描述信息
        #'bizType'       => 'BIZ01106',          //产品业务类型
        'bizType'       => 'BIZ01101',          //产品业务类型
        'passThrough'   => '',                  //透传信息
        'merAcct'       => '1110000250',         //产品供应商的编码
        #'merAcct'       => 'fbp100091',         //产品供应商的编码
        'merKey'        => 'Pa54pXDDQRgq6OoD5VCMdgqNZiUKCihG',      //签名秘钥
        #'merKey'        => 'K3NZLCY5IWSQEF6DTXJRBVMAH9G827U4P',    //签名秘钥
        #测试环境
        #'gateway'       => 'https://fbtest.sumapay.com/sumapay/QuickPayInterface_noLogin',
        #'searchway'     => 'https://fbtest.sumapay.com/main/SearchOrderAction_merSingleQuery',
        #正式环境
        'gateway'       => 'https://www.sumapay.com/sumapay/QuickPayInterface_noLogin',
        'searchway'     => 'https://www.sumapay.com/main/SearchOrderAction_merSingleQuery',
        #网银支付
        'API_GATEWAY'   => 'https://www.sumapay.com/sumapay/pay_bankPayForNoLoginUser',
        'encode'        => 'GBK',
    ],

];