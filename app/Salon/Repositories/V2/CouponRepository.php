<?php

namespace App\Salon\Repositories\V2;

use App\Salon\Coupon;
use App\Salon\Repositories\CouponRepository as CouponRep;
/**
 *
 *
 *
 * @desc优惠券数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class CouponRepository extends CouponRep
{
    /**
     *
     * 创建一个优惠券数据仓库实例
     * @param App\Salon\Coupon $coupon
     * @return void
     */
    public function __construct(Coupon $coupon)
    {
        $this->model = $coupon;
    }

    /**
     * 更新或创建优惠券
     * @param Coupon $coupon 优惠券model
     * @param array $inputs 更新的数据
     * @return Coupon|null
     */
    protected function saveCoupon(Coupon $coupon, array $inputs)
    {
        if (array_key_exists('face_fee', $inputs)) {
            $coupon->face_fee = $inputs['face_fee'];
        }
        if (array_key_exists('valid_term', $inputs)) {
            $coupon->valid_term = $inputs['valid_term'];
        }
        if (array_key_exists('full_cat', $inputs)) {
            $coupon->full_cat = $inputs['full_cat'];
        }
        if (array_key_exists('configs', $inputs)) {
            $coupon->configs = serialize($inputs['configs']);
        }
        
        if ($coupon->save()) {
            return $coupon;
        }
        
        return null;
    }    
}