<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 用户优惠券model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="ConsumerCoupon")
 */
class ConsumerCoupon extends Model
{
    const COUPON_STATUS_NOT_USE = 0;#优惠券未使用
    const COUPON_STATUS_USEED = 1;#已使用
    const COUPON_STATUS_EXPIRE = 2;#已过期
    
    /**
     * 表名
     * @var string
     */
    protected $table = 'consumer_coupons';
    
    /**
     * 关闭自动更新时间戳
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [];
    
    /**
     * 不允许批量赋值的字段
     * @var array
     */
    protected $guarded = ['status'];
    
    /**
     * 一个关系对应一张优惠券
     * 
     */
    public function coupon()
    {
        return $this->belongsTo('App\Salon\Coupon', 'coupon_id', 'id');
    }
    
    // ========================================
    // model的属性
    //=========================================
    /**
     * @SWG\Property(name="id", type="integer", description="主键")
     * @var int
     */
    private $id;
    
    /**
     * @SWG\Property(name="consumer_id", type="integer", description="消费者id")
     * @var int
     */
    private $consumer_id;
    
    /**
     * @SWG\Property(name="coupon_id", type="integer", description="优惠券id")
     * @var int
     */
    private $coupon_id;
    
    /**
     * @SWG\Property(
     *  name="status",
     *  type="integer",
     *  description="状态",
     *  enum="{'0':'未使用','1':'已使用', '2':'过期'}"
     * )
     * @var int
     */
    private $status;
    
    /**
     * @SWG\Property(
     *  name="deadline",
     *  type="integer",
     *  description="如果coupon中的valid_term是天数，则该处是当前时间戳+天数，否则与valid_term一致"
     * )
     * @var int
     */
    private $deadline;
    
    /**
     * @SWG\Property(name="coupon", type="Coupon", description="优惠券信息")
     * @var unknown
     */
    private $coupon;
}
