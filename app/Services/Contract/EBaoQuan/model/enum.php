<?php
namespace org_mapu_themis_rop_model;
/**
*  保全类型枚举
*@edit yfx 2015.03.10
*/
class PreservationType {
	/**网页设计*/
	static $WEB_DESIGN=0;
	/**平面设计*/
	static $GRAPHIC_DESIGN=1;
	/**摄影作品*/
	static $PHOTOGRAPHIC_WORKS=2;
	/**视频作品*/
	static $VIDEO_WORKS=3;
	/**音乐作品*/
	static $AUDIO_WORKS=4;
	/**电子合同*/
	static $DIGITAL_CONTRACT=5;
	/**电子证据*/
	static $DIGITAL_EVIDENCE=6;
	/**保全中心客户端录像取证*/
	static $VIDEO_EVIDENCE=7;
	/**电子凭证*/
	static $DIGITAL_VOUCHER=8;
	/**原创作品*/
	static $DIGITAL_ORIGINAL=9;
	/**业务日志*/
	static $BUSINESS_LOGGING=10;
	/**风控信息*/
	static $RISK_CONTROL=11;
	/**"其它"*/
	static $OTHERS=9999;
}
/**
 *   用户身分证明类型枚举
 *@edit yfx 2015.03.10
 */
class UserIdentiferType{
	/**"个人身份证"*/
	static $PRIVATE_ID="0";
	/**"企业营业执照"*/
	static $BUSINESS_LICENSE="1";
}
/**
 * 交易对象类型枚举
 * @edit yfx 2015.06.09
 */
class TransactionEntityType{
	/**付款方*/
	static $CREDITOR="0";
	/**收款方*/
	static $DEBTOR="1";
}
/**
 * 凭证类型枚举
 * @edit yfx 2015.06.09
 */
class VoucherType{
	/**往来支付*/
	static $REMITTANCE="0";
	/**转账收入*/
	static $INCOME="1";
	/**转账支出*/
	static $DISBURSEMENT="2";
	/**充值交易*/
	static $DEPOSIT="3";
	/**提现交易*/
	static $WITHDRAW="4";
	/**其它*/
	static $OTHERS="9999";
}
/**
 * 账户类型
 * @edit yfx 2015.06.15
 */
class AccountType{
	/**银行账户*/
	static $BANK="0";
	/**易极付账户*/
	static $YJF="1";
	/**易宝支付账户*/
	static $YEEPAY="2";
	/**支付宝账号*/
	static $ALIPAY="3";
	/**宝付支付账户*/
	static $BAOFOO="4";
	/**融宝支付账户*/
	static $REEPAL="5";
	/**其他账户类型*/
	static $OTHER="9999";
}
/**
 * 原创类型
 * @edit yfx 2015.10.26
 */
class OriginalType{
	/**网页设计*/
	static $WEB_DESIGN="0";
	/**平面设计*/
	static $GRAPHIC_DESIGN="1";
	/**摄影作品*/
	static $PHOTOGRAPHIC_WORKS="2";
	/**视频作品*/
	static $VIDEO_WORKS="3";
	/**音乐作品*/
	static $AUDIO_WORKS="4";
	/**稿件*/
	static $MANUSCRIPT_WORKS="5";
	/**案例*/
	static $CASUS_WORKS="6";
	/**其它*/
	static $OTHERS="9999";
}
/***/
class BusinessType{
	/**稿件*/
	static $MANUSCRIPT_WORKS="1";
	/**案例*/
	static $CASUS_WORKS="2";
	/**其它*/
	static $OTHERS="9999";
}
?>
