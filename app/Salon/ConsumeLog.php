<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 消费日志model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="ConsumeLog")
 */
class ConsumeLog extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'consume_logs';
    
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
            'order_info_id',
            'order_product_id',
            'consumer_id',
            'supplier_id',
            'barber_id',
            'created_at',
    ];
    
    /**
     * 一个消费日志对应一个客户
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consumer()
    {
        return $this->belongsTo('App\Salon\Consumer', 'consumer_id', 'id');
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
     * @SWG\Property(name="order_info_id", type="integer", description="订单的id")
     * @var int
     */
    private $order_info_id;
    
    /**
     * @SWG\Property(name="order_product_id", type="integer", description="订单对应产品id")
     * @var int
     */
    private $order_product_id;
    
    /**
     * @SWG\Property(name="consumer_id", type="integer", description="消费者主键id")
     * @var int
     */
    private $consumer_id;
    
    /**
     * @SWG\Property(name="supplier_id", type="integer", description="店家id")
     * @var int
     */
    private $supplier_id;
    
    /**
     * @SWG\Property(name="barber_id", type="integer", description="理发师id")
     * @var int
     */
    private $barber_id;
}
