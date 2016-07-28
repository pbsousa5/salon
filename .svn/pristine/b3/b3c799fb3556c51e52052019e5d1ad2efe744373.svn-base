<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 理发师缓存Model
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月28日
 *
 * @SWG\Model(id="BarberCache")
 */
class BarberCache extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'barber_caches';
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
            'barber_id',
            'reviews',
            'lower_price',
            'followers',
            'avg_score',
    ];
    
    /**
     * 理发师
     * 模型对象关系：缓存对应的理发师信息，1对1
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
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
     * @SWG\Property(name="barber_id", type="integer", description="理发师id")
     * @var int
     */
    private $barber_id;
    
    /**
     * @SWG\Property(name="reviews", type="string", description="评论的相关信息集合")
     * @var string
     */
    private $reviews;
    
    /**
     * @SWG\Property(name="avg_score", type="integer", description="理发师的综合得分")
     * @var integer
     */
    private $avg_score;
    
    /**
     * @SWG\Property(name="lower_price", type="integer", description="最低价格")
     * @var int
     */
    private $lower_price;
    
    /**
     * @SWG\Property(name="followers", type="integer", description="关注者")
     * @var int
     */
    private $followers;
}
