<?php

namespace App\Libary\Encryption;

use App\Libary\Contracts\Encryption\EncrypterInterface;
use RuntimeException;
/**
 * 
 * 
 * @desc 使用AES进行加解密
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月15日
 */
class AesCrypt implements EncrypterInterface
{
    /**
     * 
     * @var string
     */
    protected $hex_iv = '00000000000000000000000000000000';
    
    /**
     * The encryption key.
     *
     * @var string
     */
    protected $key;
    
    /**
     * 创建一个加解密实例
     * @param string $key
     */
    public function __construct($key)
    {
        $key = (string) trim($key);
        $this->key = hash('sha256', $key, true);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Illuminate\Contracts\Encryption\Encrypter::encrypt()
     */
    public function encrypt($str)
    {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($td, $this->key, $this->hexToStr($this->hex_iv));
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $pad = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);
        $encrypted = mcrypt_generic($td, $str);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return base64_encode($encrypted);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Illuminate\Contracts\Encryption\Encrypter::decrypt()
     */
    public function decrypt($payload)
    {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($td, $this->key, $this->hexToStr($this->hex_iv));
        $str = mdecrypt_generic($td, base64_decode($payload));
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $this->strippadding($str);
    }
    
    /**
     * For PKCS7 padding
     * @param string $string
     * @param int $blocksize
     */
    private function addpadding($string, $blocksize = 16)
    {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;
    }
    
    /**
     * 
     * @param string $string
     */
    private function strippadding($string)
    {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }
    
    /**
     * 
     * @param string $hex
     */
    protected function hexToStr($hex)
    {
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2)
        {
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }
    
    /**
     * (non-PHPdoc)
     * @see \App\Libary\Contracts\Encryption\EncrypterInterface::setKey()
     */
    public function setKey($key)
    {
        $key = (string) trim($key);
        $len = strlen($key);
        if (16!=$len && 32!=$len) {
            throw new RuntimeException('仅支持16位或32位秘钥');
        }
        
        $this->key = hash('sha256', $key, true);
    }
}