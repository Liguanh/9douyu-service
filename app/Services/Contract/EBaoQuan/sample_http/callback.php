<?php
	require_once dirname(__FILE__).'/../tool/httpSignUtils.php';
	require_once(dirname(__FILE__).'/../cfg/clientInfo.php');
	use org_mapu_themis_rop_tool\HttpSignUtils as HttpSignUtils;
	use org_mapu_themis_rop_cfg\ClientInfo as ClientInfo;
	$result=array(
			"resultCode"=>"",
			"msg"=>"",
			"success"=>true,
	);
	if(!isset($_REQUEST['preservationId'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="preservationId is null";
		$result['success']=false;
		echo(json_encode($result,JSON_UNESCAPED_UNICODE));
		exit(0);
	}
	if(!isset($_REQUEST['checkTime'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="checkTime is null";
		$result['success']=false;
		echo(json_encode($result,JSON_UNESCAPED_UNICODE));
		exit(0);
	}
	if(!isset($_REQUEST['timestamp'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="timestamp is null";
		$result['success']=false;
		echo(json_encode($result,JSON_UNESCAPED_UNICODE));
		exit(0);
	}
	if(!isset($_REQUEST['sign'])){
		$result['resultCode']="jsonParamsError";
		$result['msg']="sign is null";
		$result['success']=false;
		echo(json_encode($result,JSON_UNESCAPED_UNICODE));
		exit(0);
	}
	
	$preservationId=$_REQUEST['preservationId'];
	$checkTime=$_REQUEST['checkTime'];
	$timestamp=$_REQUEST['timestamp'];
	$sign=$_REQUEST['sign'];
	$bodyParams=array(
			'preservationId'=>$preservationId,
			'checkTime'=>$checkTime
	);
	try {
		HttpSignUtils::checkHttpSign($bodyParams, $timestamp, ClientInfo::$app_key, ClientInfo::$app_secret, $sign);
	} catch (Exception $e) {
		$result['resultCode']="signError";
		$result['msg']=$e->getMessage();
		$result['success']=false;
	}
	if($result['success']){
		//TODO 做自个的业务相关处理
		
	}
	echo(json_encode($result,JSON_UNESCAPED_UNICODE));
?>