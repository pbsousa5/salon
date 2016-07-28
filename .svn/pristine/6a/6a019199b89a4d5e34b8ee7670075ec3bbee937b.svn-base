<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 提现记录model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="FundRecord")
 */
class FundRecord extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'fund_records';
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
            'user_id',
            'user_type',
            'status',
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
     * @SWG\Property(name="draw_fee", type="integer", description="总共提现的金额")
     * @var int
     */
    private $draw_fee;
    
    /**
     * @SWG\Property(name="balance_fee", type="integer", description="平台剩余额，可以提现的")
     * @var int
     */
    private $balance_fee;
    
    /**
     * @SWG\Property(name="user_id", type="integer", description="账户拥有者id")
     * @var int
     */
    private $user_id;
    
    /**
     * @SWG\Property(name="user_type", type="string", description="身份标识", enum="{'supplier':'门店','barber':'理发师'}")
     * @var string
     */
    private $user_type;
    
    /**
     * @SWG\Property(
     *  name="status",
     *  type="string",
     *  description="该条记录状态",
     *  enum="{'1':'被冻结的资金记录','2':'未提现的资金记录','3':'提现成功的资金记录','4':'退款的资金记录'}"
     * )
     * @var string
     */
    private $status;
}
