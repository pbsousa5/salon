<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 退单的产品
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="BackProduct")
 */
class BackProduct extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'back_products';
    
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
            'back_order_id',
            'product_id',
            'supplier_id',
            'barber_id',
            'barber_product_id',
            'category_name',
            'product_name',
            'back_fee',
            'back_number',
    ];
    
    // ========================================
    // model的属性
    //=========================================
    /**
     * @SWG\Property(name="id", type="integer", description="主键")
     * @var int
     */
    private $id;
    
    /**
     * @SWG\Property(name="back_order_id", type="integer", description="退单中的主键")
     * @var int
     */
    private $back_order_id;
    
    /**
     * @SWG\Property(name="product_id", type="integer", description="对应的产品id")
     * @var int
     */
    private $product_id;
    
    /**
     * @SWG\Property(name="barber_id", type="integer", description="对应的理发师id,如果product_id=0，才能出现该值")
     * @var int
     */
    private $barber_id;
    /**
     * @SWG\Property(name="barber_product_id", type="integer", description="对应的理发师产品id")
     * @var int
     */
    private $barber_product_id;
    
    /**
     * @SWG\Property(name="supplier_id", type="integer", description="门店主键")
     * @var int
     */
    private $supplier_id;
    
    /**
     * @SWG\Property(name="category_name", type="string", description="分类名称")
     * @var string
     */
    private $category_name;
    
    /**
     * @SWG\Property(name="product_name", type="string", description="产品的名称")
     * @var string
     */
    private $product_name;
    
    /**
     * @SWG\Property(name="back_fee", type="integer", description="退款金额，单位为分")
     * @var int
     */
    private $back_fee;
    
    /**
     * @SWG\Property(name="back_number", type="integer", description="退款的产品数量")
     * @var int
     */
    private $back_number;
}
