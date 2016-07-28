<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Salon\Notify;
use App\Salon\Logger\NotifyLogger;
use App\Salon\Consumer;

/**
 * 
 * 
 * @desc 用户消息监听
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月3日
 */
class UserNotifyEventListener
{

    
    public function onConsumerGetCoupon($event)
    {

    }
    
    /**
     * 用户阅读消息
     * 
     */
    public function onUserReadNotify($event)
    {
        $user_id = $event->user_id;
        $user_type = $event->user_type;
        
        return Notify::where(['user_id'=>$user_id, 'user_type'=>$user_type])->update(['is_read'=>1]);
    }
    
    /**
     * 用户下单后，发送消息到用户，与用户所订购的门店
     * 
     */
    public function onPayOrder($event)
    {
        $orderInfo = $event->orderInfo;
        $fee = bcdiv($orderInfo->pay_fee, 100, 2);
        
        // 向用户发送消息
        $consumer_notify = [
                'user_id' => $orderInfo->consumer_id,
                'user_type' => 'consumer',
                'title' => '新的订单',
                'push_msg' => "本次支付总金额：{$fee}元",
                'is_read' => 0,
                'notify_type' => Notify::NOTIFY_TYPE_ORDER,
        ];
        
        // 向门店或者理发师发送消息
        $orderProduct = $orderInfo->orderProducts()->select('supplier_id', 'barber_id', 'product_name')->first();
        if ($orderProduct->barber_id != 0) {
            $user_type = 'barber';
            $user_id = $orderProduct->barber_id;
        } else {
            $user_type = 'supplier';
            $user_id = $orderProduct->supplier_id;
        }
        
        $seller_notify = [
                'user_id' => $user_id,
                'user_type' => $user_type,
                'title' => '新的订单',
                'push_msg' => "{$orderInfo->consumer_name} 购买了您的 {$orderProduct->product_name}",
                'is_read' => 0,
                'notify_type' => Notify::NOTIFY_TYPE_ORDER,
        ];
        
        
        NotifyLogger::write($consumer_notify);
        NotifyLogger::write($seller_notify);
        
        // 用户支付成功，用户权重值增加3
        $consumer = Consumer::where('id', $orderInfo->consumer_id)->first();
        $consumer->weight += 3;
        $consumer->save();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        // 订单支付成功的消息
        $events->listen(
                'App\Events\NotifyPayOrderEvent',
                'App\Listeners\UserNotifyEventListener@onPayOrder'
        );
        // 用户获取消息列表时，更新所有消息为已读
        $events->listen(
                'App\Events\UserReadNotifyEvent',
                'App\Listeners\UserNotifyEventListener@onUserReadNotify'
        );
    }
}
