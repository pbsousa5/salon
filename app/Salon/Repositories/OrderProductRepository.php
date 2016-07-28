<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\OrderProductRepositoryInterface;
use App\Salon\OrderProduct;

/**
 * 
 * 
 * @desc 订单产品数据仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class OrderProductRepository extends BaseRepository implements OrderProductRepositoryInterface
{
    /**
     * 
     * 创建一个订单产品数据仓库实例
     * @param App\Salon\OrderProduct $product
     * @return void
     */
    public function __construct(OrderProduct $product)
    {
        $this->model = $product;
    }
    
    /**
     * 保存或者更新订单产品接口
     * @param OrderProduct $orderProduct 订单产品模型
     * @param array $inputs 创建或者更新的数据
     * @return boolean
     */
    protected function saveOrderProduct(OrderProduct $orderProduct, array $inputs)
    {
        if (array_key_exists('order_info_id', $inputs)) {
            $orderProduct->order_info_id = $inputs['order_info_id'];
        }
        if (array_key_exists('consumer_id', $inputs)) {
            $orderProduct['consumer_id'] = $inputs['consumer_id'];
        }
        if (array_key_exists('product_id', $inputs)) {
            $orderProduct['product_id'] = $inputs['product_id'];
        }
        if (array_key_exists('supplier_id', $inputs)) {
            $orderProduct['supplier_id'] = $inputs['supplier_id'];
        }
        if (array_key_exists('barber_id', $inputs)) {
            $orderProduct['barber_id'] = $inputs['barber_id'];
        }
        if (array_key_exists('barber_product_id', $inputs)) {
            $orderProduct['barber_product_id'] = $inputs['barber_product_id'];
        }
        if (array_key_exists('category_name', $inputs)) {
            $orderProduct['category_name'] = $inputs['category_name'];
        }
        if (array_key_exists('product_name', $inputs)) {
            $orderProduct['product_name'] = $inputs['product_name'];
        }
        if (array_key_exists('product_desc', $inputs)) {
            $orderProduct['product_desc'] = $inputs['product_desc'];
        }
        if (array_key_exists('original_price', $inputs)) {
            $orderProduct['original_price'] = $inputs['original_price'];
        }
        if (array_key_exists('pay_price', $inputs)) {
            $orderProduct['pay_price'] = $inputs['pay_price'];
        }
        if (array_key_exists('sign_price', $inputs)) {
            $orderProduct['sign_price'] = $inputs['sign_price'];
        }
        if (array_key_exists('good_number', $inputs)) {
            $orderProduct['good_number'] = $inputs['good_number'];
        }
        if (array_key_exists('is_action', $inputs)) {
            $orderProduct['is_action'] = $inputs['is_action'];
        }
        if (array_key_exists('is_real', $inputs)) {
            $orderProduct['is_real'] = $inputs['is_real'];
        }
        if (array_key_exists('is_back', $inputs)) {
            $orderProduct['is_back'] = $inputs['is_back'];
        }
        if (array_key_exists('consume_code', $inputs)) {
            $orderProduct['consume_code'] = $inputs['consume_code'];
        }
        if (array_key_exists('product_status', $inputs)) {
            $orderProduct['product_status'] = $inputs['product_status'];
        }
        if (array_key_exists('multiple_supplier', $inputs)) {
            $orderProduct['multiple_supplier'] = $inputs['multiple_supplier'];
        }
        if (array_key_exists('multiple_platform', $inputs)) {
            $orderProduct['multiple_platform'] = $inputs['multiple_platform'];
        }
        
        if ($orderProduct->save()) {
            return $orderProduct;
        }
        
        return null;
    }
    
    /**
     * 批量更新
     *
     * @param array $where 批量更新的条件
     * @param array $inputs 批量更新的数据
     * @return
     */
    public function batchUpdate($where, $inputs)
    {
        $query = $this->createModel()->newQuery();
        if (array_key_exists('order_info_id', $where)) {
            $query->where('order_info_id', $where['order_info_id']);
        }
        
        if (array_key_exists('product_status', $inputs)) {
            return $query->update($inputs);
        }
        
        return false;
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
        $inputs = array_add($inputs, 'product_id', 0);
        $inputs = array_add($inputs, 'barber_id', 0);
        $inputs = array_add($inputs, 'barber_product_id', 0);
        
        return $this->saveOrderProduct($this->createModel(), $inputs);
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
        
        $query->with('orderInfo');
        foreach ($where as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->first();
    }
    
    /**
     * 更新特定id资源
     *
     * @param  int $id 订单id
     * @param  array $inputs 必须传入与更新模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return boolean
    */
    public function update($id, $inputs, $extra='')
    {
        $orderProduct = $this->model->where('id', $id)->first();
        if (is_null($orderProduct)) {
            return false;
        }
        
        return $this->saveOrderProduct($orderProduct, $inputs);
    }
    
}