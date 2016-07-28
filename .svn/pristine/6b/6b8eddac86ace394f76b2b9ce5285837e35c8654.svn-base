<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 提现日志Model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 *
 * @SWG\Model(id="WithdrawCashLog")
 */
class WithdrawCashLog extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'withdraw_cash_logs';
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
            'supplier_id',
            'fund_account_id',
            'cash_fee',
            'user_name',
            'card_number',
            'pay_code',
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
     * @SWG\Property(name="supplier_id", type="integer", description="门店id")
     * @var int
     */
    private $supplier_id;
    
    /**
     * @SWG\Property(name="id", type="fund_account_id", description="资金账号id")
     * @var int
     */
    private $fund_account_id;
    
    /**
     * @SWG\Property(name="cash_fee", type="integer", description="提现金额，单位为分")
     * @var int
     */
    private $cash_fee;
    
    /**
     * @SWG\Property(name="user_name", type="string", description="账户姓名")
     * @var string
     */
    private $user_name;
    
    /**
     * @SWG\Property(name="card_number", type="string", description="卡号")
     * @var string
     */
    private $card_number;
    
    /**
     * @SWG\Property(name="pay_code", type="string", description="支付方式编码")
     * @var string
     */
    private $pay_code;
    
    /**
     * @SWG\Property(
     *  name="is_verify",
     *  type="integer",
     *  description="审核状态，0：审核中，1：审核成功，2：审核失败",
     *  enum="{'0':'审核中','1':'审核成功','2':'审核失败'}"
     * )
     * @var int
     */
    private $is_verify;
}
