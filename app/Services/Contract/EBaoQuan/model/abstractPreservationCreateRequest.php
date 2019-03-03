<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/richServiceRequest.php';
use org_mapu_themis_rop_model\RichServiceRequest as RichServiceRequest;
use Exception as Exception;
/**
*保全客户端请求体.抽像类
*@edit yfx 2015.03.10
*/
abstract class AbstractPreservationCreateRequest extends RichServiceRequest{
	/**
	 * 用户标识信息（必填）
	 */
	public $userIdentifer;
	
	/**
	 * 用户真实姓名（必填）
	 */
	public $userRealName;
	
	/**
	 * 用户标识类型（必填）
	 */
	public $userIdentiferType;
	
	/**
	 * 保全类型（必填）
	 */
	public $preservationType;
	
	/**
	 * 保全标题（必填）
	 */
	public $preservationTitle;
	
	/**
	 * 来源处注册用户ID（必填）
	 */
	public $sourceRegistryId;
	
	/**
	 * 用户邮箱(有则填写)
	 */
	public $userEmail;
	
	/**
	 * 用户手机(有则填写)
	 */
	public $mobilePhone;
	
	/**
	 * 保全组第三方关联ID（有则填写）
	 */
	public $objectId;
	
	function validate(){
		$this->userIdentiferType=$this->trim($this->userIdentiferType);
		if($this->userIdentiferType==''){
			throw new Exception("userIdentiferType is null");
		}
		if($this->userIdentifer==null||trim($this->userIdentifer)==''){
			throw new Exception("userIdentifer is null");
		}
		if($this->userRealName==null||trim($this->userRealName)==''){
			throw new Exception("userRealName is null");
		}
		if($this->preservationTitle==null||trim($this->preservationTitle)==''){
			throw new Exception("preservationTitle is null");
		}
		if($this->sourceRegistryId==null||trim($this->sourceRegistryId)==''){
			throw new Exception("sourceRegistryId is null");
		}
		parent::validate();
		return true;
	}
}
?>
