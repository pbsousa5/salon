<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 产品分类Model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 *
 * @SWG\Model(id="ProductCategory")
 */
class ProductCategory extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'product_categorys';
    
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
            'name',
            'describe',
    ];
    
    /**
     * 获取该分类下的所有产品
     * 请注意N+1问题
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product()
    {
        return $this->hasMany('App\Salon\Product', 'category_id', 'id');
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
     * @SWG\Property(name="name", type="string", description="产品分类名")
     * @var string
     */
    private $name;
    
    /**
     * @SWG\Property(name="describe", type="string", description="对分类的说明")
     * @var string
     */
    private $describe;
}
