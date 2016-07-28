<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use App\Salon\OrderInfo;

/**
 * 
 * 
 * @desc 订单过期事件
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月2日
 */
class OrderExpireEvent extends Event
{
    use SerializesModels;
    
    /**
     * 订单model
     * @var OrderInfo
     */
    public $orderInfo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(OrderInfo $orderInfo)
    {
        $this->orderInfo = $orderInfo;
    }
}
