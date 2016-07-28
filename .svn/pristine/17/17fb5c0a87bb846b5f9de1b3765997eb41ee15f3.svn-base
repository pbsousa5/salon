<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use App\Salon\OrderInfo;

/**
 * 
 * 
 * @desc 处理免费订单事件
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月7日
 */
class OrderFreeEvent extends Event
{
    use SerializesModels;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {
        $this->orderInfo = OrderInfo::where('id', $order_id)->first();
    }
}
