<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 支付类型model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 *
 * @SWG\Model(id="PaymentType")
 */
class PaymentType extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'payment_types';
    
    /**
     * 更改主键的名称
     * @var string
     */
    protected $primaryKey = 'pay_code';
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
            'pay_code',
            'pay_name',
            'pay_desc',
            'is_enable',
            'pay_configs',
    ];
    
    // ========================================
    // model的属性
    //=========================================
    /**
     * @SWG\Property(name="pay_code", type="string", description="支付方式的代码，主键")
     * @var string
     */
    private $pay_code;
    
    /**
     * @SWG\Property(name="pay_name", type="string", description="支付方式名称")
     * @var string
     */
    private $pay_name;
    
    /**
     * @SWG\Property(name="pay_desc", type="string", description="对支付的描述")
     * @var string
     */
    private $pay_desc;
    
    /**
     * @SWG\Property(
     *  name="is_enable",
     *  type="integer",
     *  description="是否支持该支付方式",
     *  enum="{'0':'不支持','1':'支持'}"
     * )
     * @var int
     */
    private $is_enable;
    
    /**
     * @SWG\Property(name="pay_configs", type="string", description="支付的配置参数")
     * @var string
     */
    private $pay_configs;
}
