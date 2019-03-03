<?php

namespace org_mapu_themis_rop_model;

require_once dirname ( __FILE__ ) . '/../model/richServiceRequest.php';
use org_mapu_themis_rop_model\RichServiceRequest as RichServiceRequest;
use Exception as Exception;
/**
 * 保全转移
 * @edit yfx 2015.04.28
 */
class PreservationTransferRequest extends RichServiceRequest {
	static $v = "1.0";
	static $method = "preservation.transfer";
	
	/**
	 * 保全ID 必填
	 */
	public $preservationId;
	
	/**
	 * 受让用户标识信息 必填
	 */
	public $userIdentifer;
	
	/**
	 * 受让用户真实姓名 必填
	 */
	public $userRealName;
	
	/**
	 * 受让用户标识类型 必填
	 */
	public $userIdentiferType;
	
	/**
	 * 受让3方唯一标识 必填
	 */
	public $sourceRegistryId;
	
	/**
	 * 受让用户邮箱 选填
	 */
	public $userEmail;
	
	/**
	 * 受让用户手机 选填
	 */
	public $mobilePhone;
	
	// 请求的版本号
	function getVersion() {
		return self::$v;
	}
	// 请求的方法
	function getMethod() {
		return self::$method;
	}
	function validate() {
		$this->preservationId = $this->trim ( $this->preservationId );
		if ($this->preservationId == '') {
			throw new Exception ( "preservationId is null" );
		}
		if ($this->userIdentifer == null || trim ( $this->userIdentifer ) == '') {
			throw new Exception ( "userIdentifer is null" );
		}
		if ($this->userRealName == null || trim ( $this->userRealName ) == '') {
			throw new Exception ( "userIdentiferType is null" );
		}
		if ($this->userIdentiferType == null || trim ( $this->userIdentiferType ) == '') {
			throw new Exception ( "preservationTitle is null" );
		}
		if ($this->sourceRegistryId == null || trim ( $this->sourceRegistryId ) == '') {
			throw new Exception ( "sourceRegistryId is null" );
		}
		parent::validate ();
		return true;
	}
}
?>
