<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 评论信息Model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 *
 * @SWG\Model(id="Review")
 */
class Review extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'reviews';
    
    /**
     * 关闭自动更新时间戳
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    //protected $fillable = [];
    
    /**
     * 不允许批量赋值的字段
     * @var array
     */
    protected $guarded = ['is_highgrade','zan_num','is_verify','average_score'];
    
    /**
     * 获取该评论对应的门店信息
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo('App\Salon\Supplier', 'supplier_id', 'id');
    }
    
    /**
     * 获取该评论对应的门店理发师信息
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barber()
    {
        return $this->belongsTo('App\Salon\Barber', 'barber_id', 'id');
    }
    
    /**
     * 获取该评论对应的评论者信息
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consumer()
    {
        return $this->belongsTo('App\Salon\Consumer', 'consumer_id', 'id');
    }
    
    /**
     * 获取该评论对应的产品信息
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Salon\Product', 'product_id', 'id');
    }
    
    /**
     * 获取该评论对应的产品信息
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barberProduct()
    {
        return $this->belongsTo('App\Salon\BarberProduct', 'barber_product_id', 'id');
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
     * @SWG\Property(name="service_score", type="integer", description="服务评分,需要")
     * @var int
     */
    private $service_score;
    
    /**
     * @SWG\Property(name="skill_score", type="integer", description="技术评分,需要")
     * @var int
     */
    private $skill_score;
    
    /**
     * @SWG\Property(name="env_score", type="integer", description="环境评分,需要")
     * @var int
     */
    private $env_score;
    
    /**
     * @SWG\Property(name="average_score", type="integer", description="该条评论的综合得分")
     * @var int
     */
    private $average_score;
    
    /**
     * @SWG\Property(name="comment_txt", type="string", description="评论文本内容,需要")
     * @var string
     */
    private $comment_txt;
    
    /**
     * @SWG\Property(name="comment_imgs", type="array", description="评论的图片,需要(可为空)")
     * @var string
     */
    private $comment_imgs;
    
    /**
     * @SWG\Property(name="consumer_id", type="integer", description="消费者id,需要")
     * @var int
     */
    private $consumer_id;
    
    /**
     * @SWG\Property(name="supplier_id", type="integer", description="门店id,需要")
     * @var int
     */
    private $supplier_id;
    
    /**
     * @SWG\Property(name="barber_id", type="integer", description="理发师id,需要(可为空)")
     * @var int
     */
    private $barber_id;
    
    /**
     * @SWG\Property(name="barber_nickname", type="string", description="理发师昵称,需要(当barber_id存在时，它必须存在)")
     * @var int
     */
    private $barber_nickname;
    
    /**
     * @SWG\Property(name="product_id", type="integer", description="产品id,需要(如果设置了理发师，该字段值应该为0)")
     * @var int
     */
    private $product_id;
    
    /**
     * @SWG\Property(name="barber_product_id", type="integer", description="理发师对应的产品，如果设置了理发师，该字段必须")
     * @var int
     */
    private $barber_product_id;
    
    /**
     * @SWG\Property(name="order_product_id", type="integer", description="订单与产品id,需要")
     * @var int
     */
    private $order_product_id;
    
    /**
     * @SWG\Property(name="order_info_id", type="integer", description="订单id,需要")
     * @var int
     */
    private $order_info_id;
    
    /**
     * @SWG\Property(name="queue_time", type="string", description="等待时间,需要")
     * @var string
     */
    private $queue_time;
    
    /**
     * @SWG\Property(name="review_tags", type="array", description="用户选择的评论标签值,需要")
     * @var string
     */
    private $review_tags;
    
    /**
     * @SWG\Property(
     *  name="is_highgrade",
     *  type="integer",
     *  description="是否是认真评论,添加评论时，该字段不传",
     *  enum="{'0':'不是','1':'是'}"
     * )
     * @var int
     */
    private $is_highgrade;
    
    /**
     * @SWG\Property(
     *  name="is_verify",
     *  type="integer",
     *  description="审核评论",
     *  enum="{'0':'审核中','1':'未审核'}"
     * )
     * @var int
     */
    private $is_verify;
    
    /**
     * @SWG\Property(
     *  name="is_anonymous",
     *  type="integer",
     *  description="是否匿名",
     *  enum="{'0':'不匿名','1':'匿名'}"
     * )
     * @var int
     */
    private $is_anonymous;
    
    /**
     * @SWG\Property(name="zan_num", type="integer", description="支持该条评论的人数,添加评论时，该字段不传")
     * @var int
     */
    private $zan_num;
    
    /**
     * @SWG\Property(name="consumer", type="Consumer", description="该评论发布者的信息")
     * @var int
     */
    private $consumer;
    
    /**
     * @SWG\Property(name="product", type="Product", description="该评论的产品信息")
     * @var int
     */
    private $product;
}
