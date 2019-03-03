<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/richServiceRequest.php';
use org_mapu_themis_rop_model\RichServiceRequest as RichServiceRequest;
use Exception as Exception;
/**
 * 客户端请求体
 * 猪八戒保全转移
 * @edit yfx 2015.10.27
 */
class MultiTransferPreservationCreateRequest extends RichServiceRequest{
	static $v="1.0";
	static $method="multi.preservation.transfer";
	
	/**
	 * 保全IDs(多个保全id以|线分隔) 必填
	 */
	public $preservationIds;
	
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
	
	//请求的版本号
	function getVersion(){
		return self::$v;
	}
	
	//请求的方法
	function getMethod(){
		return self::$method;
	}
	
	//验证方法
	function validate($markType="rop"){
		if($this->preservationIds==null||trim($this->preservationIds)==''){
			throw new Exception("preservationIds is null");
		}
		//使用rop方式
		if($markType=='rop'){
			if($this->userIdentifer==null||trim($this->userIdentifer)==''){
				throw new Exception("userIdentifer is null");
			}
			if($this->userRealName==null||trim($this->userRealName)==''){
				throw new Exception("userRealName is null");
			}
			$this->userIdentiferType=$this->trim($this->userIdentiferType);
			if(trim($this->userIdentiferType)==''){
				throw new Exception("userIdentiferType is null");
			}
		}
		if($this->sourceRegistryId==null||trim($this->sourceRegistryId)==''){
			throw new Exception("sourceRegistryId is null");
		}
		parent::validate();
		return true;
	}
}
?>
