<?php

namespace App\Salon\Repositories\V2;

use App\Salon\ConsumerCoupon;
use App\Salon\Repositories\ConsumerCouponRepository as ConsumerCouponRep;
/**
 *
 *
 *
 * @desc 消费者优惠券数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class ConsumerCouponRepository extends ConsumerCouponRep
{
    /**
     *
     * 创建一个消费者优惠券数据仓库实例
     * @param App\Salon\ConsumerCoupon $consumerCoupon
     * @return void
     */
    public function __construct(ConsumerCoupon $consumerCoupon)
    {
        $this->model = $consumerCoupon;
    }

    /**
     * 更新或创建消费者优惠券
     * @param ConsumerCoupon $consumerCoupon 消费者优惠券model
     * @param array $inputs 更新的数据
     * @return ConsumerCoupon|null
     */
    protected function saveConsumerCoupon(ConsumerCoupon $consumerCoupon, array $inputs)
    {
        if (array_key_exists('consumer_id', $inputs)) {
            $consumerCoupon->consumer_id = $inputs['consumer_id'];
        }
        if (array_key_exists('coupon_id', $inputs)) {
            $consumerCoupon->coupon_id = $inputs['coupon_id'];
        }
        if (array_key_exists('status', $inputs)) {
            $consumerCoupon->status = $inputs['status'];
        }
        if (array_key_exists('deadline', $inputs)) {
            $consumerCoupon->deadline = $inputs['deadline'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $consumerCoupon->created_at = $inputs['created_at'];
        }
        
        if ($consumerCoupon->save()) {
            return $consumerCoupon;
        }
        
        return null;
    }    
}