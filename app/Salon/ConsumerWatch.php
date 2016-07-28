<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 用户关注商家model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="ConsumeWatch")
 */
class ConsumerWatch extends Model
{
    
    /**
     * 表名
     * @var string
     */
    protected $table = 'consumer_watchs';
    
    /**
     * 禁止自动更新时间戳
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = ['consumer_id', 'user_id', 'user_type', 'created_at'];
    
    // ========================================
    // model的属性
    //=========================================
    /**
     * @SWG\Property(name="consumer_id", type="integer", description="消费者id")
     * @var int
     */
    private $consumer_id;
    
    /**
     * @SWG\Property(name="user_id", type="integer", description="门店id or 理发师id")
     * @var int
     */
    private $user_id;
    
    /**
     * @SWG\Property(
     *  name="user_type",
     *  type="string",
     *  description="门店id or 理发师id",
     *  enum="{'supplier':'门店','barber':'理发师'}"
     * )
     * @var string
     */
    private $user_type;
}
