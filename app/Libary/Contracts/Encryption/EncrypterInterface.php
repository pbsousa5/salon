<?php

namespace App\Libary\Contracts\Encryption;

use Illuminate\Contracts\Encryption\Encrypter;
/**
 * 
 * 
 * @desc 扩展接口，增加了设置key的接口
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月15日
 */
interface EncrypterInterface extends Encrypter
{
    /**
     * 
     * 设置加密、解密的key
     * @param string $key
     */
    public function setKey($key);
}