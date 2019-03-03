<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/abstractPreservationCreateRequest.php';
use org_mapu_themis_rop_model\AbstractPreservationCreateRequest as AbstractPreservationCreateRequest;
use Exception as Exception;
/**
 * 客户端请求体
 * 合同保
 * @edit yfx 2015.06.09
 */
abstract class FileBasedPreservationCreateRequest extends AbstractPreservationCreateRequest{

	/**
	 * 保全文件
	 */
	public $file;
	
	function validate(){
		if($this->file==null){
			throw new Exception("$file is null");
		}
		parent::validate();
		return true;
	}
}
?>
