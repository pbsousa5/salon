<?php

namespace App\Salon\Repositories;

use App\Salon\BackOrder;
use App\Salon\Contracts\Repositories\BackOrderRepositoryInterface;

/**
 * 
 * @desc 退单的数据仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class BackOrderRepository extends BaseRepository implements BackOrderRepositoryInterface
{
    /**
     * 
     * 创建一个退单数据仓库实例
     * @param App\Salon\BackOrder $back
     * @return void
     */
    public function __construct(BackOrder $back)
    {
        $this->model = $back;
    }
    
    /**
     * 创建或者更新退单
     * 
     * @param BackOrder $backOrder 退单model
     * @param array $inputs 创建或者更新的数据
     * @return BackOrder|null
     */
    protected function saveBackOrder(BackOrder $backOrder, array $inputs)
    {
        if (array_key_exists('order_info_id', $inputs)) {
            $backOrder->order_info_id = $inputs['order_info_id'];
        }
        if (array_key_exists('order_product_id', $inputs)) {
            $backOrder->order_product_id = $inputs['order_product_id'];
        }
        if (array_key_exists('trade_no', $inputs)) {
            $backOrder->trade_no = $inputs['trade_no'];
        }
        if (array_key_exists('consumer_id', $inputs)) {
            $backOrder->consumer_id = $inputs['consumer_id'];
        }
        if (array_key_exists('postscript', $inputs)) {
            $backOrder->postscript = e(trim($inputs['postscript']));
        }
        if (array_key_exists('back_fee', $inputs)) {
            $backOrder->back_fee = $inputs['back_fee'];
        }
        if (array_key_exists('bean_amount', $inputs)) {
            $backOrder->bean_amount = $inputs['bean_amount'];
        }
        if (array_key_exists('consumer_coupon_id', $inputs)) {
            $backOrder->consumer_coupon_id = $inputs['consumer_coupon_id'];
        }
        if (array_key_exists('consumer_name', $inputs)) {
            $backOrder->consumer_name = $inputs['consumer_name'];
        }
        if (array_key_exists('consumer_mobile', $inputs)) {
            $backOrder->consumer_mobile = $inputs['consumer_mobile'];
        }
        if (array_key_exists('consumer_head', $inputs)) {
            $backOrder->consumer_head = $inputs['consumer_head'];
        }
        
        if ($backOrder->save()) {
            return $backOrder;
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
        return $this->saveBackOrder($this->createModel(), $inputs);
    }
    
    /**
     * 获取指定资源
     *
     * @param array $where 查询条件
     * @param array $extra 可选额外传入的参数
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