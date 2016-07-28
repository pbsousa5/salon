<?php

namespace App\Salon\Repositories\V2;

use App\Salon\OrderAction;
use App\Salon\Repositories\OrderActionRepository as OrderActionRep;
/**
 *
 *
 *
 * @desc 订单活动数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class OrderActionRepository extends OrderActionRep
{
    /**
     *
     * 创建一个订单活动数据仓库实例
     * @param App\Salon\OrderAction $orderAction
     * @return void
     */
    public function __construct(OrderAction $orderAction)
    {
        $this->model = $orderAction;
    }

    /**
     * 更新或创建订单活动
     * @param OrderAction $orderAction 订单活动model
     * @param array $inputs 更新的数据
     * @return OrderAction|null
     */
    protected function saveOrderAction(OrderAction $orderAction, array $inputs)
    {
        if (array_key_exists('order_product_id', $inputs)) {
            $orderAction->order_product_id = $inputs['order_product_id'];
        }
        if (array_key_exists('action_name', $inputs)) {
            $orderAction->action_name = e(trim($inputs['action_name']));
        }
        if (array_key_exists('configs', $inputs)) {
            $orderAction->configs = trim($inputs['configs']);
        }
        
        if ($orderAction->save()) {
            return $orderAction;
        }
        
        return null;
    }    
}