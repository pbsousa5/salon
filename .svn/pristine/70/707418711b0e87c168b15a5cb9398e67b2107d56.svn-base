<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 设备model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="Device")
 */
class Device extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'devices';
    
    /**
     * 关闭自动更新时间戳
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = ['name', 'key', 'status'];
    
    // ========================================
    // model的属性
    //=========================================
    /**
     * @SWG\Property(name="id", type="integer", description="主键")
     * @var int
     */
    private $id;
    
    /**
     * @SWG\Property(name="name", type="string", description="名称")
     * @var string
     */
    private $name;
    
    /**
     * @SWG\Property(name="key", type="string", description="对应的秘钥")
     * @var string
     */
    private $key;
    
    /**
     * @SWG\Property(
     *  name="status",
     *  type="integer",
     *  description="是否启用",
     *  enum="{'0':'不支持','1':'支持'}"
     * )
     * @var int
     */
    private $status;
}
