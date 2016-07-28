<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use App\Salon\SupplierCache;
use App\Salon\BarberCache;

/**
 * 
 * 
 * @desc 用户评论的事件类，用来处理用户评论的标签统计工作
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月6日
 */
class ConsumerReviewEvent extends Event
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
    
    // 评论缓存内容
    public $review_tags;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $data, $review_tags = [])
    {
        $this->supplierCache = SupplierCache::where('supplier_id', $data['supplier_id'])->first();
        $this->barberCache = BarberCache::where('barber_id', $data['barber_id'])->first();
        $this->review_tags = $review_tags;
    }

}
