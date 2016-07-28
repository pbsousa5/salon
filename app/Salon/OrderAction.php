<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 * 
 * @desc 订单与活动model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 *
 * @SWG\Model(id="OrderAction")
 */
class OrderAction extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'order_actions';
    
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
            'order_product_id',
            'action_name',
            'configs',
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
     * @SWG\Property(name="order_product_id", type="integer", description="订单中的产品id")
     * @var int
     */
    private $order_product_id;
    
    /**
     * @SWG\Property(name="action_name", type="string", description="活动的表名")
     * @var string
     */
    private $action_name;
    
    /**
     * @SWG\Property(name="configs", type="string", description="活动的配置信息")
     * @var string
     */
    private $configs;
}
