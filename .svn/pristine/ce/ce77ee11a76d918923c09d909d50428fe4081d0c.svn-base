<?php

namespace App\Salon\Repositories\V2;

use App\Salon\BackOrder;
use App\Salon\Repositories\BackOrderRepository as BackOrderRep;
/**
 *
 *
 *
 * @desc 退单的数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class BackOrderRepository extends BackOrderRep
{
    /**
     *
     * 创建一个退单数据仓库实例
     * @param App\Salon\BackOrder $backorder
     * @return void
     */
    public function __construct(BackOrder $backOrder)
    {
        $this->model = $backOrder;
    }
    
    /**
     * 保存或者更新退单信息
     * @param BackOrder $backorder 退单model
     * @param array $inputs 更新的数据
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
            $backOrder->trade_no = trim($inputs['trade_no']);
        }
        if (array_key_exists('consumer_id', $inputs)) {
            $backOrder->consumer_id = $inputs['consumer_id'];
        }
        if (array_key_exists('postscript', $inputs)) {
            $backOrder->postscript = e(trim($inputs['postscript']));
        }
        if (array_key_exists('back_fee', $inputs)) {
            $backOrder->back_fee = (int)$inputs['back_fee'];
        }
        if (array_key_exists('back_status', $inputs)) {
            $backOrder->back_status = $inputs['back_status'];
        }
        if (array_key_exists('bean_amount', $inputs)) {
            $backOrder->bean_amount = (int)$inputs['bean_amount'];
        }
        if (array_key_exists('consumer_coupon_id', $inputs)) {
            $backOrder->consumer_coupon_id = $inputs['consumer_coupon_id'];
        }
        if (array_key_exists('consumer_name', $inputs)) {
            $backOrder->consumer_name = e(trim($inputs['consumer_name']));
        }
        if (array_key_exists('consumer_mobile', $inputs)) {
            $backOrder->consumer_mobile = trim($inputs['consumer_mobile']);
        }
        if (array_key_exists('consumer_head', $inputs)) {
            $backOrder->consumer_head = trim($inputs['consumer_head']);
        }
        if (array_key_exists('created_at', $inputs)) {
            $backOrder->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $backOrder->updated_at = $inputs['updated_at'];
        }

        if ($backOrder->save()) {
            return $backOrder;
        }
        
        return null;
    }
}