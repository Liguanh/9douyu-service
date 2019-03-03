<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/richServiceRequest.php';
use org_mapu_themis_rop_model\RichServiceRequest as RichServiceRequest;
use Exception as Exception;
/**
*获取关联保全信息
*@edit yfx 2015.04.28
*/
class GroupPreservationGetRequest extends RichServiceRequest{

	static $v="1.0";
	static $method="group.preservation.get";
	
	/**
	 * 保全组第三方ID
	 */
	public $objectId;
	
	/**
	 * 保全人的唯一身份信息（身份证/企业营业执照）
	 */
	public $identifier;
	
	//请求的版本号
	function getVersion(){
		return self::$v;
	}
	//请求的方法
	function getMethod(){
		return self::$method;
	}
	
	function validate(){
		if($this->objectId==null||trim($this->objectId)==''){
			throw new Exception("objectId is null");
		}
		if($this->identifier==null||trim($this->identifier)==''){
			throw new Exception("identifier is null");
		}
		parent::validate();
		return true;
	}
}
?>
