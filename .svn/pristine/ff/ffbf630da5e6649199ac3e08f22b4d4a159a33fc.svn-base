<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Salon\OrderProduct;

/**
 * 
 * 
 * @desc 订单监听事件
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月3日
 */
class OrderEventListener
{
    /**
     * 向用户发送消息的服务层
     * @var App\Salon\Services\NotifyService
     */
    protected $ser;
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }
    
    /**
     * 免费订单的操作，直接生产可用订单
     * 
     */
    public function onFreeOrder($event)
    {
        // 更新订单
        $orderInfo = $event->orderInfo;
        $orderInfo->pay_status = 1;
        $orderInfo->order_status = 1;
        $orderInfo->pay_name = '免费订单';
        $orderInfo->pay_code = 'FREE_ORDER';
        $orderInfo->re_trade_no = $orderInfo->trade_no;
        $orderInfo->re_cash_fee = $orderInfo->pay_fee;//值一定为0
        $orderInfo->re_payment_time =  date("Y-m-d h:i:s", time());
        $orderInfo->save();
        
        // 更新订单产品
        OrderProduct::where('order_info_id', $orderInfo->id)->update(['product_status'=>1]);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        // 免费订单
        $events->listen(
                'App\Events\OrderFreeEvent',
                'App\Listeners\OrderEventListener@onFreeOrder'
        );
    }
}
