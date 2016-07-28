<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * 
 * 
 * @desc 用户消费事件
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月1日
 */
class UserConsumeEvent extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($orderProduct)
    {
        $this->orderProduct = $orderProduct;
    }
}
