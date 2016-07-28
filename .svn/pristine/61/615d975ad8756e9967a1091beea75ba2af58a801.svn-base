<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\WithdrawCashLogRepositoryInterface;
use App\Salon\WithdrawCashLog;

/**
 * 
 * 
 * @desc 提现日志记录
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class WithdrawCashLogRepository extends BaseRepository implements WithdrawCashLogRepositoryInterface
{
    /**
     * 
     * 创建一个提现日志数据仓库实例
     * @param App\Salon\WithdrawCashLog $cashLog
     * @return void
     */
    public function __construct(WithdrawCashLog $cashLog)
    {
        $this->model = $cashLog;
    }
    
    /**
     * 增加或者更新提现记录日志
     * 
     * @param WithdrawCashLog $withdrawCashLog 提现记录日志
     * @param array $inputs 创建或更新的数据
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
            $withdrawCashLog->user_name = $inputs['user_name'];
        }
        if (array_key_exists('card_number', $inputs)) {
            $withdrawCashLog->card_number = $inputs['card_number'];
        }
        if (array_key_exists('pay_code', $inputs)) {
            $withdrawCashLog->pay_code = $inputs['pay_code'];
        }
        if (array_key_exists('supplier_id', $inputs)) {
            $withdrawCashLog->supplier_id = $inputs['supplier_id'];
        }
        
        if ($withdrawCashLog->save()) {
            return $withdrawCashLog;
        } else {
            return null;
        }
    }
    
    /**
     * 统计门店申请提现的信息
     *
     * @param integer $supplier_id 门店id
     * @param array|string $extra 额外条件
     * @return integer
     */
    public function countApplyInfo($supplier_id, $extra='')
    {
        if (array_key_exists('is_verify', $extra)) {
            return $this->model
                            ->where('supplier_id', $supplier_id)
                            ->where('is_verify', $extra['is_verify'])
                            ->count();
        }
        
        return 0;
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $data 必须传入与模型查询相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @param  integer $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($data, $extra='', $size=10)
    {
        $query = $this->createModel()->newQuery();
        
        foreach ($data as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($size);
    }
    
    /**
     * 存储资源
     *
     * @param  array $inputs 必须传入与存储模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model
     */
    public function store($inputs, $extra='')
    {
        $inputs = array_add($inputs, 'is_verify', 0);
        
        return $this->saveWithdrawCashLog($this->createModel(), $inputs);
    }
    
}