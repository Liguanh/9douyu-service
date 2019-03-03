<?php
//====================配置商户的宝付接口授权参数============================
$path = $_SERVER['DOCUMENT_ROOT'].'/CerPCDemo';
$pathcer = $path."/CER/";//证书路径配制

$member_id = "100000178";	//商户号
$terminal_id = "100000916";	//终端号
$data_type="json";//加密报文的数据类型（xml/json）
$txn_type = "03311";
$private_key_password = "100000178_204500";	//商户私钥证书密码
$pfxfilename = $pathcer."bfkey_100000178@@100000916.pfx";  //注意证书路径是否存在
$cerfilename = $pathcer."baofoo_pub.cer";//注意证书路径是否存在
if(!file_exists($pfxfilename)){
    die("私钥证书不存在！<br>");
}
if(!file_exists($cerfilename)){
    die("公钥证书不存在！<br>");
}
require_once($path."/BAOFOOSDK/BaofooSdk.php");
require_once($path."/BAOFOOSDK/SdkXML.php");
require_once($path."/BAOFOOSDK/Log.php");
require_once($path."/BAOFOOSDK/HttpClient.php");


