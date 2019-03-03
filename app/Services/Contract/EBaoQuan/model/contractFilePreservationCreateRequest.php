<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/fileBasedPreservationCreateRequest.php';
use org_mapu_themis_rop_model\FileBasedPreservationCreateRequest as FileBasedPreservationCreateRequest;
use Exception as Exception;
/**
 * 客户端请求体
 * 合同文件保全
 * @edit yfx 2015.06.09
 */
class ContractFilePreservationCreateRequest extends FileBasedPreservationCreateRequest{
	static $v="1.0";
	static $method="contract.file.preservation.create";
	
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
	
	/**
	 * 是否需要签名:否"0"是"1"。（可选）
	 */
	public $isNeedSign;
	
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
		if(isset($this->isNeedSign)){
			$this->isNeedSign = $this->trim ( $this->isNeedSign );
			if($this->isNeedSign!="0"&&$this->isNeedSign!="1"){
				throw new Exception("isNeedSign is not in(0,1)");
			}
		}
		parent::validate();
		return true;
	}
}
?>
