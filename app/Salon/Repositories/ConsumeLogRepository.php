<?php

namespace App\Salon\Repositories;

use App\Salon\ConsumeLog;
use App\Salon\Contracts\Repositories\ConsumeLogRepositoryInterface;

/**
 * 
 * 
 * @desc 消费日志仓库实现
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class ConsumeLogRepository extends BaseRepository implements ConsumeLogRepositoryInterface
{
    /**
     * 
     * 创建一个消费关系数据仓库实例
     * @param App\Salon\ConsumeLog $log
     * @return void
     */
    public function __construct(ConsumeLog $log)
    {
        $this->model = $log;
    }
    
    /**
     * 创建或者更新消费日志
     * 
     * @param ConsumeLog $consumeLog 消费日志model
     * @param array $inputs 创建或者更新的数据
     * 
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
    
    /**
     * 根据给定的条件，统计有多少条记录
     *
     * @param string $filed 统计的字段
     * @param string $value 字段的值
     * @return integer
     */
    public function countByWhere($filed, $value)
    {
        return $this->model
                        ->where($filed, $value)
                        ->groupBy('consumer_id')
                        ->count();
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $data 必须传入与模型查询相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @param  string $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($data, $extra='', $size=10)
    {
        $query = $this->createModel()->newQuery();
        
        $query->with('consumer');
        foreach ($data as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->groupBy('consumer_id')->orderBy('created_at', 'desc')->paginate($size);
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
        $inputs = array_add($inputs, 'created_at', time());
        
        return $this->saveConsumeLog($this->createModel(), $inputs);
    }
    
}