<?php

namespace org_mapu_themis_rop_model;

require_once dirname ( __FILE__ ) . '/../model/abstractPreservationCreateRequest.php';
use org_mapu_themis_rop_model\AbstractPreservationCreateRequest as AbstractPreservationCreateRequest;
use Exception as Exception;
/**
 * 客户端请求体
 * hash保全
 * @edit yfx 2015.03.10
 */
class HashBasedPreservationCreateRequest extends AbstractPreservationCreateRequest {
	static $v = "HASH_1.0";
	static $method = "preservation.create";
	/**
	 * 特证值
	 */
	public $hashValue;
	
	// 请求的版本号
	function getVersion() {
		return self::$v;
	}
	// 请求的方法
	function getMethod() {
		return self::$method;
	}
	function validate() {
		if ($this->hashValue == null || trim ( $this->hashValue ) == '') {
			throw new Exception ( "hashValue is null" );
		}
		parent::validate ();
		return true;
	}
}
?>
