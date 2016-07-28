<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\FundAccountRepositoryInterface;
use App\Salon\FundAccount;
use Illuminate\Support\Str;

/**
 * 
 * 
 * @desc 资金账户数据仓库实现
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class FundAccountRepository extends BaseRepository implements FundAccountRepositoryInterface
{
    /**
     * 
     * 创建一个资金账户数据仓库实例
     * @param App\Salon\FundAccount $account
     * @return void
     */
    public function __construct(FundAccount $account)
    {
        $this->model = $account;
    }
    
    /**
     * 更新或者创建资金账号
     * 
     * @param FundAccount $fundAccount 资金账号model
     * @param array $inputs 更新或者创建的数据
     * @return FundAccount|null
     */
    protected function saveFundAccount(FundAccount $fundAccount, array $inputs)
    {
        if (array_key_exists('user_id', $inputs)) {
            $fundAccount->user_id = $inputs['user_id'];
        }
        if (array_key_exists('user_type', $inputs)) {
            $fundAccount->user_type = e(trim($inputs['user_type']));
        }
        if (array_key_exists('user_name', $inputs)) {
            $fundAccount->user_name = e(trim($inputs['user_name']));
        }
        if (array_key_exists('card_number', $inputs)) {
            $fundAccount->card_number = e(trim($inputs['card_number']));
        }
        if (array_key_exists('mobile', $inputs)) {
            $fundAccount->mobile = $inputs['mobile'];
        }
        if (array_key_exists('pay_code', $inputs)) {
            $fundAccount->pay_code = trim($inputs['pay_code']);
        }
        
        if ($fundAccount->save()) {
            return $fundAccount;
        } else {
            return null;
        }
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $data 必须传入与模型查询相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @param  integer $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($data, $user_type='', $size=10)
    {
        $query = $this->createModel()->newQuery();
        
        $query->with('paymentType')->where('user_type', $user_type);
        foreach ($data as $field => $value) {
            $query->where($field, $value);
        }
        
        
        return $query->orderBy('updated_at', 'desc')->get();
    }
    
    /**
     * 获取指定资源
     *
     * @param int $user_id 账户所有者id
     * @param string $user_type 用户身份类型
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($user_id, $user_type='')
    {
        return $this->model->where('user_id', $user_id)->where('user_type', $user_type)->first();
    }
    
    /**
     * 更新资源
     *
     * @param  array $where 更新的资源查询条件
     * @param  array $inputs 必须传入与更新模型相关的数据
     * @param  string $extra 用户的类型
     * @return void
    */
    public function update($where, $inputs, $extra='')
    {
        $query = $this->createModel()->newQuery();
        
        if (array_key_exists('fund_id', $inputs)) {
            $query->where('id', $inputs['fund_id']);
            unset($inputs['fund_id']);
        }
        foreach ($where as $field => $value) {
            $query->where($field, $value);
        }
        
        return $this->saveFundAccount($query->first(), $inputs);
    }
    
}