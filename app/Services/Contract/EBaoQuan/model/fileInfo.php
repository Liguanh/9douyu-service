<?php
namespace org_mapu_themis_rop_model;
/**
 * 文件简单信息 
 * @edit yfx 2015.10.23
 */
class FileInfo{
	/**文件名称*/
	public $fileName;
	/**关联id*/
	public $relevanceId;
	/**文件的hashValue*/
	public $hashValue;
	
	function say(){ 
		echo "fileName：".$this->fileName." relevanceId：".$this->relevanceId." hashValue：".$this->hashValue."<br/>"; 
	}
}
?>
