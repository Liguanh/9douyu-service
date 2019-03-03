<?php
namespace org_mapu_themis_rop_model;
/**
*上传的文件体
*@edit yfx 2015.03.10
*/
class UploadFile{
	static $boundary="---------------------------7df27332f1714";
	
	/**文件类型*/
	public $fileType;
	/**文件名称*/
	public $fileName;
	/**文件内容*/
	public $content;
	/**编译后的待上传文本*/
	public $uploadStr;
	
	function say(){ 
		echo "fileType：".$this->fileType." fileName：".$this->fileName." content：".$this->content."<br>"; 
	} 
	
}
?>
