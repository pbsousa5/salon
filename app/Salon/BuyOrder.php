<?php

namespace App\Salon;

/**
 * 
 * 
 * @desc 下订单提交的 model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="BuyOrder")
 */
class BuyOrder
{
    /**
     * @var int
     * @SWG\Property(name="postscript", type="string", description="用户的留言")
     */
     
    /**
     * @var int
     * @SWG\Property(name="consumer_coupon_id", type="integer", description="用户优惠券id")
     */
     
    /**
     * @var int
     * @SWG\Property(
     *  name="is_user_bean",
     *  type="integer",
     *  description="是否使用美发币",
     *  enum="{'0':'不使用','1':'使用'}"
     * )
     */
     
    /**
     * @var int
     * @SWG\Property(name="advance_time", type="string", description="预约时间")
     */
     
    /**
     * @var int
     * @SWG\Property(name="consumer_mobile", type="string", description="用户手机号码")
     */
        
    /**
     * @var int
     * @SWG\Property(name="products", type="array", items="$ref:ListProduct", description="用户手机号码")
     */
}

/**
 * @SWG\Model(id="ListProduct")
 */
class ListProduct
{
    /**
     * @var int
     * @SWG\Property(name="supplier_id", type="integer", description="门店id")
     */
    
    /**
     * @var int
     * @SWG\Property(name="product_id", type="integer", description="产品id")
     */
    
    /**
     * @var int
     * @SWG\Property(name="barber_id", type="integer", description="理发师id")
     */
    
    /**
     * @var int
     * @SWG\Property(name="barber_product_id", type="integer", description="理发师产品id")
     */
    
    /**
     * @var string
     * @SWG\Property(name="category_name", type="string", description="分类名称")
     */
    
    /**
     * @var int
     * @SWG\Property(name="good_number", type="integer", description="购买数量")
     */
    
    /**
     * @var int
     * @SWG\Property(
     *  name="is_action",
     *  type="integer",
     *  description="是否属于活动",
     *  enum="{'0':'不属于','1':'属于'}"
     * )
     */
    
    /**
     * @var string
     * @SWG\Property(name="action_name", type="string", description="活动名称，当为1时，必填")
     */
}