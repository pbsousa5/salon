<?php

namespace App\Salon\Repositories\V2;

use App\Salon\OrderProduct;
use App\Salon\Repositories\OrderProductRepository as OrderProductRep;
/**
 *
 *
 *
 * @desc 订单产品数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class OrderProductRepository extends OrderProductRep
{
    /**
     *
     * 创建一个订单产品数据仓库实例
     * @param App\Salon\OrderProduct $orderProduct
     * @return void
     */
    public function __construct(OrderProduct $orderProduct)
    {
        $this->model = $orderProduct;
    }

    /**
     * 更新或创建订单产品
     * @param OrderProduct $orderProduct 订单产品model
     * @param array $inputs 更新的数据
     * @return OrderProduct|null
     */
    protected function saveOrderProduct(OrderProduct $orderProduct, array $inputs)
    {
        if (array_key_exists('order_info_id', $inputs)) {
            $orderProduct->order_info_id = $inputs['order_info_id'];
        }
        if (array_key_exists('consumer_id', $inputs)) {
            $orderProduct->consumer_id = $inputs['consumer_id'];
        }
        if (array_key_exists('product_id', $inputs)) {
            $orderProduct->product_id = $inputs['product_id'];
        }
        if (array_key_exists('supplier_id', $inputs)) {
            $orderProduct->supplier_id = $inputs['supplier_id'];
        }
        if (array_key_exists('barber_product_id', $inputs)) {
            $orderProduct->barber_product_id = $inputs['barber_product_id'];
        }
        if (array_key_exists('barber_id', $inputs)) {
            $orderProduct->barber_id = $inputs['barber_id'];
        }
        if (array_key_exists('category_name', $inputs)) {
            $orderProduct->category_name = trim($inputs['category_name']);
        }
        if (array_key_exists('product_name', $inputs)) {
            $orderProduct->product_name = e(trim($inputs['product_name']));
        }
        if (array_key_exists('product_desc', $inputs)) {
            $orderProduct->product_desc = e(trim($inputs['product_desc']));
        }
        if (array_key_exists('original_price', $inputs)) {
            $orderProduct->original_price = $inputs['original_price'];
        }
        if (array_key_exists('sign_price', $inputs)) {
            $orderProduct->sign_price = $inputs['sign_price'];
        }
        if (array_key_exists('pay_price', $inputs)) {
            $orderProduct->pay_price = $inputs['pay_price'];
        }
        if (array_key_exists('good_number', $inputs)) {
            $orderProduct->good_number = $inputs['good_number'];
        }
        if (array_key_exists('is_action', $inputs)) {
            $orderProduct->is_action = $inputs['is_action'];
        }
        if (array_key_exists('is_real', $inputs)) {
            $orderProduct->is_real = $inputs['is_real'];
        }
        if (array_key_exists('is_back', $inputs)) {
            $orderProduct->is_back = $inputs['is_back'];
        }
        if (array_key_exists('consume_code', $inputs)) {
            $orderProduct->consume_code = trim($inputs['consume_code']);
        }
        if (array_key_exists('product_status', $inputs)) {
            $orderProduct->product_status = $inputs['product_status'];
        }
        if (array_key_exists('multiple_supplier', $inputs)) {
            $orderProduct->multiple_supplier = $inputs['multiple_supplier'];
        }
        if (array_key_exists('multiple_platform', $inputs)) {
            $orderProduct->multiple_platform = $inputs['multiple_platform'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $orderProduct->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $orderProduct->updated_at = $inputs['updated_at'];
        }
        
        if ($orderProduct->save()) {
            return $orderProduct;
        }
        
        return null;
    }    
}