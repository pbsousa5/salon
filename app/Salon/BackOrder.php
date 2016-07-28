<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 退单的model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="BackOrder")
 */
class BackOrder extends Model
{
    
    //'0':'审核中','1':'成功', '2':'失败'
    const BACK_CHECK_STATUS = 0;
    const BACK_SUCCESS_STATUS = 1;
    const BACK_FAILED_STATUS = 2;
    /**
     * 表名
     * @var string
     */
    protected $table = 'back_orders';
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    //protected $fillable = [];
    
    /**
     * 不允许批量赋值的字段
     * @var array
     */
    protected $guarded = ['back_status'];
    
    #********
    #* 根据退单状况，获取不同类中的元素
    #* back_status : 0 审核中
    #* back_status : 1 退单成功
    #* back_status : 2 退单失败
    #********
    public function scopeBackStatus($query, $status)
    {
        return $query->whereBackStatus($status);
    }
    
    /**
     * 退单信息对应的订单信息
     * 1对1
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderInfo()
    {
        return $this->belongsTo('App\Salon\OrderInfo', 'order_info_id', 'id');
    }
    
    /**
     * 一个退单包含多个退单产品
     * 1对多
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function backProduct()
    {
        return $this->hasMany('App\Salon\BackProduct', 'back_order_id', 'id');
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
     * @SWG\Property(name="order_info_id", type="integer", description="订单主键")
     * @var int
     */
    private $order_info_id;
    
    /**
     * @SWG\Property(name="order_product_id", type="integer", description="订单产品主键")
     * @var int
     */
    private $order_product_id;
    
    /**
     * @SWG\Property(name="consumer_id", type="integer", description="消费者主键")
     * @var int
     */
    private $consumer_id;
    
    /**
     * @SWG\Property(name="trade_no", type="string", description="订单编号")
     * @var string
     */
    private $trade_no;
    
    /**
     * @SWG\Property(name="postscript", type="string", description="退单留言")
     * @var string
     */
    private $postscript;
    
    /**
     * @SWG\Property(name="back_fee", type="integer", description="退单金额，单位为分")
     * @var int
     */
    private $back_fee;
    
    /**
     * @SWG\Property(
     *  name="back_status",
     *  type="integer",
     *  description="退单状态",
     *  enum="{'0':'审核中','1':'成功', '2':'失败'}"
     * )
     * @var int
     */
    private $back_status;
    
    /**
     * @SWG\Property(name="bean_amount", type="integer", description="退换的积分数量，默认为0")
     * @var int
     */
    private $bean_amount;
    
    /**
     * @SWG\Property(name="consumer_coupon_id", type="integer", description="使用的优惠券id")
     * @var int
     */
    private $consumer_coupon_id;
    
    /**
     * @SWG\Property(name="consumer_name", type="string", description="退单人姓名")
     * @var string
     */
    private $consumer_name;
    
    /**
     * @SWG\Property(name="consumer_mobile", type="string", description="退单人联系方式")
     * @var string
     */
    private $consumer_mobile;
    
    /**
     * @SWG\Property(name="consumer_head", type="string", description="头像地址")
     * @var string
     */
    private $consumer_head;
}
