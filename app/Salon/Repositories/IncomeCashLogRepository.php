<?php

namespace App\Salon\Repositories;

use App\Salon\IncomeCashLog;
use App\Salon\Contracts\Repositories\IncomeCashLogRepositoryInterface;
use Illuminate\Support\Str;

/**
 * 
 * 
 * @desc 收入日志记录
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月11日
 */
class IncomeCashLogRepository extends BaseRepository implements IncomeCashLogRepositoryInterface
{
    /**
     * 
     * 创建一个收入日志数据仓库实例
     * @param App\Salon\WithdrawCashLog $cashLog
     * @return void
     */
    public function __construct(IncomeCashLog $cashLog)
    {
        $this->model = $cashLog;
    }
    
    /**
     * 根据门店id，获取资金情况。
     * 
     * @param integer $supplier_id 提现金额
     */
    public function sumCashFee($supplier_id)
    {
        // 总收入
        $supplier_total_fee = $this->model->where('supplier_id', $supplier_id)->where('barber_id', 0)->sum('cash_fee');
        $barber_total_fee = $this->model->where('supplier_id', $supplier_id)->where('barber_id', '<>', 0)->sum('cash_fee');
        
        // 未体现金额
        $supplier_balance_fee = $this->model->where('supplier_id', $supplier_id)->where('barber_id', 0)->where('status', 1)->sum('cash_fee');
        $barber_balance_fee = $this->model->where('supplier_id', $supplier_id)->where('barber_id', '<>', 0)->where('status', 1)->sum('cash_fee');
        
        return compact('supplier_total_fee', 'barber_total_fee', 'supplier_balance_fee', 'barber_balance_fee');
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $data 必须传入与模型查询相关的数据
     * @param  string $fee_source 资金来源
     * @param  integer $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($data, $fee_source = 'supplier', $size = 10)
    {
        $query = $this->createModel()->newQuery();
        
        $query->with('consumer');
        if (Str::equals('supplier', $data['user_type'])) {
            $query->where('supplier_id', $data['user_id']);
            if (Str::equals('supplier', $fee_source)) {// 获取门店收入
                $query->where('barber_id', 0);
            } else {// 获取理发师收入
                $query->where('barber_id', '<>', 0);
            }
        } elseif (Str::equals('barber', $data['user_type'])) {
            $query->where('barber_id', $data['user_id']);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($size);
    }
    
}