<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\OrderActionRepositoryInterface;
use App\Salon\OrderAction;

/**
 * 
 * 
 * @desc 订单活动
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class OrderActionRepository implements OrderActionRepositoryInterface
{
    /**
     * 
     * 创建一个订单活动数据仓库实例
     * @param App\Salon\OrderAction $action
     * @return void
     */
    public function __construct(OrderAction $action)
    {
        $this->model = $action;
    }
    
}