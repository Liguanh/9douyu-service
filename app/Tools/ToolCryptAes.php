<?php
/**
 * Created by PhpStorm.
 * User: scofie
 * Date: 2017/9/14
 * Time: PM5:29
 */

namespace App\Tools;



class ToolCryptAes
{
    protected $cipher       =   MCRYPT_RIJNDAEL_128;    //加密模式
    protected $mode         =   MCRYPT_MODE_CBC;        //加密的方式
    protected $pad_method   =   NULL;
    protected $secret_key   =   NULL;
    protected $encIv        =   NUll;

    /**
     * @param string $string
     * @return bool|string
     * @desc 输出加密的结果
     */
    public function getEncryptText($string ='')
    {
        if( empty($string) ) {
            return false ;
        }

//        $this->set_key($this->secret_key);
//
//        $this->set_iv($this->encIv);

        $this->require_pkcs5();

        return $this->encrypt($string);
    }

    /**
     * @param string $string
     * @return bool|mixed
     * @desc  输出解密的结果
     */
    public function getDecryptText($string ='')
    {
        if( empty($string) ) {

            return false ;
        }

//        $this->set_key($this->secret_key);
//
//        $this->set_iv($this->encIv);

        $this->require_pkcs5();

        return $this->decrypt($string);
    }
    /**
     * @param $cipher
     * @desc  Mcrypt 的加密模式
     */
    public function setCipher($cipher)
    {
        $this->cipher = $cipher;
    }

    /**
     * @param $mode
     * @desc 加密的方式
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function require_pkcs5()
    {
        $this->pad_method = 'pkcs5';
    }

    /**
     * @param $iv
     * @desc 实例变量
     */
    public function set_iv($iv)
    {
        $this->iv = $iv;
    }

    /**
     * @param $key
     * @desc 实例变量
     */
    public function set_key($key)
    {
        $this->secret_key = $key;
    }

    /**
     * @param $str
     * @param $ext
     * @return mixed
     * @desc 返回回调结果
     */
    protected function pad_or_unpad($str, $ext)
    {
        if ( is_null($this->pad_method) ) {
            return $str;
        } else {
            $func_name = __CLASS__ . '::' . $this->pad_method . '_' . $ext . 'pad';

            if ( is_callable($func_name) ) {

                $size = mcrypt_get_block_size($this->cipher, $this->mode);

                return call_user_func($func_name, $str, $size);
            }
        }
        return $str;
    }


    protected function pad($str)
    {
        return $this->pad_or_unpad($str, '');
    }

    protected function unpad($str)
    {
        return $this->pad_or_unpad($str, 'un');
    }

    /**
     * @param $string
     * @return string
     * @desc 加密的方式
     */
    public function encrypt($string)
    {
        $string     =   $this->pad($string);

        $openEncrypt=   $this->doMcryptOpen ();

        if ( empty($this->iv) ) {

            $encIv  =   @mcrypt_create_iv(mcrypt_enc_get_iv_size($openEncrypt), MCRYPT_RAND);
        } else {
            $encIv  =   $this->iv;
        }

        mcrypt_generic_init($openEncrypt, $this->secret_key, $encIv);

        $encryptText=   mcrypt_generic($openEncrypt, $string);

        $baseEncrypt=   base64_encode($encryptText);

        mcrypt_generic_deinit($openEncrypt);

        mcrypt_module_close($openEncrypt);

        return $baseEncrypt;
    }

    /**
     * @return resource
     */
    protected function doMcryptOpen()
    {
        return mcrypt_module_open($this->cipher, '', $this->mode, '');
    }
    /**
     * @param $str
     * @return mixed
     * @desc  解密的方式
     */
    public function decrypt($str) {

        $td = $this->doMcryptOpen ();;

        $openEncrypt=   $this->doMcryptOpen ();

        if ( empty($this->iv) ) {
            $encIv  =   @mcrypt_create_iv(mcrypt_enc_get_iv_size($openEncrypt), MCRYPT_RAND);
        } else {
            $encIv  =   $this->iv;
        }

        mcrypt_generic_init($td, $this->secret_key, $encIv);

        $decrypted_text = mdecrypt_generic($td, base64_decode($str));

        $rt = $decrypted_text;

        mcrypt_generic_deinit($td);

        mcrypt_module_close($td);

        return $this->unpad($rt);
    }


    public static function pkcs5_pad($text, $blockSize)
    {
        $pad = $blockSize - (strlen($text) % $blockSize);

        return $text . str_repeat(chr($pad), $pad);
    }

    public static function pkcs5_unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});

        if ($pad > strlen($text)) return false;

        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        
        return substr($text, 0, -1 * $pad);
    }
}