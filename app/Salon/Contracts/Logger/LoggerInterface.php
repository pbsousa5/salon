<?php

namespace App\Salon\Contracts\Logger;

/**
 * 
 * 
 * @desc 日志接口
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月31日
 */
interface LoggerInterface
{
    /**
     * 写入日志
     * 
     * @param array $data 日志数据
     * @return boolean
     */
    public static function write(array $data);
    
    /**
     * 更新日志
     * 
     * @param Illuminate\Database\Eloquent\Model $model
     * @param array $data 更新的数据
     * @return boolean
     */
    public static function update($model, array $data);
}