<?php

namespace App\Salon\Exceptions;

use Exception;
/**
 * 
 * 
 * @desc redis服务发生异常
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月27日
 */
class RedisException extends Exception
{
    /**
     * Constructor
     * @param string $msg
     * @param int $code
     */
    public function __construct($message, $code=0)
    {
        parent::__construct($message, $code);
    }
}