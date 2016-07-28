<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use App\Salon\SupplierCache;
use App\Salon\BarberCache;

/**
 * 
 * 
 * @desc 用户下单的事件类，用来处理用户下单后的统计
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月9日
 */
class ConsumerOrderEvent extends Event
{
    use SerializesModels;
    
    /**
     * 评论的门店缓存信息
     * @var App\Salon\SupplierCache
     */
    public $supplierCache;
    
    /**
     * 评论的理发师缓存信息
     * @var BarberCache
     */
    public $barberCache;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order, $order_count)
    {
        $this->supplierCache = SupplierCache::where('supplier_id', $order->product->supplier_id)->first();
        $this->barberCache = BarberCache::where('barber_id', $order->product->barber_id)->first();
        $this->order_count = $order_count;// 下单的数量
    }

}
