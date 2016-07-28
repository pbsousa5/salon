<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * 
 * 
 * @desc 支付订单时，发送消息
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月19日
 */
class NotifyPayOrderEvent extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($orderInfo)
    {
        $this->orderInfo = $orderInfo;
    }
}
