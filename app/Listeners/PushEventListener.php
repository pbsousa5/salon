<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Request;
use Illuminate\Support\Str;
use Pusher;
use Cache;
use App\Salon\OrderInfo;
use App\Salon\OrderProduct;
use Riverslei\Pusher\Libary\BaseSDK;

/**
 * 
 * 
 * @desc 消息推送监听器
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月12日
 */
class PushEventListener
{
    
    /**
     * 配置信息
     * @var array
     */
    private $config;
    
    public function __construct()
    {
        $this->config = config('pusher');
    }
    
    /**
     * 处理用户下订单消息推送
     * 
     * @param mixed $event 事件
     */
    public function onOrderPush($event)
    {
        $orderInfo = $event->orderInfo;
        
        // 向门店、理发师发送推送消息
        $products = $orderInfo->orderProducts;
        
        foreach ($products as $key => $product) {
            $supplier_id = $product->supplier_id;
            $supplierKey = 'supplier' . $supplier_id;
            if (Cache::has($supplierKey)) {
                $supplierCache = Cache::get($supplierKey);
                $supplier_channel_id = $supplierCache['channel_id'];
                $supplier_source = $supplierCache['source'];
                
                $barberKey = 'barber'.$product->barber_id;
                $barberCache = Cache::get($barberKey);
                $barber_channel_id = $barberCache['channel_id'];
                $barber_source = $barberCache['source'];
                
                $fee = bcdiv($orderInfo->pay_fee, 100, 2);
                if ($supplier_channel_id) {
                    if (Str::equals('android', strtolower($supplier_source))) {
                        $msg = [
                                'title' => '订单支付成功',
                                'description' => "{$orderInfo->consumer_name} 订购了您店的 {$product->product_name},金额共计{$fee}元",
                        ];
                        $this->pushAndroidSupplier($supplierCache, $msg);
                    } elseif (Str::equals('ios', strtolower($supplier_source))) {
                        $msg = [
                                "aps" => [
                                        "alert" => "{$orderInfo->consumer_name} 订购了您店的 {$product->product_name},金额共计{$fee}元",
                                ],
                        ];
                        $this->pushIosSupplier($supplierCache, $msg);
                    }
                }
                if ($barber_channel_id) {
                    if (Str::equals('anroid', strtolower($barber_source))) {
                        $msg = [
                                'title' => '订单支付成功',
                                'description' => "{$orderInfo->consumer_name} 订购了您的 {$product->product_name},金额共计{$fee}元",
                        ];
                        $this->pushAndroidBarber($barberCache, $msg);
                    } elseif (Str::equals('ios', strtolower($barber_source))) {
                        $msg = [
                                "aps" => [
                                        "alert" => "{$orderInfo->consumer_name} 订购了您的 {$product->product_name},金额共计{$fee}元",
                                ],
                        ];
                        $this->pushIosBarber($barberCache, $msg);
                    }
                }
            }
        }
    }
    
    /**
     * 处理用户退单消息推送
     *
     * @param mixed $event 事件
     */
    public function onRetOrderPush($event)
    {
        $backOrder = $event->backOrder;
        
        // 向门店、理发师发送推送消息
        $products = $backOrder->backProduct;
        
        foreach ($products as $key => $product) {
            $supplier_id = $product->supplier_id;
            $supplierKey = 'supplier' . $supplier_id;
            if (Cache::has($supplierKey)) {
                $supplierCache = Cache::get($supplierKey);
                $supplier_channel_id = $supplierCache['channel_id'];
                $supplier_source = $supplierCache['source'];
                
                $barberKey = 'barber'.$product->barber_id;
                $barberCache = Cache::get($barberKey);
                $barber_channel_id = $barberCache['channel_id'];
                $barber_source = $barberCache['source'];
                
                $fee = bcdiv($orderInfo->pay_fee, 100, 2);
                if ($supplier_channel_id) {
                    if (Str::equals('android', strtolower($supplier_source))) {
                        $msg = [
                                'title' => '有新的退单信息',
                                'description' => "{$backOrder->consumer_name} 申请退款您店的 {$product->product_name},金额共计{$fee}元",
                        ];
                        $this->pushAndroidSupplier($supplierCache, $msg);
                    } elseif (Str::equals('ios', strtolower($supplier_source))) {
                        $msg = [
                                "aps" => [
                                        "alert" => "{$backOrder->consumer_name} 申请退款您店的 {$product->product_name},金额共计{$fee}元",
                                ],
                        ];
                        $this->pushIosSupplier($supplierCache, $msg);
                    }
                }
                if ($barber_channel_id) {
                    if (Str::equals('anroid', strtolower($barber_source))) {
                        $msg = [
                                'title' => '有新的退单信息',
                                'description' => "{$backOrder->consumer_name} 申请退您的 {$product->product_name},金额共计{$fee}元",
                        ];
                        $this->pushAndroidBarber($barberCache, $msg);
                    } elseif (Str::equals('ios', strtolower($barber_source))) {
                        $msg = [
                                "aps" => [
                                        "alert" => "{$backOrder->consumer_name} 申请退您的 {$product->product_name},金额共计{$fee}元",
                                ],
                        ];
                        $this->pushIosBarber($barberCache, $msg);
                    }
                }
            }
        }
    }
    
