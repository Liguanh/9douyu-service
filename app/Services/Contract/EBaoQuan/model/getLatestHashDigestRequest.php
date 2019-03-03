<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/richServiceRequest.php';
use org_mapu_themis_rop_model\RichServiceRequest as RichServiceRequest;
use Exception as Exception;
/**
*得到最新的摘要链信息
*@edit yfx 2015.03.10
*/
class GetLatestHashDigestRequest extends RichServiceRequest{
	/**
	 * 获取哈希摘要的数量,默认1条
	 */
	public $size=1;
	
	static $v="1.0";
	static $method="digest.get.latest";
	
	//请求的版本号
	function getVersion(){
		return self::$v;
	}
	//请求的方法
	function getMethod(){
		return self::$method;
	}
	
	function validate(){
		$this->size=$this->trim($this->size);
		if($this->size==''||$this->size=='0'){
			throw new Exception("size is null");
		}
		parent::validate();
		return true;
	}
}
?>
