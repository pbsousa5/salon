<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 订单信息model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 *
 * @SWG\Model(id="OrderInfo")
 */
class OrderInfo extends Model
{
    // '0':'过期','1':'正常','2':'取消'，'3':'门店删除','4':'用户删除'
    const ORDER_STATUS_EXPIRE = 0;
    const ORDER_STATUS_NORMAL = 1;
    const ORDER_STATUS_CANCEL = 2;
    const ORDER_STATUS_DEL_SUPPLIER = 3;
    const ORDER_STATUS_DEL_CONSUMER = 4;
    
    /**
     * 表名
     * @var string
     */
    protected $table = 'order_infos';
    
    /**
     * 不允许批量赋值的字段
     * @var array
     */
    protected $guarded = [
            'order_status',
            'pay_status',
            'review_status',
            're_trade_no',
            're_cash_fee',
            're_payment_time',
    ];
    
    /**
     * 订单对应的产品信息
     * 
     * 1对n，一个订单对应多个产品
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProducts()
    {
        return $this->hasMany('App\Salon\OrderProduct', 'order_info_id', 'id');
    }
    
    /**
     * 订单对应的退单
     * 1对1，一个订单对应一个退单
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function backOrder()
    {
        return $this->hasOne('App\Salon\BackOrder', 'order_info_id', 'id');
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
     * @SWG\Property(name="trade_no", type="string", description="唯一订单号")
     * @var string
     */
    private $trade_no;
    
    /**
     * @SWG\Property(name="consumer_id", type="integer", description="消费者id,需要")
     * @var int
     */
    private $consumer_id;
    
    /**
     * @SWG\Property(name="postscript", type="string", description="下单时的留言,需要")
     * @var string
     */
    private $postscript;
    
    /**
     * @SWG\Property(
     *  name="order_status",
     *  type="integer",
     *  description="订单状态",
     *  enum="{'0':'过期','1':'正常','2':'取消','3':'门店删除','4':'用户删除'}"
     * )
     * @var int
     */
    private $order_status;
    
    /**
     * @SWG\Property(
     *  name="pay_status",
     *  type="integer",
     *  description="支付状态",
     *  enum="{'0':'未支付','1':'已支付'}"
     * )
     * @var int
     */
    private $pay_status;
    
    /**
     * @SWG\Property(
     *  name="review_status",
     *  type="integer",
     *  description="支付状态",
     *  enum="{'0':'未评论','1':'已评论'}"
     * )
     * @var int
     */
    private $review_status;
    
    /**
     * @SWG\Property(name="consumer_coupon_id", type="integer", description="用户优惠券id,需要(如果未使用，则传0)")
     * @var int
     */
    private $consumer_coupon_id;
    
    /**
     * @SWG\Property(name="coupon_face_fee", type="integer", description="优惠券面值金额，单位为分")
     * @var int
     */
    private $coupon_face_fee;
    
    /**
     * @SWG\Property(name="bean_amount", type="integer", description="消费的积分数量,需要(未消费时传0)")
     * @var int
     */
    private $bean_amount;
    
    /**
     * @SWG\Property(name="bean_fee", type="integer", description="积分折现后的价值")
     * @var int
     */
    private $bean_fee;
    
    /**
     * @SWG\Property(name="original_fee", type="integer", description="产品的原价")
     * @var int
     */
    private $original_fee;
    
    /**
     * @SWG\Property(name="pay_fee", type="integer", description="实际支付的价格")
     * @var int
     */
    private $pay_fee;
    
    /**
     * @SWG\Property(name="advance_time", type="integer", description="预约的时间,需要")
     * @var int
     */
    private $advance_time;
    
    /**
     * @SWG\Property(name="pay_name", type="string", description="支付的名称")
     * @var string
     */
    private $pay_name;
    
    /**
     * @SWG\Property(name="pay_code", type="string", description="支付方式的代码")
     * @var string
     */
    private $pay_code;
    
    /**
     * @SWG\Property(name="re_trade_no", type="string", description="第三方返回的交易码")
     * @var string
     */
    private $re_trade_no;
    
    /**
     * @SWG\Property(name="re_cash_fee", type="integer", description="第三方平台返回的金额")
     * @var int
     */
    private $re_cash_fee;
    
    /**
     * @SWG\Property(name="re_payment_time", type="string", description="用户支付的时间")
     * @var string
     */
    private $re_payment_time;
    
    /**
     * @SWG\Property(name="consumer_name", type="string", description="购买者名称")
     * @var string
     */
    private $consumer_name;
    
    /**
     * @SWG\Property(name="consumer_mobile", type="string", description="购买电话号码,需要")
     * @var string
     */
    private $consumer_mobile;
    
    /**
     * @SWG\Property(name="consumer_head", type="string", description="购买者头像")
     * @var string
     */
    private $consumer_head;
    
    /**
     * @SWG\Property(name="products", type="array", items="$ref:OrderProduct", description="购买者头像")
     * @var unknown
     */
    private $order_product;
}
