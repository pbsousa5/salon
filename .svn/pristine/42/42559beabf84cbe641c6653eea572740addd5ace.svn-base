<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\FundRecordRepositoryInterface;
use App\Salon\FundRecord;
/**
 * 
 * 
 * @desc 资金记录信息数据仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class FundRecordRepository extends BaseRepository implements FundRecordRepositoryInterface
{
    /**
     * 
     * 创建一个资金变化的数据仓库实例
     * @param App\Salon\FundRecord $record
     * @return void
     */
    public function __construct(FundRecord $record)
    {
        $this->model = $record;
    }
    
    /**
     * 获取指定资源
     *
     * @param int $user_id 用户id
     * @param string $user_type 用户的身份 supplier:门店
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($user_id, $user_type='supplier')
    {
        return $this->model->where('user_id', $user_id)->where('user_type', $user_type)->first();
    }
    
    /**
     * 更新特定id资源
     *
     * @param  int $supplier_id 门店id
     * @param  array $inputs 必须传入与更新模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return void
    */
    public function update($supplier_id, $inputs, $extra='')
    {
        $fund = $this->model->where('user_id', $supplier_id)->where('user_type', 'supplier')->first();
        if (empty($fund)) {
            return null;
        }
        
        if (array_key_exists('sign_fee', $inputs)) {// 增加可提现的金额
            $fund->balance_fee += $inputs['sign_fee'];
        }
        
        if (array_key_exists('pay_fee', $inputs)) {// 统计实际用户支付的价格
            $fund->total_pay_fee += $inputs['pay_fee'];
        }
        
        return $fund->save();
    }
    
}