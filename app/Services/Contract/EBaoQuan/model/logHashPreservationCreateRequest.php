<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/hashBasedPreservationCreateRequest.php';
use org_mapu_themis_rop_model\HashBasedPreservationCreateRequest as HashBasedPreservationCreateRequest;
/**
 * 客户端请求体
 * 日志保全
 * @edit yfx 2015.06.09
 */
class LogHashPreservationCreateRequest extends HashBasedPreservationCreateRequest{
	static $v="1.0";
	static $method="log.hash.preservation.create";
	
	/**
	 * 交易备注（可选）
	 */
	public $comments;
	
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
