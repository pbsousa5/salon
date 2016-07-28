<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use App\Salon\SupplierCache;
use App\Salon\BarberCache;

/**
 * 
 * 
 * @desc 用户关注事件
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月9日
 */
class ConsumerFollowerEvent extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id, $user_type)
    {
        $this->user_id = $user_id;
        $this->user_type = $user_type;
    }

}
