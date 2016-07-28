<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 * 
 * @desc 产品Model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 * 
 * @SWG\Model(id="Product")
 */
class Product extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'products';
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    //protected $fillable = [];
    
    /**
     * 不允许批量赋值的字段
     * @var array
     */
    protected $guarded = [
            'is_delete',
    ];
    
    /**
     * 获取该产品对应的分类
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Salon\ProductCategory', 'category_id', 'id');
    }
    
    /**
     * 获取该产品对应的门店信息
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo('App\Salon\Supplier', 'supplier_id', 'id');
    }
    
    /**
     * 获取该产品对应的评论信息
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reviews()
    {
        return $this->hasMany('App\Salon\Review', 'supplier_id', 'id');
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
     * @SWG\Property(name="supplier_id", type="integer", description="门店id")
     * @var int
     */
    private $supplier_id;
    
    /**
     * @SWG\Property(
     *  name="status",
     *  type="integer",
     *  description="分类类型，此字段不会返回给APP",
     *  enum="{'1':'洗剪吹','2':'烫发','3':'染发','4':'护发','5':'套餐'}"
     * )
     * @var integer
     */
    private $category_type;
    
    /**
     * @SWG\Property(name="category", type="ProductCategory", description="分类的信息")
     * @var int
     */
    private $category;
    
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
     * @SWG\Property(name="sell_price", type="integer", description="售价，单位为分")
     * @var int
     */
    private $sell_price;
    
    /**
     * @SWG\Property(name="original_price", type="integer", description="原价，单位为分")
     * @var int
     */
    private $original_price;
    
    /**
     * @SWG\Property(name="total_stock", type="integer", description="库存，0表示没有限制")
     * @var int
     */
    private $total_stock;
    
    /**
     * @SWG\Property(name="quota_num", type="integer", description="限购数，0表示没有限制")
     * @var int
     */
    private $quota_num;
    
    /**
     * @SWG\Property(
     *  name="status",
     *  type="integer",
     *  description="状态,下架的商品不会返回到app中",
     *  enum="{'0':'售罄','1':'在售','2':'下架'}"
     * )
     * @var int
     */
    private $status;
    
    /**
     * @SWG\Property(
     *  name="sold_type",
     *  type="integer",
     *  description="等于1时，需要判断当前时间戳大于等于指定时间戳",
     *  enum="{'0':'立刻出售','1':'定时出售'}"
     * )
     * @var int
     */
    private $sold_type;
    
    /**
     * @SWG\Property(name="start_sold_time", type="integer", description="定时出售的时间戳")
     * @var int
     */
    private $start_sold_time;
    
    /**
     * @SWG\Property(name="rich_desc", type="string", description="富文本描述")
     * @var string
     */
    private $rich_desc;
    
    /**
     * @SWG\Property(
     *  name="is_real",
     *  type="integer",
     *  description="是否是实物商品",
     *  enum="{'0':'不是','1':'是'}"
     * )
     * @var int
     */
    private $is_real;
}
