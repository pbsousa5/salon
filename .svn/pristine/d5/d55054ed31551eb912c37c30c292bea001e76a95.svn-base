<?php

namespace App\Salon\Logger;

use App\Salon\Contracts\Logger\LoggerInterface;
use App\Salon\IncomeCashLog;

/**
 * 
 * 
 * @desc 记录消费时或者提现时，现金变动
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月3日
 */
class IncomeLogger implements LoggerInterface
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
        $log = new IncomeCashLog;
    
        $data = array_add($data, 'barber_id', 0);
        $data['created_at'] = date('Y-m-d H:i:s', time());
    
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