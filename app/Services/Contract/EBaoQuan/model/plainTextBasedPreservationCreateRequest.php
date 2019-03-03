<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/hashBasedPreservationCreateRequest.php';
use org_mapu_themis_rop_model\HashBasedPreservationCreateRequest as HashBasedPreservationCreateRequest;
use Exception as Exception;
/**
 * 基于文件的保全
 * @author yfx
 * @edit yfx 2015.04.28
 */
class PlainTextBasedPreservationCreateRequest extends HashBasedPreservationCreateRequest{
	static $v="PLAINTEXT_1.0";
	static $method="preservation.create";
	
	/**
	 * 保全文本
	 */
	public $text;
	
	//请求的版本号
	function getVersion(){
		return self::$v;
	}
	
	//请求的方法
	function getMethod(){
		return self::$method;
	}
	
	function validate(){
		if($this->text==null||trim($this->text)==''){
			throw new Exception("text is null");
		}
		parent::validate();
		return true;
	}
}
?>
