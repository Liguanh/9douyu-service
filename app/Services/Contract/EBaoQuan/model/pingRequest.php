<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/richServiceRequest.php';
use org_mapu_themis_rop_model\RichServiceRequest as RichServiceRequest;
/**
 * ping 请求
 * @edit yfx 2015.03.10
 */
class PingRequest extends RichServiceRequest{
	
	static $v="1.0";
	static $method="ping";
	
	//请求的版本号
	function getVersion(){
		return self::$v;
	}
	//请求的方法
	function getMethod(){
		return self::$method;
	}
}
?>
