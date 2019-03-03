<?php
namespace org_mapu_themis_rop_model;
use Exception as Exception;
/**
* 客户端请求体.抽像类
* @edit yfx 2015.03.10
*/
abstract class RichServiceRequest{
	//请求的版本号
	abstract function getVersion();
	//请求的方法
	abstract function getMethod();
	
	/**
	 * 默认的验证方法
	 * */
	function validate(){
		return true;
	}
	
	public $contentType="application/x-www-form-urlencoded; charset=UTF-8";
	//请求的返回处理类
	//function getResponseClass();
	
	function getObject2Array(){
		$_array = is_object($this) ? get_object_vars($this) : $this;
		$array=array();
		$cContentType=false;
		foreach ($_array as $key => $value) {
			if((!$cContentType)&&is_a($value,'org_mapu_themis_rop_model\UploadFile')){
				$this->contentType="multipart/form-data; boundary=".UploadFile::$boundary;
				$value->uploadStr=base64_encode($value->fileName)."@".base64_encode($value->content);
				//url安全转换
				$value->uploadStr=str_replace(array('+','/','='),array('-','_',''),$value->uploadStr);
				//清空文件内容
				$value->content=null;
			}
			if($key!="contentType"){
				$array[$key] = $value;
			}
		}
		return $array;
	}
	
	/**
	 * 处理无效字符串
	 **/
	static function trim($str){
		if($str==null){
			if(is_numeric($str)){
				return '0';
			}
			return '';
		}else{
			return trim($str.'');
		}
	}
}
?>
