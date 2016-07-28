<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use App\Salon\ConsumerCoupon;

/**
 * 
 * 
 * @desc 优惠券过期事件
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月2日
 */
class CouponExpireEvent extends Event
{
    use SerializesModels;
    
    /**
     * 消费者的优惠券model
     * @var App\Salon\ConsumerCoupon
     */
    public $consumerCoupon;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ConsumerCoupon $consumerCoupon)
    {
        $this->consumerCoupon = $consumerCoupon;
    }

}
