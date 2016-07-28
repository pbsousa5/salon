<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Request;
use Illuminate\Support\Str;
use App\Salon\SupplierCache;
use App\Salon\BarberCache;
use App\Salon\OrderInfo;
use App\Salon\Logger\ConsumeLogger;
use App\Salon\Logger\IncomeLogger;
use App\Salon\AvailReview;
use App\Salon\Consumer;
use App\Salon\Coupon;
use App\Salon\ConsumerCoupon;
use App\Salon\ConsumeLog;

/**
 * 
 * 
 * @desc 用户行为事件监听器
 * @author helei <helei@bnersoft.com>
 * @date 2015年9月1日
 */
class ConsumerBehaviorEventListener
{
    /**
     * 处理用户评论，更新评论门店的标签数
     * 
     * @param mixed $event 事件
     */
    public function onConsumerReview($event)
    {
        $review_tags = $event->review_tags;
        
        $supplierCache = $event->supplierCache;
        $this->updateSupplierCache($supplierCache, $review_tags);
        
        $barberCache = $event->barberCache;
        if (!is_null($barberCache)) {
            $this->updateBarberCache($barberCache, $review_tags);
        }
    }
    
    /**
     * 更新门店的缓存
     * 
     * @param SupplierCache $supplierCache 门店缓存
     * @param array $reviewTags 评论内容
     */
    private function updateSupplierCache($supplierCache, $review_tags)
    {
        $old_tags = unserialize($supplierCache->tags);
        $old_count = unserialize($supplierCache->count);
        // 统计标签数组个数
        foreach ($review_tags as $val) {
            if (array_key_exists($val, $old_tags)) {
                $t = $old_tags[$val];
                $old_tags[$val] += 1;
            } else {
                $old_tags[$val] = 1;
            }
        }
        
        // 修改统计数据值
        $old_count['review'] += 1;
         
        $supplierCache->tags = serialize($old_tags);
        $supplierCache->count = serialize($old_count);
        
        return $supplierCache->save();
    }
    
    /**
     * 更新理发师的缓存
     *
     * @param BarberCache $barberCache 理发师缓存
     * @param array $reviewTags 评论内容
     */
    private function updateBarberCache($barberCache, $review_tags)
    {
        $old_reviews = unserialize($barberCache->reviews);
        $old_tags = isset($old_reviews['tags']) ? $old_reviews['tags'] : [];
        $old_count = unserialize($supplierCache->count);
        // 统计数组个数
        foreach ($review_tags as $val) {
            if (array_key_exists($val, $old_tags)) {
                $t = $old_tags[$val];
                $old_tags[$val] += 1;
            } else {
                $old_tags[$val] = 1;
            }
        }
        $old_reviews['tags'] = $old_tags;
        
        // 修改统计数据值
        $old_count['review'] += 1;
         
        $barberCache->reviews = serialize($old_reviews);
        $barberCache->count = serialize($old_count);
        return $barberCache->save();
    }
    
    /**
     * 用户下单，监听事件处理程序
     * 
     */
    public function onConsumerOrder($event)
    {
        // 订单变化数量
        $order_count = $event->order_count;
        
        $supplierCache = $event->supplierCache;
        $count = unserialize($supplierCache->count);
        $count['order'] += $order_count;
        $supplierCache->count = serialize($count);
        $supplierCache->save();
        
        $barberCache = $event->barberCache;
        if (!is_null($barberCache)) {
            $count = unserialize($barberCache->count);
            $count['order'] += $order_count;
            $barberCache->count = serialize($count);
            $barberCache->save();
        }
    }
    
    /**
     * 用户优惠券优惠券过期
     *
     */
    public function onCouponExpire($event)
    {
        $consumerCoupon = $event->consumerCoupon;
        $consumerCoupon->status = 2;
        
        // 减少用户的优惠券数量
        $consumer = Consumer::where('id', $consumerCoupon->consumer_id)->first();
        $consumer->my_coupon -= 1;
        $consumer->save();
        
        return $consumerCoupon->save();
    }
    
    /**
     *
     * 订单过期
     */
    public function onOrderExpire($event)
    {
        $orderInfo = $event->orderInfo;
        
        $orderInfo->order_status = OrderInfo::ORDER_STATUS_EXPIRE;
        
        return $orderInfo->save();
    }
    
