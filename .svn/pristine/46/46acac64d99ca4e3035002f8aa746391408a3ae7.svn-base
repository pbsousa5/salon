<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * 
 * 
 * @desc 用户阅读消息事件
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月9日
 */
class UserReadNotifyEvent extends Event
{
    use SerializesModels;
    
    // 用户资料
    public $user_id;
    // 用户类型
    public $user_type;

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
