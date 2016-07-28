<?php

namespace App\Salon\Repositories\V2;

use App\Salon\FundRecord;
use App\Salon\Repositories\FundRecordRepository as FundRecordRep;
/**
 *
 *
 *
 * @desc 资金记录信息数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class FundRecordRepository extends FundRecordRep
{
    /**
     *
     * 创建一个资金记录信息数据仓库实例
     * @param App\Salon\FundRecord $fundRecord
     * @return void
     */
    public function __construct(FundRecord $fundRecord)
    {
        $this->model = $fundRecord;
    }

    /**
     * 更新或创建资金记录信息
     * @param FundRecord $fundRecord 资金记录信息model
     * @param array $inputs 更新的数据
     * @return FundRecord|null
     */
    protected function saveFundRecord(FundRecord $fundRecord, array $inputs)
    {
        if (array_key_exists('draw_fee', $inputs)) {
            $fundRecord->draw_fee = $inputs['draw_fee'];
        }
        if (array_key_exists('balance_fee', $inputs)) {
            $fundRecord->balance_fee = $inputs['balance_fee'];
        }
        if (array_key_exists('total_pay_fee', $inputs)) {
            $fundRecord->total_pay_fee = $inputs['total_pay_fee'];
        }
        if (array_key_exists('user_id', $inputs)) {
            $fundRecord->user_id = $inputs['user_id'];
        }
        if (array_key_exists('user_type', $inputs)) {
            $fundRecord->user_type = strtolower(trim($inputs['user_type']));
        }
        if (array_key_exists('created_at', $inputs)) {
            $fundRecord->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $fundRecord->updated_at = $inputs['updated_at'];
        }       
        
        if ($fundRecord->save()) {
            return $fundRecord;
        }
        
        return null;
    }    
}