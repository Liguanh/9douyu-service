<?php
	require_once dirname(__FILE__).'/../tool/shaUtils.php';
	require_once dirname(__FILE__).'/../tool/ropUtils.php';
	require_once dirname(__FILE__).'/../model/contractFilePreservationCreateRequest.php';
	require_once dirname(__FILE__).'/../model/uploadFile.php';
	require_once dirname(__FILE__).'/../model/enum.php';
	use org_mapu_themis_rop_tool\RopUtils as RopUtils;
	use org_mapu_themis_rop_tool\ShaUtils as ShaUtils;
	use org_mapu_themis_rop_model\ContractFilePreservationCreateRequest as ContractFilePreservationCreateRequest;
	use org_mapu_themis_rop_model\UploadFile as UploadFile;
	use org_mapu_themis_rop_model\PreservationType as PreservationType;
	use org_mapu_themis_rop_model\UserIdentiferType as UserIdentiferType;

	//echo dirname(__FILE__).'/../model/contractFilePreservationCreateRequest.php';

	//组建请求参数
	//$filePath="E:\\tmp\\[9374_33431_f1922a]投资凭证.pdf";
	$filePath = dirname(__FILE__).'/../tool/九斗鱼享乐计划投资协议132519-1894-196645.pdf';

	//下面语句当系统为windows时，访问中文文件名的内容需转换，当前程序中的文件名从utf-8->gbk，再进行读取文件路径
	$fileName=RopUtils::getFileName($filePath);
	//$filePath =iconv("utf-8","gb2312//IGNORE", $filePath);
	//封装上传文件内容
	$file=new UploadFile();
	$file->content=file_get_contents($filePath);
	$file->fileName=$fileName;
	//初始化合同文件上传
	$requestObj=new ContractFilePreservationCreateRequest();
	//init保全请求参数
	$requestObj->file=$file;
	$requestObj->preservationTitle="XXX公司LOGO保全";
	
	$requestObj->userIdentifer="123123123123123";
	$requestObj->userRealName="张菲公司";
	$requestObj->userIdentiferType=UserIdentiferType::$BUSINESS_LICENSE;
	/**
	$requestObj->userIdentifer="5002199901016433";
	$requestObj->userRealName="张菲";
	$requestObj->userIdentiferType=UserIdentiferType::$PRIVATE_ID;
	 * */
	$requestObj->preservationType=PreservationType::$DIGITAL_CONTRACT;
	$requestObj->sourceRegistryId="243221434";
	$requestObj->userEmail="3811970@qq.com";
	$requestObj->mobilePhone="15320369150";
	$requestObj->contractAmount=200000.00;
	$requestObj->contractNumber="渝PCS-3247234";
	//$requestObj->objectId="0000001";//关联保全时使用
	$requestObj->comments="说明";
	//$requestObj->isNeedSign="0";
	//isNeedSign 这个参数如果为1是需要签名，则上传的文件必须是pdf文件，且服务端会将文件做签名后再保全.请->
	//使用保全contractFileDownloadUrl.php例子的使用方法得到合同保全的保全后文件的下载地址进行下载。（下载地址有时效性，过期后重新按此方法取得新地址）
	
	//请求服务器
	$response=RopUtils::doPostByObj($requestObj);
	//以下为返回的一些处理
	$responseJson=json_decode($response);
	print_r("response:".$response."</br>");
	print_r("format:</br>");
	var_dump($responseJson); //null
	if($responseJson->success){
		echo $requestObj->getMethod()."->处理成功";
	}else{
		echo $requestObj->getMethod()."->处理失败";
	}
?>