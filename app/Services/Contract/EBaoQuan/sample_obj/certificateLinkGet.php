<?php
	require_once dirname(__FILE__).'/../tool/shaUtils.php';
	require_once dirname(__FILE__).'/../tool/ropUtils.php';
	require_once dirname(__FILE__).'/../model/certificateLinkGetRequest.php';
	use org_mapu_themis_rop_tool\RopUtils as RopUtils;
	use org_mapu_themis_rop_tool\ShaUtils as ShaUtils;
	use org_mapu_themis_rop_model\CertificateLinkGetRequest as CertificateLinkGetRequest;
	//组建请求参数
	$requestObj=new CertificateLinkGetRequest();
	$requestObj->preservationId=32;
	
	print_r("request.format:</br>");
	var_dump($requestObj); //null
	//请求
	$response=RopUtils::doPostByObj($requestObj);

	//以下为返回的一些处理
	$responseJson=json_decode($response);
	print_r("response:".$response."</br>");
	print_r("format:</br>");
	var_dump($responseJson); //null
	if($responseJson->success){
		echo $requestObj->getMethod()."->处理成功，详情如下：</br>";
		echo "link联接地址->".$responseJson->link."</br>";
		echo "linkExpireTime失效时间（毫秒级）->".$responseJson->linkExpireTime."</br>";
		echo "success返回状态->".$responseJson->success."</br>";
	}else{
		echo $requestObj->getMethod()."->处理失败";
	}
	
?>