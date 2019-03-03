<?php
	require_once dirname(__FILE__).'/../tool/shaUtils.php';
	require_once dirname(__FILE__).'/../tool/ropUtils.php';
	require_once dirname(__FILE__).'/../model/pingRequest.php';
	use org_mapu_themis_rop_tool\RopUtils as RopUtils;
	use org_mapu_themis_rop_tool\ShaUtils as ShaUtils;
	use org_mapu_themis_rop_model\PingRequest as PingRequest;
	
	//
	//$sha512=ShaUtils::getSha512(file_get_contents("http://account.ebaoquan.dev:8080/resources/img/logos.png"));
	//print_r($sha512."</br>");
	//组建请求参数
	$requestObj=new PingRequest();
	//is_subclass_of 可以查多层继承情况
	//echo is_subclass_of($requestObj,'org_mapu_themis_rop_model\RichServiceRequest').'<br/>';
	//请求
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