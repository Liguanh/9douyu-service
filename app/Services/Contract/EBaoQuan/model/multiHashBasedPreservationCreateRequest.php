<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/abstractPreservationCreateRequest.php';
use org_mapu_themis_rop_model\AbstractPreservationCreateRequest as AbstractPreservationCreateRequest;
use Exception as Exception;
/**
 * 客户端请求体
 * 多hash保全
 * @edit yfx 2015.10.23
 */
class MultiHashBasedPreservationCreateRequest extends AbstractPreservationCreateRequest{
	static $v="MULTI_HASH_1.0";
	static $method="preservation.create";
	
	//待保全的多个sha1信息为：fileName:hashValue:relevanceId|fileName:hashValue:relevanceId|....字符串
	public $fileInfos;
	
	//请求的版本号
	function getVersion(){
		return self::$v;
	}
	//请求的方法
	function getMethod(){
		return self::$method;
	}
	
	function validate(){
		if($this->fileInfos==null||trim($this->fileInfos)==''){
			throw new Exception("fileInfos is null");
		}
		parent::validate();
		return true;
	}
}
?>
