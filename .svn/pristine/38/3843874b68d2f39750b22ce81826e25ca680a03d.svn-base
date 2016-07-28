<?php

namespace App\Salon\Repositories\V2;

use App\Salon\FundAccount;
use App\Salon\Repositories\FundAccountRepository as FundAccountRep;
/**
 *
 *
 *
 * @desc 资金账户数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class FundAccountRepository extends FundAccountRep
{
    /**
     *
     * 创建一个资金账户数据仓库实例
     * @param App\Salon\FundAccount $fundaccount
     * @return void
     */
    public function __construct(FundAccount $fundAccount)
    {
        $this->model = $fundAccount;
    }

    /**
     * 更新或创建资金账户
     * @param FundAccount $fundAccount 资金账户model
     * @param array $inputs 更新的数据
     * @return FundAccount|null
     */
    protected function saveFundAccount(FundAccount $fundAccount, array $inputs)
    {
        if (array_key_exists('user_id', $inputs)) {
            $fundAccount->user_id = $inputs['user_id'];
        }
        if (array_key_exists('user_type', $inputs)) {
            $fundAccount->user_type = strtolower(trim($inputs['user_type']));
        }
        if (array_key_exists('user_name', $inputs)) {
            $fundAccount->user_name = e(trim($inputs['user_name']));
        }
        if (array_key_exists('card_number', $inputs)) {
            $fundAccount->card_number = e(trim($inputs['card_number']));
        }
        if (array_key_exists('mobile', $inputs)) {
            $fundAccount->mobile = trim($inputs['mobile']);
        }
        if (array_key_exists('pay_code', $inputs)) {
            $fundAccount->pay_code = e(trim($inputs['pay_code']));
        }
        if (array_key_exists('created_at', $inputs)) {
            $fundAccount->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $fundAccount->updated_at = $inputs['updated_at'];
        }
        
        if ($fundAccount->save()) {
            return $fundAccount;
        }
        
        return null;
    }    
}