<?php

namespace App\Salon\Logger;

use App\Salon\Contracts\Logger\LoggerInterface;
use Request;
use App\Salon\LoginLogoutLog;

/**
 * 
 * 
 * @desc 记录登录日志
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月31日
 */
class LoginLogger implements LoggerInterface
{

    /**
     * 写入日志
     * 
     * @param array $data 日志数据
     * @return boolean
     */
    public static function write(array $data)
    {
        if (! is_array($data)) {
            return false;
        }
        $log = new LoginLogoutLog;
        
        $data = array_add($data, 'user_ip', Request::getClientIp());
        $data = array_add($data, 'source', 'debug_web');
        $data = array_add($data, 'created_at', time());
        $data = array_add($data, 'content', '该操作存在异常');
        
        return $log->create($data);
    }
    
    /**
     * 更新日志记录
     * 
     */
    public static function update($model, array $data)
    {
        return false;
    }
}