<?php

namespace App\Salon\Repositories;

use App\Salon\ConsumerCoupon;
use App\Salon\Contracts\Repositories\ConsumerCouponRepositoryInterface;
use App\Salon\Consumer;

/**
 * 
 * 
 * @desc 消费者优惠券
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class ConsumerCouponRepository extends BaseRepository implements ConsumerCouponRepositoryInterface
{
    /**
     * 
     * 创建一个消费者优惠券数据仓库实例
     * @param App\Salon\ConsumerCoupon $coupon
     * @return void
     */
    public function __construct(ConsumerCoupon $coupon)
    {
        $this->model = $coupon;
    }
    
    /**
     * 保存或者更新用户优惠券
     * 
     * @param ConsumerCoupon $consumerCoupon 用户优惠券model
     * @param array $inputs 更新或者创建的数据
     * 
     * @return ConsumerCoupon|null
     */
    protected function saveConsumerCoupon(ConsumerCoupon $consumerCoupon, array $inputs)
    {
        if (array_key_exists('consumer_id', $inputs)) {
            $consumerCoupon->consumer_id = $inputs['consumer_id'];
        }
        if (array_key_exists('coupon_id', $inputs)) {
            $consumerCoupon->coupon_id = $inputs['coupon_id'];
        }
        if (array_key_exists('status', $inputs)) {
            $consumerCoupon->status = $inputs['status'];
        }
        if (array_key_exists('deadline', $inputs)) {
            $consumerCoupon->deadline = $inputs['deadline'];
        }
        
        if ($consumerCoupon->save()) {
            return $consumerCoupon;
        }
        return null;
    }
    
    /**
     * 根据给定的条件，统计有多少条记录
     *
     * @param integer $consumer_id 消费者id
     * @param integer $coupon_id 优惠券id
     * @return integer
     */
    public function countByWhere($consumer_id, $coupon_id)
    {
        return $this->model
                        ->where('consumer_id', $consumer_id)
                        ->where('coupon_id', $coupon_id)
                        ->where('status', '<>', 2)
                        ->count();
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $data 必须传入与模型查询相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @param  string $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($data, $extra='', $size=10)
    {
        // 获取能够使用的优惠券
        if (array_key_exists('consumer_id', $data)) {
            return $this->model
                            ->with('coupon')
                            ->where('consumer_id', $data['consumer_id'])
                            ->where('status', ConsumerCoupon::COUPON_STATUS_NOT_USE)
                            ->paginate($size);
        }
    }
    
    /**
     * 存储资源
     *
     * @param  array $inputs 必须传入与存储模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model
     */
    public function store($inputs, $extra='')
    {
        $inputs = array_add($inputs, 'created_at', time());
        
        return $this->saveConsumerCoupon($this->createModel(), $inputs);
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
        return $this->model->where('id', $id)->first();
    }
    
    /**
     * 更新特定id资源
     *
     * @param  int $id 资源id
     * @param  array $inputs 必须传入与更新模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return void
    */
    public function update($id, $inputs, $extra='')
    {
        $consumerCoupon = $this->show($id);
        if (is_null($consumerCoupon)) {
            return null;
        }
        
        return $this->saveConsumerCoupon($consumerCoupon, $inputs);
    }
    
}