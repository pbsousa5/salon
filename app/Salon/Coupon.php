<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 优惠券model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="Coupon")
 */
class Coupon extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'coupons';
    
    /**
     * 关闭自动更新时间戳
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
            'face_fee',
            'valid_term',
            'full_cat',
            'configs',
    ];
    
    /**
     * 获取该优惠券对应的消费者信息
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consumers()
    {
        return $this->belongsToMany('App\Salon\Consumer', 'consumer_coupons', 'consumer_id', 'coupon_id');
    }
    
    /**
     * 优惠券与用户优惠券关系表是1对n关系
     * 1张优惠券可以属于多个关系，一个关系只能有一张优惠券
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function consumerCoupons()
    {
        return $this->hasMany('App\Salon\ConsumerCoupon', 'coupon_id', 'id');
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
     * @SWG\Property(name="face_fee", type="integer", description="优惠券面值")
     * @var int
     */
    private $face_fee;
    
    /**
     * @SWG\Property(name="valid_term", type="integer", description="到期时间")
     * @var int
     */
    private $valid_term;
    
    /**
     * @SWG\Property(name="full_cat", type="integer", description="满多少减")
     * @var int
     */
    private $full_cat;
    
    /**
     * @SWG\Property(name="configs", type="string", description="其他条件,使用json格式保存")
     * @var string
     */
    private $configs;
}
