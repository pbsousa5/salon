<?php

namespace App\Salon\Logger;

use App\Salon\Contracts\Logger\LoggerInterface;
use App\Salon\Notify;

/**
 * 
 * 
 * @desc 消息日志
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月3日
 */
class NotifyLogger implements LoggerInterface
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
        $log = new Notify;
        
        $data = array_add($data, 'is_read', 0);
        
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