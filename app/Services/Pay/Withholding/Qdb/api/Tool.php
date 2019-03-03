<?php
$basePath = base_path();
require_once($basePath."/app/Services/Pay/Withholding/Qdb/api/Crypt/RSA.php");
require_once($basePath."/app/Services/Pay/Withholding/Qdb/api/Crypt/FastJSON.php");

class QdbTool{

	const MAX_DECRYPT_BLOCK = '128';
	const MAX_ENCRYPT_BLOCK = '117';

	function __construct($config){
		$this->config = $config;
		$this->PRIVATE_KEY = file_get_contents(base_path().'/app/Services/Pay/Withholding/Qdb/cert/rsa_private_key.pem');
		$this->PUBLIC_KEY = file_get_contents(base_path().'/app/Services/Pay/Withholding/Qdb/cert/rsa_public_key.pem');

	}


	function send($url, $info) {

		$data = new stdClass ();
		$jsonObject = new stdClass ();
		$info =json_encode ( $info, JSON_UNESCAPED_UNICODE );
		$info =str_replace ( "\\","",$info );
		$info = json_decode ( json_encode ( $info, JSON_UNESCAPED_UNICODE ), true );

		$sign = $this->paramSign ( $info );
		$data->info = $info;
		$data->sign = $sign;
		$jsonObject->data = $this->private_encrypt ( $data );
		$jsonObject->charset_name = $this->config['charset_name'];
		$jsonObject->userNo = $this->config['userNo'];
		$jsonObject =json_encode ($jsonObject, JSON_UNESCAPED_UNICODE ) ;

		$returnMsg =  $this->http_POST ($url, $jsonObject );
		return $this->public_decrypt ( $returnMsg );

	}


	function decode($params){
		return json_decode($this->public_decrypt ( $params['data'] ),true);

	}
	function paramSign($info) {
		$rsa = new Crypt_RSA ();
		$rsa->setHash ( 'md5' );
		$rsa->loadKey ( $this->PRIVATE_KEY );
		$rsa->setSignatureMode ( CRYPT_RSA_SIGNATURE_PKCS1 );
		$signature = $rsa->sign ( $info );
		return base64_encode ( $signature );
	}

	/*
//私钥加密
	function private_encrypt($data) {
		$pi_key = openssl_pkey_get_private ( $this->PRIVATE_KEY ); //这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
		if (! empty ( $pi_key )) {

			$data = json_decode ( json_encode ( $data, JSON_UNESCAPED_UNICODE ), true );
			ksort ( $data );
			$data = str_replace ( "\\/", "/", json_encode ( $data, JSON_UNESCAPED_UNICODE ) );
			// 对数据分段加密
			$i = 0;
			$offSet = 0;
			$encryptedAll = '';
			while ( mb_strlen ( $data ) - $offSet > 0 ) {
				$originalData = substr ( $data, $offSet, SELF::MAX_ENCRYPT_BLOCK );
				if (openssl_private_encrypt ( substr ( $data, $offSet, SELF::MAX_ENCRYPT_BLOCK ), $encrypted, $pi_key )) { //私钥加密
					$encryptedAll = $encryptedAll . base64_encode ( $encrypted );
					$i ++;
					$offSet = $i * SELF::MAX_ENCRYPT_BLOCK;
				}
			}

			$encryptedAll = str_replace ( "+", "%2B", $encryptedAll );
			return $encryptedAll;
		} else {
			return null;
		}
	}
//公钥解密
	function public_decrypt($encrypted)
	{
		$pu_key = openssl_pkey_get_public($this->PUBLIC_KEY); //这个函数可用来判断公钥是否是可用的
		if (!empty ($pu_key)) {
			// 对数据分段解密
			$i = 0;
			$offSet = 0;
			$decryptAll = '';
			$encrypted = base64_decode($encrypted);
			while (mb_strlen($encrypted) - $offSet > 0) {
				if (SELF::MAX_DECRYPT_BLOCK * 2 <= mb_strlen($encrypted) && mb_strlen($encrypted) <= SELF::MAX_DECRYPT_BLOCK * 3) {
					$offSet = $i * SELF::MAX_DECRYPT_BLOCK;
					openssl_public_decrypt(substr($encrypted, $offSet, SELF::MAX_DECRYPT_BLOCK), $decrypted1, $pu_key); //私钥加密的内容通过公钥可用解密出来
					$decryptAll = $decryptAll . $decrypted1;
					$i++;
				} else {
					openssl_public_decrypt(substr($encrypted, $offSet, SELF::MAX_DECRYPT_BLOCK), $decrypted1, $pu_key); //私钥加密的内容通过公钥可用解密出来
					$decryptAll = $decryptAll . $decrypted1;
					$i++;
					$offSet = $i * SELF::MAX_DECRYPT_BLOCK;
				}
			}
			return $decryptAll;
		} else {
			return null;
		}
	}

	*/

	/*todo 新的加解密方法,解决回调\密文过长无法解析的问题*/
	//私钥加密
	function private_encrypt($info) {

		$pi_key = openssl_pkey_get_private ( $this->PRIVATE_KEY );
		$info = (array)$info;
		ksort($info);
		$data = json_encode($info, JSON_UNESCAPED_UNICODE);
		$partialData = '';
		$encryptedAll = '';
		$split = str_split($data , self::MAX_ENCRYPT_BLOCK);
		foreach($split as $part)
		{
			openssl_private_encrypt($part, $partialData,$pi_key);
			$encryptedAll .= base64_encode($partialData);
		}
		$encryptedAll = str_replace('+', '%2B', $encryptedAll);

		return $encryptedAll;

	}

	//公钥解密
	function public_decrypt($encrypted)
	{
		$pu_key = openssl_pkey_get_public($this->PUBLIC_KEY);
		$decryptAll = '';
		$split = str_split(base64_decode($encrypted ), self::MAX_DECRYPT_BLOCK);
		ForEach($split as $part)
		{
			openssl_public_decrypt($part, $partialData,$pu_key);
			$decryptAll .= $partialData;
		}
		return $decryptAll;

	}

	function http_POST($url,$arr) {
		$jsondata = urldecode ( $arr );
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $jsondata );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
		$response = curl_exec ( $ch );
		curl_close ( $ch );
		$obj = json_decode ( $response,true );
		print_r ( $obj );
		$response = str_replace ( "\r\n", "", $response );
		return $response;
	}
}

?>