<?php

namespace App\Salon\Logger;

use App\Salon\Contracts\Logger\LoggerInterface;
use App\Salon\ConsumeLog;

/**
 * 
 * 
 * @desc 记录消费日志
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月3日
 */
class ConsumeLogger implements LoggerInterface
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
        $log = new ConsumeLog;
    
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data = array_add($data, 'barber_id', 0);
    
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