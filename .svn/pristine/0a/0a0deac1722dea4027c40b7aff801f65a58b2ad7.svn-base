<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 门店缓存Model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 *
 * @SWG\Model(id="SupplierCache")
 */
class SupplierCache extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'supplier_caches';
    
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
            'supplier_id',
            'reviews',
            'lower_price',
            'hot_product_ids',
            'busy_index',
            'followers',
            'tags',
    ];
    
    /**
     * 门店
     * 模型对象关系：缓存对应的门店信息，1对1
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo('App\Salon\Supplier', 'supplier_id', 'id');
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
     * @SWG\Property(name="reviews", type="string", description="评论的相关信息集合")
     * @var string
     */
    private $reviews;
    
    /**
     * @SWG\Property(name="avg_score", type="integer", description="门店的综合得分")
     * @var integer
     */
    private $avg_score;
    
    /**
     * @SWG\Property(name="lower_price", type="integer", description="最低价格")
     * @var int
     */
    private $lower_price;
    
    /**
     * @SWG\Property(name="hot_product_id", type="string", description="热门商品id集合")
     * @var string
     */
    private $hot_product_ids;
    
    /**
     * @SWG\Property(name="busy_index", type="integer", description="忙碌指数值")
     * @var int
     */
    private $busy_index;
    
    /**
     * @SWG\Property(name="followers", type="integer", description="关注者")
     * @var int
     */
    private $followers;
    
    /**
     * @SWG\Property(name="tags", type="string", description="评价的标签统计")
     * @var string
     */
    private $tags;
}
