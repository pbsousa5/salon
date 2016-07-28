<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * 
 * 
 * @desc 下单事件
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月12日
 */
class OrderPushEvent extends Event
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
