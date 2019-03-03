<?php
	require_once dirname(__FILE__).'/../tool/shaUtils.php';
	require_once dirname(__FILE__).'/../tool/ropUtils.php';
	require_once dirname(__FILE__).'/../model/contractStatusGetRequest.php';
	use org_mapu_themis_rop_tool\RopUtils as RopUtils;
	use org_mapu_themis_rop_tool\ShaUtils as ShaUtils;
	use org_mapu_themis_rop_model\ContractStatusGetRequest as ContractStatusGetRequest;
	//组建请求参数
	$requestObj=new ContractStatusGetRequest();
	//init params
	$requestObj->preservationId=64;
	//请求
	$response=RopUtils::doPostByObj($requestObj);
	//echo stristr(strtolower("123456"),"6")==null;
	
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