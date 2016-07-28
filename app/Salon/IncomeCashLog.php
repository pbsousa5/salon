<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;
/**
 *
 *
 * @desc 收入日志Model
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月11日
 *
 * @SWG\Model(id="IncomeCashLog")
 */
class IncomeCashLog extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'income_cash_logs';

    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
            'supplier_id',
            'consumer_id',
            'barber_id',
            'order_info_id',
            'trade_no',
            'cash_fee',
            'pay_fee',
    ];
    
    /**
     * 一条收入，可能属于某个理发师，一个理发师对应多个收入
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barber()
    {
        return $this->belongsTo('App\Salon\Barber', 'barber_id', 'id');
    }
    
    /**
     * 一条收入，属于某个门店，一个门店对应多个收入
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo('App\Salon\Supplier', 'supplier_id', 'id');
    }
    
    /**
     * 一条收入，属于某个用户，一个用户对应多个收入
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function consumer()
    {
        return $this->belongsTo('App\Salon\Consumer', 'consumer_id', 'id');
    }
    
    /**
     * 一条收入，属于某个订单，一个订单对应一条收入
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderInfo()
    {
        return $this->belongsTo('App\Salon\OrderInfo', 'order_info_id', 'id');
    }
}