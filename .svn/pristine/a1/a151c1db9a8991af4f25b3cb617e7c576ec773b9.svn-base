<?php

namespace App\Salon\Repositories;

use App\Salon\JoinApply;
use App\Salon\Contracts\Repositories\JoinApplyRepositoryInterface;

/**
 * 
 * 
 * @desc 加入我们的数据仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月10日
 */
class JoinApplyRepository extends BaseRepository implements JoinApplyRepositoryInterface
{
    /**
     *
     * 创建一个加入我们数据仓库实例
     * @param App\Salon\VersionApp $version
     * @return void
     */
    public function __construct(JoinApply $joinApply)
    {
        $this->model = $joinApply;
    }
    
    /**
     * 保存或者更新加入的申请信息
     * 
     * @param JoinApply $joinApply 申请加入数据model
     * @param array $inputs 要创建或者更新的信息
     */
    protected function saveJoinApply(JoinApply $joinApply, array $inputs)
    {
        if (array_key_exists('mobile', $inputs)) {
            $joinApply->mobile = trim($inputs['mobile']);
        }
        if (array_key_exists('store_name', $inputs)) {
            $joinApply->store_name = e(trim($inputs['store_name']));
        }
        if (array_key_exists('legal_name', $inputs)) {
            $joinApply->legal_name = e(trim($inputs['legal_name']));
        }
        if (array_key_exists('status', $inputs)) {
            $joinApply->status = $inputs['status'];
        }
        
        if ($joinApply->save()) {
            return $joinApply;
        }
        
        return null;
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
        return $this->saveJoinApply($this->createModel(), $inputs);
    }
    
    /**
     * 获取指定资源
     *
     * @param array $where 获取资源条件
     * @param array|string $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($where, $extra='')
    {
        $query = $this->createModel()->newQuery();
        
        foreach ($where as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->first();
    }
    
}