<?php

namespace App\Salon\Repositories\V2;

use App\Salon\ConsumerWatch;
use App\Salon\Repositories\ConsumerWatchRepository as ConsumerWatchRep;
/**
 *
 *
 *
 * @desc 消费者关注门店数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class ConsumerWatchRepository extends ConsumerWatchRep
{
    /**
     *
     * 创建一个消费者关注门店数据仓库实例
     * @param App\Salon\ConsumerWatch $consumerWatch
     * @return void
     */
    public function __construct(ConsumerWatch $consumerWatch)
    {
        $this->model = $consumerWatch;
    }

    /**
     * 更新或创建消费者关注门店
     * @param ConsumerWatch $consumerWatch 消费者关注门店model
     * @param array $inputs 更新的数据
     * @return ConsumerWatch|null
     */
    protected function saveConsumerWatch(ConsumerWatch $consumerWatch, array $inputs)
    {
        if (array_key_exists('consumer_id', $inputs)) {
            $consumerWatch->consumer_id = $inputs['consumer_id'];
        }
        if (array_key_exists('user_id', $inputs)) {
            $consumerWatch->user_id = $inputs['user_id'];
        }
        if (array_key_exists('user_type', $inputs)) {
            $consumerWatch->user_type = strtolower($inputs['user_type']);
        }
        if (array_key_exists('created_at', $inputs)) {
            $consumerWatch->created_at = $inputs['created_at'];
        }
        
        if ($consumerWatch->save()) {
            return $consumerWatch;
        }
        
        return null;
    }

    /**
     * 获取门店、理发师粉丝id数
     * @param array $id 门店id/理发师id
     *  @param array $type 用户类型
     * @return array|null
     */
    public function getFansId($id, $type, $size)
    {
        return $this->model
                    ->where('user_id', $id)
                    ->where('user_type', $type)
                    ->orderBy('created_at', 'desc')
                    ->paginate($size)
                    ->lists('consumer_id');
    }
}