<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 评论标签Model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 *
 * @SWG\Model(id="ReviewTag")
 */
class ReviewTag extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'review_tags';
    
    /**
     * 关闭自动更新时间戳
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = ['name', 'type'];
    
    // ========================================
    // model的属性
    //=========================================
    /**
     * @SWG\Property(name="id", type="integer", description="主键")
     * @var int
     */
    private $id;
    
    /**
     * @SWG\Property(name="name", type="string", description="标签名称")
     * @var string
     */
    private $name;
    
    /**
     * @SWG\Property(
     *  name="type",
     *  type="integer",
     *  description="标签类型",
     *  enum="{'1':'好tag','2':'中tag','3':'差tag'}"
     * )
     * @var string
     */
    private $type;
}
