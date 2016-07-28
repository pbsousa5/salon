<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

/**
 * 
 * 
 * @desc 登出事件
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月1日
 */
class UserLoggedOut extends Event
{
    use SerializesModels;

    // 用户资料
    public $user;
    // 用户类型
    public $user_type;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $user_type)
    {
        $this->user = $user;
        $this->user_type = $user_type;
    }
}
