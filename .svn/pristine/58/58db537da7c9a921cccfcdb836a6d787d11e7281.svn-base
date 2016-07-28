<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\CouponRepositoryInterface;
use App\Salon\Coupon;

/**
 * 
 * 
 * @desc 优惠券仓库实现
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class CouponRepository extends BaseRepository implements CouponRepositoryInterface
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
     * 获取指定资源
     *
     * @param int $id 资源id
     * @param array|string $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id, $extra='')
    {
        return $this->model->find($id);
    }
    
}