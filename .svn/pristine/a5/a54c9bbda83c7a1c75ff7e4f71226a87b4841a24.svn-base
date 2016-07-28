<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 订单中产品model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 *
 * @SWG\Model(id="OrderProduct")
 */
class OrderProduct extends Model
{
    // '0':'尚未支付','1':'支付未使用','2':'支付已使用','3':'退款处理'
    const PRODUCT_STATUS_NOT_PAY = 0;
    const PRODUCT_STATUS_CAN_USE = 1;
    const PRODUCT_STATUS_USED = 2;
    const PRODUCT_STATUS_REFUND = 3;
    
    /**
     * 表名
     * @var string
     */
    protected $table = 'order_products';
    
    /**
     * 不允许批量赋值的字段
     * @var array
     */
    protected $guarded = [
            'product_status',
    ];
    
    /**
     * 产品对应的订单信息
     * @return Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function orderInfo()
    {
        return $this->belongsTo('App\Salon\OrderInfo', 'order_info_id', 'id');
    }
    
    /**
     * 产品对应的门店信息
     * @return Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function supplier()
    {
        return $this->belongsTo('App\Salon\Supplier', 'supplier_id', 'id');
    }
    
    /**
     * 产品对应的理发师信息
     * @return Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function barber()
    {
        return $this->belongsTo('App\Salon\Barber', 'barber_id', 'id');
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
     * @SWG\Property(name="order_info_id", type="integer", description="订单id")
     * @var int
     */
    private $order_info_id;
    
    /**
     * @SWG\Property(name="consumer_id", type="integer", description="用户id")
     * @var int
     */
    private $consumer_id;
    
    /**
     * @SWG\Property(name="product_id", type="integer", description="对应产品id,需要")
     * @var int
     */
    private $product_id;
    
    /**
     * @SWG\Property(name="supplier_id", type="integer", description="门店id，需要")
     * @var int
     */
    private $supplier_id;
    
    /**
     * @SWG\Property(name="barber_product_id", type="integer", description="理发师的产品id，当product_id=0时，才能设置该值")
     * @var int
     */
    private $barber_product_id;
    
    /**
     * @SWG\Property(name="barber_id", type="integer", description="理发师id，当barber_product_id != 0 时才能设置该值")
     * @var int
     */
    private $barber_id;
    
    /**
     * @SWG\Property(name="category_name", type="string", description="分类名称，需要")
     * @var string
     */
    private $category_name;
    
    /**
     * @SWG\Property(name="product_name", type="string", description="产品的名称")
     * @var string
     */
    private $product_name;
    
    /**
     * @SWG\Property(name="product_desc", type="string", description="产品的描述")
     * @var string
     */
    private $product_desc;
    
    /**
     * @SWG\Property(name="original_price", type="integer", description="产品原价")
     * @var int
     */
    private $original_price;
    
    /**
     * @SWG\Property(name="pay_price", type="integer", description="产品的售价")
     * @var int
     */
    private $pay_price;
    
    /**
     * @SWG\Property(name="good_number", type="integer", description="商品的数量,需要")
     * @var int
     */
    private $good_number;
    
    /**
     * @SWG\Property(
     *  name="is_action",
     *  type="integer",
     *  description="产品是否属于活动",
     *  enum="{'0':'不属于','1':'属于'}"
     * )
     * @var int
     */
    private $is_action;
    
    /**
     * @SWG\Property(
     *  name="is_real",
     *  type="integer",
     *  description="是否是实物",
     *  enum="{'0':'不是','1':'是'}"
     * )
     * @var int
     */
    private $is_real;
    
    /**
     * @SWG\Property(
     *  name="is_back",
     *  type="integer",
     *  description="是否能够进行退货操作",
     *  enum="{'0':'不可以','1':'可以'}"
     * )
     * @var int
     */
    private $is_back;
    
    /**
     * @SWG\Property(name="consume_code", type="string", description="消费码")
     * @var string
     */
    private $consume_code;
    
    /**
     * @SWG\Property(
     *  name="product_status",
     *  type="integer",
     *  description="是否可以进行消费，如果退款失败，状态会变为支付未使用",
     *  enum="{'0':'尚未支付','1':'支付未使用','2':'支付已使用','3':'退款处理'}"
     * )
     * @var int
     */
    private $product_status;
}
