<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/richServiceRequest.php';
use org_mapu_themis_rop_model\RichServiceRequest as RichServiceRequest;
use Exception as Exception;
/**
 * 合同文件地址查看url获取
 * @edit yfx 2016.06.23
 */
class ContractFileViewUrlRequest extends RichServiceRequest{
	
	static $v="1.0";
	static $method="contract.file.view.url";
	/**保全ID|备案号*/
	public $preservationId;
	
	//请求的版本号
	function getVersion(){
		return self::$v;
	}
	//请求的方法
	function getMethod(){
		return self::$method;
	}
	
	function validate(){
		$this->preservationId=$this->trim($this->preservationId);
		if($this->preservationId==''){
			throw new Exception("preservationId is null");
		}
		parent::validate();
		return true;
	}
}
?>