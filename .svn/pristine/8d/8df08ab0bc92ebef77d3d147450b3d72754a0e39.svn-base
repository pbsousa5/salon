<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * 
 * 
 * @desc 退单消息推送
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月12日
 */
class RetOrderPushEvent extends Event
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
