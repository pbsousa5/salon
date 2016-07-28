<?php

namespace App\Salon\Repositories\V2;

use App\Salon\PaymentType;
use App\Salon\Repositories\PaymentTypeRepository as PaymentTypeRep;
/**
 *
 *
 *
 * @desc 支付类型数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class PaymentTypeRepository extends PaymentTypeRep
{
    /**
     *
     * 创建一个支付类型数据仓库实例
     * @param App\Salon\PaymentType $paymentType
     * @return void
     */
    public function __construct(PaymentType $paymentType)
    {
        $this->model = $paymentType;
    }

    /**
     * 更新或创建支付类型
     * @param PaymentType $paymentType 支付类型model
     * @param array $inputs 更新的数据
     * @return PaymentType|null
     */
    protected function savePaymentType(PaymentType $paymentType, array $inputs)
    {
        if (array_key_exists('pay_name', $inputs)) {
            $paymentType->pay_name = e(trim($inputs['pay_name']));
        }
        if (array_key_exists('pay_desc', $inputs)) {
            $paymentType->pay_desc = e(trim($inputs['pay_desc']));
        }
        if (array_key_exists('is_enable', $inputs)) {
            $paymentType->is_enable = $inputs['is_enable'];
        }
        if (array_key_exists('pay_configs', $inputs)) {
            $paymentType->pay_configs = serialize($inputs['pay_configs']);
        }
        if (array_key_exists('created_at', $inputs)) {
            $paymentType->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $paymentType->updated_at = $inputs['updated_at'];
        }
        
        if ($paymentType->save()) {
            return $paymentType;
        }
        
        return null;
    }    
}