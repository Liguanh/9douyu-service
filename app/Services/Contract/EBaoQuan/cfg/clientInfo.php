<?php
namespace org_mapu_themis_rop_cfg;
/**
 * 客户端配置信息
 * @author yfx 2015-03-10
 *
 */

class ClientInfo{
	/**
	 * 沙箱和正式环境的app_key、app_secret、services_url均不同 
	 * 前期测试请联系技术支持申请沙箱的：app_key、app_secret
	 * 生产上不要用于测试，请使用正规内容进行保全api操作。
	 *
	 * 测试
	 * APP_KEY：f3d0e4ed45c64576
	 * APP_SECRET：506ea6bcc0bee72fb6a8512b71db97ca
	 * SERVICE_URL：http://sandbox.api.ebaoquan.org/services
	 *
	 * */
	//服务商服务地址
	static $services_url;
	//app_key对应从服务商申请到的appkey
	static $app_key;
	//appkey对应的密钥,客户使用,不能公开
	static $app_secret;

	public function __construct($config)
	{

		self::$services_url		= $config['services_url'];
		self::$app_key			= $config['app_key'];
		self::$app_secret 		= $config['app_secret'];

	}

}
?>
