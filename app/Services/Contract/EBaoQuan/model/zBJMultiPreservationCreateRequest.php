<?php
namespace org_mapu_themis_rop_model;
require_once dirname(__FILE__).'/../model/multiHashBasedPreservationCreateRequest.php';
require_once dirname(__FILE__).'/../model/enum.php';
use org_mapu_themis_rop_model\BusinessType as BusinessType;
use Exception as Exception;
/**
 * 客户端请求体
 * 猪八戒保全
 * @edit yfx 2015.10.23
 */
class ZBJMultiPreservationCreateRequest extends MultiHashBasedPreservationCreateRequest{
	static $v="1.0";
	static $method="zbj.multi.hash.preservation.create";
	
	static $DATA_TYPE_SHA1_MUTI = 3;//多文件sha1文本保全:shaValue|shaValue|shaValue|shaValue....
	static $DATA_TYPE_MD5_MUTI = 4;//多文件md5文本保全:md5Value|md5Value|md5Value|md5Value.... 
	/**
	 * 需求号
	 */
	public $demandNumber;
	
	/**
	 *稿件号
	 */
	public $manuscriptNumber;
	
	/**
	 *案例号
	 */
	public $casusNumber;
	
	/**
	 *保全数据类型3 sha1Muti,4 md5Muti
	 */
	public $preservationDataType;
	
	/**
	 *封面图片
	 */
	public $coverImg;
	
	/**
	 *原创类型1稿件2案例
	 */
	public $businessType;
	
	function withManuscript($manuscriptNumber,$demandNumber){
		$this->businessType=BusinessType::$MANUSCRIPT_WORKS;
		$this->manuscriptNumber=$manuscriptNumber;
		$this->demandNumber=$demandNumber;
		return $this;
	}
	
	function withCasus($casusNumber){
		$this->businessType=BusinessType::$CASUS_WORKS;
		$this->casusNumber=$casusNumber;
		return $this;
	}
	
	//请求的版本号
	function getVersion(){
		return self::$v;
	}
	
	//请求的方法
	function getMethod(){
		return self::$method;
	}
	
	function validate(){
		if($this->businessType==null){
			throw new Exception("originalType is null");
		}
		if($this->preservationDataType==null){
			throw new Exception("preservationDataType is null");
		}
		if($this->businessType==BusinessType::$MANUSCRIPT_WORKS){
			if($this->manuscriptNumber==null||trim($this->manuscriptNumber)==''){
				throw new Exception("manuscriptNumber is null");
			}
			if($this->demandNumber==null||trim($this->demandNumber)==''){
				throw new Exception("demandNumber is null");
			}
		}else if($this->businessType==BusinessType::$CASUS_WORKS){
			if($this->casusNumber==null||trim($this->casusNumber)==''){
				throw new Exception("casusNumber is null");
			}
		}else{
			throw new Exception("originalType is not in [5,6]");
		}
		if($this->preservationDataType!=self::$DATA_TYPE_MD5_MUTI&&$this->preservationDataType!=self::$DATA_TYPE_SHA1_MUTI){
			throw new Exception("preservationDataType is not in [DATA_TYPE_MD5_MUTI,DATA_TYPE_SHA1_MUTI]");
		}
		parent::validate();
		return true;
	}
}
?>
