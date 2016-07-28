<?php

namespace App\Salon\Repositories\V2;

use App\Salon\ConsumeLog;
use App\Salon\Repositories\ConsumeLogRepository as ConsumeLogRep;
/**
 *
 *
 *
 * @desc  消费日志数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class ConsumeLogRepository extends ConsumeLogRep
{
    /**
     *
     * 创建一个 消费日志数据仓库实例
     * @param App\Salon\ConsumeLog $consumelog
     * @return void
     */
    public function __construct(ConsumeLog $consumeLog)
    {
        $this->model = $consumeLog;
    }

    /**
     * 更新或创建 消费日志
     * @param ConsumeLog $consumeLog  消费日志model
     * @param array $inputs 更新的数据
     * @return ConsumeLog|null
     */
    protected function saveConsumeLog(ConsumeLog $consumeLog, array $inputs)
    {
        if (array_key_exists('order_info_id', $inputs)) {
            $consumeLog->order_info_id = $inputs['order_info_id'];
        }
        if (array_key_exists('order_product_id', $inputs)) {
            $consumeLog->order_product_id = $inputs['order_product_id'];
        }
        if (array_key_exists('consumer_id', $inputs)) {
            $consumeLog->consumer_id = $inputs['consumer_id'];
        }
        if (array_key_exists('supplier_id', $inputs)) {
            $consumeLog->supplier_id = $inputs['supplier_id'];
        }
        if (array_key_exists('barber_id', $inputs)) {
            $consumeLog->barber_id = $inputs['barber_id'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $consumeLog->created_at = $inputs['created_at'];
        }
        
        if ($consumeLog->save()) {
            return $consumeLog;
        }
        
        return null;
    }    
}