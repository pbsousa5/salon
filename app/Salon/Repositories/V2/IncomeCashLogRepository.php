<?php

namespace App\Salon\Repositories\V2;

use App\Salon\IncomeCashLog;
use App\Salon\Repositories\IncomeCashLogRepository as IncomeCashLogRep;
/**
 *
 *
 *
 * @desc 收入日志记录数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class IncomeCashLogRepository extends IncomeCashLogRep
{
    /**
     *
     * 创建一个收入日志记录数据仓库实例
     * @param App\Salon\IncomeCashLog $incomeCashLog
     * @return void
     */
    public function __construct(IncomeCashLog $incomeCashLog)
    {
        $this->model = $incomeCashLog;
    }

    /**
     * 更新或创建收入日志记录
     * @param IncomeCashLog $incomeCashLog 收入日志记录model
     * @param array $inputs 更新的数据
     * @return IncomeCashLog|null
     */
    protected function saveIncomeCashLog(IncomeCashLog $incomeCashLog, array $inputs)
    {
        if (array_key_exists('supplier_id', $inputs)) {
            $incomeCashLog->supplier_id = $inputs['supplier_id'];
        }
        if (array_key_exists('barber_id', $inputs)) {
            $incomeCashLog->barber_id = $inputs['barber_id'];
        }
        if (array_key_exists('consumer_id', $inputs)) {
            $incomeCashLog->consumer_id = $inputs['consumer_id'];
        }
        if (array_key_exists('order_info_id', $inputs)) {
            $incomeCashLog->order_info_id = $inputs['order_info_id'];
        }
        if (array_key_exists('trade_no', $inputs)) {
            $incomeCashLog->trade_no = trim($inputs['trade_no']);
        }
        if (array_key_exists('cash_fee', $inputs)) {
            $incomeCashLog->cash_fee = $inputs['cash_fee'];
        }
        if (array_key_exists('pay_fee', $inputs)) {
            $incomeCashLog->pay_fee = $inputs['pay_fee'];
        }
        if (array_key_exists('status', $inputs)) {
            $incomeCashLog->status = $inputs['status'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $incomeCashLog->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $incomeCashLog->updated_at = $inputs['updated_at'];
        }
        
        if ($incomeCashLog->save()) {
            return $incomeCashLog;
        }
        
        return null;
    }    
}