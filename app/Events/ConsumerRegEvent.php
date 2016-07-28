<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * 
 * 
 * @desc 用户注册成功事件
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月18日
 */
class ConsumerRegEvent extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($consumer)
    {
        $this->consumer = $consumer;
    }
}
