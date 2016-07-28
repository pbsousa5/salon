<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 门店账号model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="FundAccount")
 */
class FundAccount extends Model
{
    const SUPPLIER_ACCOUNT = 1;
    
    /**
     * 表名
     * @var string
     */
    protected $table = 'fund_accounts';
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
            'user_id',
            'user_type',
            'user_name',
            'card_number',
            'mobile',
            'pay_code',
    ];
    
    /**
     * 获取支付的支付类型
     * 
     */
    public function paymentType()
    {
        return $this->belongsTo('App\Salon\PaymentType', 'pay_code', 'pay_code');
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
     * @SWG\Property(name="user_id", type="integer", description="账户拥有者id")
     * @var int
     */
    private $user_id;
    
    /**
     * @SWG\Property(name="user_type", type="string", description="身份标识", enum="{'supplier':'门店','consumer':'用户','barber':'理发师'}")
     * @var string
     */
    private $user_type;
    
    /**
     * @SWG\Property(name="user_name", type="string", description="账户对应的名称")
     * @var string
     */
    private $user_name;
    
    /**
     * @SWG\Property(name="card_number", type="string", description="账户卡号")
     * @var string
     */
    private $card_number;
    
    /**
     * @SWG\Property(name="mobile", type="string", description="手机号码")
     * @var string
     */
    private $mobile;
    
    /**
     * @SWG\Property(name="pay_code", type="string", description="支付方式code")
     * @var string
     */
    private $pay_code;
}
