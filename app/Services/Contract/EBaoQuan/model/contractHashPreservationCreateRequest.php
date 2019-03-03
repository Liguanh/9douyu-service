<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/hashBasedPreservationCreateRequest.php';
use org_mapu_themis_rop_model\HashBasedPreservationCreateRequest as HashBasedPreservationCreateRequest;
use Exception as Exception;
/**
 * 客户端请求体
 * 合同保
 * @edit yfx 2015.06.09
 */
class ContractHashPreservationCreateRequest extends HashBasedPreservationCreateRequest{
	static $v="1.0";
	static $method="contract.hash.preservation.create";
	
	/**
	 * 合同编号
	 */
	public $contractNumber;
	
	/**
	 * 合同金额
	 */
	public $contractAmount;
	
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
	
	function validate(){
		if($this->contractNumber==null||trim($this->contractNumber)==''){
			throw new Exception("contractNumber is null");
		}
		if($this->contractAmount==null||trim($this->contractAmount)==''){
			throw new Exception("contractAmount is null");
		}
		parent::validate();
		return true;
	}
}
?>
