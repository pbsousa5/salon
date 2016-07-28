<?php

namespace App\Salon\Repositories\V2;

use App\Salon\WithdrawCashLog;
use App\Salon\Repositories\WithdrawCashLogRepository as WithdrawCashLogRep;
/**
 *
 *
 *
 * @desc 提现日志数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class WithdrawCashLogRepository extends WithdrawCashLogRep
{
    /**
     *
     * 创建一个提现日志数据仓库实例
     * @param App\Salon\WithdrawCashLog $withdrawCashLog
     * @return void
     */
    public function __construct(WithdrawCashLog $withdrawCashLog)
    {
        $this->model = $withdrawCashLog;
    }

    /**
     * 更新或创建提现日志
     * @param WithdrawCashLog $withdrawCashLog 提现日志model
     * @param array $inputs 更新的数据
     * @return WithdrawCashLog|null
     */
    protected function saveWithdrawCashLog(WithdrawCashLog $withdrawCashLog, array $inputs)
    {
        if (array_key_exists('supplier_id', $inputs)) {
            $withdrawCashLog->supplier_id = $inputs['supplier_id'];
        }
        if (array_key_exists('fund_account_id', $inputs)) {
            $withdrawCashLog->fund_account_id = $inputs['fund_account_id'];
        }
        if (array_key_exists('cash_fee', $inputs)) {
            $withdrawCashLog->cash_fee = $inputs['cash_fee'];
        }
        if (array_key_exists('pay_fee', $inputs)) {
            $withdrawCashLog->pay_fee = $inputs['pay_fee'];
        }
        if (array_key_exists('user_name', $inputs)) {
            $withdrawCashLog->user_name = e(trim($inputs['user_name']));
        }
        if (array_key_exists('card_number', $inputs)) {
            $withdrawCashLog->card_number = trim($inputs['card_number']);
        }
        if (array_key_exists('pay_code', $inputs)) {
            $withdrawCashLog->pay_code = $inputs['pay_code'];
        }
        if (array_key_exists('is_verify', $inputs)) {
            $withdrawCashLog->is_verify = $inputs['is_verify'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $withdrawCashLog->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $withdrawCashLog->updated_at = $inputs['updated_at'];
        }
        
        if ($withdrawCashLog->save()) {
            return $withdrawCashLog;
        }
        
        return null;
    }    
}