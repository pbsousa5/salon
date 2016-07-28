<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 通知model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="Notify")
 */
class Notify extends Model
{
    // '0':'系统公告','1':'提现','2':'订单','3':'优惠券'
    const NOTIFY_TYPE_SYS = 0;
    const NOTIFY_TYPE_WITHDRAW = 1;
    const NOTIFY_TYPE_ORDER = 2;
    const NOTIFY_TYPE_COUPON = 3;
    
    /**
     * 表名
     * @var string
     */
    protected $table = 'notifys';
    
    /**
     * 不允许批量赋值的字段
     * @var array
     */
    protected $guarded = ['is_read'];
    
    /**
     * 查找门店的消息
     * 
     */
    public function scopeSupplier($query)
    {
        return $query->whereUserType('supplier');
    }
    
    /**
     * 查找用户的消息
     *
     */
    public function scopeConsumer($query)
    {
        return $query->whereUserType('consumer');
    }
    
    /**
     * 查找理发师的消息
     *
     */
    public function scopeBarber($query)
    {
        return $query->whereUserType('barber');
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
     * @SWG\Property(name="user_id", type="integer", description="用户id")
     * @var int
     */
    private $user_id;
    
    /**
     * @SWG\Property(
     *  name="user_type",
     *  type="string",
     *  description="用户类型",
     *  enum="{'consumer':'用户', 'supplier':'门店', 'barber':'理发师'}"
     * )
     * @var string
     */
    private $user_type;
    
    /**
     * @SWG\Property(name="title", type="string", description="消息标题")
     * @var string
     */
    private $title;
    
    /**
     * @SWG\Property(name="push_msg", type="string", description="通知内容")
     * @var string
     */
    private $push_msg;
    
    /**
     * @SWG\Property(
     *  name="is_read",
     *  type="integer",
     *  description="门店id",
     *  enum="{'0':'未读','1':'已读'}"
     * )
     * @var int
     */
    private $is_read;
    
    /**
     * @SWG\Property(
     *  name="notify_type",
     *  type="integer",
     *  description="通知类型",
     *  enum="{'0':'系统公告','1':'提现','2':'订单','3':'优惠券'}"
     * )
     * @var int
     */
    private $notify_type;
    
    /**
     * @SWG\Property(
     *  name="updated_at",
     *  type="string",
     *  description="更新时间，如果与创建时间不一致，表示阅读时间"
     * )
     * @var int
     */
    private $updated_at;
    
    /**
     * @SWG\Property(
     *  name="created_at",
     *  type="string",
     *  description="消息创建的时间"
     * )
     * @var int
     */
    private $created_at;
}
