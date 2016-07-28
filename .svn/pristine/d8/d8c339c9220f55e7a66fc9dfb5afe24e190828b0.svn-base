<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

    ];
    
    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
            'App\Listeners\SupplierBehaviorListener',

            'App\Listeners\PushEventListener',#百度推送监听
            'App\Listeners\OrderEventListener',#订单相关的监听
            'App\Listeners\UserNotifyEventListener',#用户通知
            'App\Listeners\ConsumerBehaviorEventListener',#用户行为事件监听
            'App\Listeners\LoginLogoutEventListener',#登入登出事件监听
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
