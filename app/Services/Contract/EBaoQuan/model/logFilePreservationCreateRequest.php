<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/fileBasedPreservationCreateRequest.php';
use org_mapu_themis_rop_model\FileBasedPreservationCreateRequest as FileBasedPreservationCreateRequest;
/**
 * 客户端请求体
 * 日志文件保全
 * @edit yfx 2015.06.09
 */
class LogFilePreservationCreateRequest extends FileBasedPreservationCreateRequest{
	
	static $v="1.0";
	static $method="log.file.preservation.create";
	
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
