<?php

namespace App\Salon\Repositories;

use App\Salon\BackProduct;
use App\Salon\Contracts\Repositories\BackProductRepositoryInterface;

/**
 * 
 * 
 * @desc 退单中的产品仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class BackProductRepository extends BaseRepository implements BackProductRepositoryInterface
{
    /**
     * 
     * 创建一个退单产品数据仓库实例
     * @param App\Salon\BackProduct $pback
     * @return void
     */
    public function __construct(BackProduct $pback)
    {
        $this->model = $pback;
    }
    
    /**
     * 创建或者更新退单产品
     * 
     * @param BackProduct $backProduct 订单产品model
     * @param array $inputs 创建或者更新的数据
     * @return BackProduct|null
     */
    protected function saveBackProduct(BackProduct $backProduct, array $inputs)
    {
        if (array_key_exists('back_order_id', $inputs)) {
            $backProduct->back_order_id = $inputs['back_order_id'];
        }
        if (array_key_exists('barber_id', $inputs)) {
            $backProduct->barber_id = $inputs['barber_id'];
        }
        if (array_key_exists('barber_product_id', $inputs)) {
            $backProduct->barber_product_id = $inputs['barber_product_id'];
        }
        if (array_key_exists('product_id', $inputs)) {
            $backProduct->product_id = $inputs['product_id'];
        }
        if (array_key_exists('supplier_id', $inputs)) {
            $backProduct->supplier_id = $inputs['supplier_id'];
        }
        if (array_key_exists('category_name', $inputs)) {
            $backProduct->category_name = $inputs['category_name'];
        }
        if (array_key_exists('product_name', $inputs)) {
            $backProduct->product_name = $inputs['product_name'];
        }
        if (array_key_exists('back_fee', $inputs)) {
            $backProduct->back_fee = $inputs['back_fee'];
        }
        if (array_key_exists('back_number', $inputs)) {
            $backProduct->back_number = $inputs['back_number'];
        }
        
        if ($backProduct->save()) {
            return $backProduct;
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
        return $this->saveBackProduct($this->createModel(), $inputs);
    }
    
}