    /* {
     "title" : "hello" ,
     "description": "hello world" //必选
     "notification_builder_id": 0, //可选
     "notification_basic_style": 7, //可选
     "open_type":0, //可选
     "url": "http://developer.baidu.com", //可选
     "pkg_content":"", //可选
     "custom_content":{"key":"value"},
     } */
    /**
     * 向使用android手机的门店推送订单支付成功信息
     * 
     * @param array $supplierCache 门店缓存信息
     * @param array 推送的消息内容
     */
    private function pushAndroidSupplier($supplierCache, $msg)
    {
        $opts = array(
                'msg_type' => 1,
        );
        
        Pusher::setApiKey($this->config['supplier_android_apiKey']);
        Pusher::setSecretKey($this->config['supplier_android_secretKey']);
        Pusher::setDeviceType(BaseSDK::DEVICE_ANDROID);// 兼容老版本
        Pusher::pushMsgToSingleDevice($supplierCache['channel_id'], $msg, $opts);
    }
    
    /**
     * 向使用android手机的理发师推送订单支付成功信息
     *
     * @param OrderInfo $orderInfo 订单model
     * @param OrderProduct $orderProduct 订单产品
     * @param array $supplierCache 门店缓存信息
     */
    private function pushAndroidBarber($orderInfo, $orderProduct, $barberCache)
    {
        $opts = array(
                'msg_type' => 1,
        );
    
        Pusher::setApiKey($this->config['consumer_android_apiKey']);
        Pusher::setSecretKey($this->config['consumer_android_secretKey']);
        Pusher::setDeviceType(BaseSDK::DEVICE_ANDROID);// 兼容老版本
        Pusher::pushMsgToSingleDevice($barberCache['channel_id'], $msg, $opts);
    }
    
    /* {
        "aps": {  
             "alert":"Message From Baidu Cloud Push-Service",
             "sound":"",  //可选
              "badge":0,    //可选
        },
        "key1":"value1",
        "key2":"value2"
    } */
    /**
     * 向使用ios手机的门店推送订单支付成功信息
     *
     * @param array $supplierCache 门店缓存信息
     * @param array $msg 推送的消息内容
     */
    private function pushIosSupplier($supplierCache, $msg)
    {
        $opts = array(
                'msg_type' => 1,
        );
    
        Pusher::setApiKey($this->config['supplier_ios_apiKey']);
        Pusher::setSecretKey($this->config['supplier_ios_secretKey']);
        Pusher::setDeviceType(BaseSDK::DEVICE_IOS);// 兼容老版本
        Pusher::pushMsgToSingleDevice($supplierCache['channel_id'], $msg, $opts);
    }
    
    /**
     * 向使用android手机的理发师推送订单支付成功信息
     *
     * @param array $barberCache 理发师缓存信息
     * @param array $msg 推送的消息内容
     */
    private function pushIosBarber($orderInfo, $msg)
    {
        $opts = array(
                'msg_type' => 1,
        );
    
        Pusher::setApiKey($this->config['consumer_ios_apiKey']);
        Pusher::setSecretKey($this->config['consumer_ios_secretKey']);
        Pusher::setDeviceType(BaseSDK::DEVICE_IOS);// 兼容老版本
        Pusher::pushMsgToSingleDevice($barberCache['channel_id'], $msg, $opts);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        // 下订单消息推送
        $events->listen(
                'App\Events\OrderPushEvent',
                'App\Listeners\PushEventListener@onOrderPush'
        );
        // 退单消息推送
        $events->listen(
                'App\Events\RetOrderPushEvent',
                'App\Listeners\PushEventListener@onRetOrderPush'
        );
    }
}