    /**
     * 用户消费事件
     */
    public function onUserConsume($event)
    {
        $orderProduct = $event->orderProduct;
        
        $consume_log = [
                'order_info_id' => $orderProduct->order_info_id,
                'order_product_id' => $orderProduct->id,
                'consumer_id' => $orderProduct->consumer_id,
                'supplier_id' => $orderProduct->supplier_id,
                'barber_id' => $orderProduct->barber_id,
                'created_at' => time(),
        ];
        
        $income_log = [
                'supplier_id' => $orderProduct->supplier_id,
                'barber_id' => $orderProduct->barber_id,
                'consumer_id' => $orderProduct->consumer_id,
                'order_info_id' => $orderProduct->order_info_id,
                'trade_no' => $orderProduct->orderInfo->trade_no,
                'cash_fee' => $orderProduct->sign_price,#平台应向门店支付的金额
                'pay_fee' => $orderProduct->pay_price,#实际收到的金额
        ];
        
        IncomeLogger::write($income_log);
        ConsumeLogger::write($consume_log);
        
        // 更新缓存信息
        $supplierCache = SupplierCache::where('supplier_id', $orderProduct->supplier_id)->first();
        $count = unserialize($supplierCache->count);
        $count['fund'] += $orderProduct->sign_price;
        
        // 检查该用户是否已经是消费者
        $flag = ConsumeLog::where('consumer_id', $orderProduct->consumer_id)->count();
        if ($flag == 1) {// 说明目前该用户还不是客户
            $count['consumer'] += 1;
        }
        
        $supplierCache->count = serialize($count);
        $supplierCache->save();
        
        $barberCache = BarberCache::where('barber_id', $orderProduct->barber_id)->first();
        if (!is_null($barberCache)) {
            $count = unserialize($barberCache->count);
            $count['fund'] += $orderProduct->sign_price;
            
            // 检查理发师是否已经记录
            $flag = ConsumeLog::where('consumer_id', $orderProduct->consumer_id)->count();
            if ($flag == 1) {
                $count['consumer'] += 1;
            }
            $barberCache->count = serialize($count);
            $barberCache->save();
        }
        
        // 消费成功时，根据消费金额，返给用户美发币
        $consumer = Consumer::where('id', $orderProduct->consumer_id)->first();
        $pay_fee = $orderProduct->pay_price;
        if ($pay_fee>10000 && $pay_fee<=30000) {
            $consumer->my_bean += 20;
        } elseif ($pay_fee>30000 && $pay_fee<=50000) {
            $consumer->my_bean += 50;
        } elseif ($pay_fee>50000 && $pay_fee<=100000) {
            $consumer->my_bean += 100;
        } elseif ($pay_fee>100000 && $pay_fee<=190000) {
            $consumer->my_bean += 200;
        } elseif ($pay_fee>190000) {
            $consumer->my_bean += 400;
        }
        
        $consumer->save();
    }
    
    /**
     * 处理用户积分变化
     *
     * @param mixed $event 事件
     */
    public function onConsumerBean($event)
    {
        $consumer = $event->consumer;
        $consumer->my_bean = $consumer->my_bean + $event->use_bean_count;
        
        return $consumer->save();
    }
    
    /**
     * 用户关注事件
     *
     * @param mixed $event 事件
     */
    public function onConsumerFollower($event)
    {
        $user_id = $event->user_id;
        $user_type = $event->user_type;
        
        if (Str::equals('supplier', $user_type)) {
            $cache = SupplierCache::where('supplier_id', $user_id)->first();
        } elseif (Str::equals('barber', $user_type)) {
            $cache = BarberCache::where('barber_id', $user_id)->first();
        }
        
        $cache->followers += 1;
        $cache->save();
    }
    
    /**
     * 用户点击有用时，增加用户等级积分。最多增加10分。同时增加用户被赞次数
     *
     * @param mixed $event 事件
     */
    public function onAddScore($event)
    {
        $consumer = $event->consumer;
        $review_id = $event->review_id;
        
        // 更新用户被赞次数
        $consumer->helpful_count += 1;
        $consumer->save();
        
        // 检查用户已经有多少人点赞了。
        $count = AvailReview::where('review_id', $review_id)->count();
        if ($count < 10) {
            $consumer->level_score += 1;
            $consumer->save();
        }
    }
    
    /**
     * 用户注册成功，增送用户优惠券
     *
     * @param mixed $event 事件
     */
    public function onConsumerRegister($event)
    {
        $coupon_id = config('appinit.reg_coupon_id');
        $coupon = Coupon::where('id', $coupon_id)->first();// 查找要保存的优惠券信息
        if (!is_null($coupon)) {
            $consumer = $event->consumer;
            $consumer->my_coupon = 1;
            
            $consumer->save();
            
            // 增加优惠券
            $user_coupon = [
                    'consumer_id' => $consumer->id,
                    'coupon_id' => $coupon_id,
                    'deadline' => $coupon->valid_term,
                    'created_at' => date('Y-m-d H:i:s', time()),
            ];
            ConsumerCoupon::create($user_coupon);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        // 用户评论
        $events->listen(
                'App\Events\ConsumerReviewEvent',
                'App\Listeners\ConsumerBehaviorEventListener@onConsumerReview'
        );
        // 用户优惠券过期
        $events->listen(
                'App\Events\CouponExpireEvent',
                'App\Listeners\ConsumerBehaviorEventListener@onCouponExpire'
        );
        // 用户购买的订单过期
        $events->listen(
                'App\Events\OrderExpireEvent',
                'App\Listeners\ConsumerBehaviorEventListener@onOrderExpire'
        );
        // 用户消费
        $events->listen(
                'App\Events\UserConsumeEvent',
                'App\Listeners\ConsumerBehaviorEventListener@onUserConsume'
        );
        // 用户积分变化
        $events->listen(
                'App\Events\ConsumerBeanEvent',
                'App\Listeners\ConsumerBehaviorEventListener@onConsumerBean'
        );
        // 用户下单
        $events->listen(
                'App\Events\ConsumerOrderEvent',
                'App\Listeners\ConsumerBehaviorEventListener@onConsumerOrder'
        );
        // 用户关注门店或者理发师
        $events->listen(
                'App\Events\ConsumerFollowerEvent',
                'App\Listeners\ConsumerBehaviorEventListener@onConsumerFollower'
        );
        // 用户点击有用后，增加用户等级积分
        $events->listen(
                'App\Events\MarkUserfulAddScoreEvent',
                'App\Listeners\ConsumerBehaviorEventListener@onAddScore'
        );
        // 用户注册成功，赠送优惠券
        $events->listen(
                'App\Events\ConsumerRegEvent',
                'App\Listeners\ConsumerBehaviorEventListener@onConsumerRegister'
        );
    }
}
