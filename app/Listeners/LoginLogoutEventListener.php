<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Request;
use Illuminate\Support\Str;
use App\Salon\Logger\LoginLogger;

/**
 * 
 * 
 * @desc 登录、登出事件处理
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月1日
 */
class LoginLogoutEventListener
{
    /**
     * 处理用户登录事件。
     */
    public function onUserLogin($event)
    {
        $user = $event->user;
        $user_type = $event->user_type;
        if (Str::equals('supplier', $user_type)) {
            $name = $user->name;
        } elseif (Str::equals('barber', $user_type)) {
            $name = $user->barber_nickname;
            $user->mobile = $user->barber_mobile;
        } else {
            $name = $user->nickname;
        }
        
        $log = [
                'mobile' => $user->mobile,
                'user_id' => $user->id,
                'user_type' => $user_type,
                'user_ip' => Request::getClientIp(),
                'source' => request_source(),
                'content' => "[{$name}] 登录app",
        ];
        
        LoginLogger::write($log);
    }
    
    /**
     * 处理用户注销事件。
     */
    public function onUserLogout($event)
    {
        $user = $event->user;
        $user_type = $event->user_type;
        
        $log = [
                'mobile' => $user['mobile'],
                'user_id' => $user['id'],
                'user_type' => $user_type,
                'user_ip' => Request::getClientIp(),
                'source' => request_source(),
                'content' => "[{$user['name']}] 登出app",
                ];
        
        LoginLogger::write($log);
    }
    

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        // 登录
        $events->listen(
                'App\Events\UserLoggedIn',
                'App\Listeners\LoginLogoutEventListener@onUserLogin'
        );
        // 注销
        $events->listen(
                'App\Events\UserLoggedOut',
                'App\Listeners\LoginLogoutEventListener@onUserLogout'
        );
    }
}
