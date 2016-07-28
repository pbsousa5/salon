<?php

namespace App\Salon\Repositories\V2;

use App\Salon\Consumer;
use App\Salon\Repositories\ConsumerRepository as ConsumerRep;
/**
 *
 *
 *
 * @desc消费者数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class ConsumerRepository extends ConsumerRep
{
    /**
     *
     * 创建一个消费者数据仓库实例
     * @param App\Salon\Consumer $consumer
     * @return void
     */
    public function __construct(Consumer $consumer)
    {
        $this->model = $consumer;
    }

    /**
     * 更新或创建消费者
     * @param Consumer $consumer 消费者model
     * @param array $inputs 更新的数据
     * @return Consumer|null
     */
    protected function saveConsumer(Consumer $consumer, array $inputs)
    {
        if (array_key_exists('account', $inputs)) {
            $consumer->account = trim($inputs['account']);
        }
        if (array_key_exists('mobile', $inputs)) {
            $consumer->mobile = trim($inputs['mobile']);
        }
        if (array_key_exists('password', $inputs)) {
            $consumer->password =bcrypt($inputs['password']);
        }
        if (array_key_exists('nickname', $inputs)) {
            $consumer->nickname = e(trim($inputs['nickname']));
        }
        if (array_key_exists('head_img', $inputs)) {
            $consumer->head_img = $inputs['head_img'];
        }
        if (array_key_exists('gender', $inputs)) {
            $consumer->gender = $inputs['gender'];
        }
        if (array_key_exists('age_tag', $inputs)) {
            $consumer->age_tag = $inputs['age_tag'];
        }
        if (array_key_exists('level_score', $inputs)) {
            $consumer->level_score = $inputs['level_score'];
        }
        if (array_key_exists('my_bean', $inputs)) {
            $consumer->my_bean = $inputs['my_bean'];
        }
        if (array_key_exists('my_coupon', $inputs)) {
            $consumer->my_coupon = $inputs['my_coupon'];
        }
        if (array_key_exists('invitation_code', $inputs)) {
            $consumer->invitation_code = $inputs['invitation_code'];
        }
        if (array_key_exists('weight', $inputs)) {
            $consumer->weight = $inputs['weight'];
        }
        if (array_key_exists('comment_time', $inputs)) {
            $consumer->comment_time = $inputs['comment_time'];
        }
        if (array_key_exists('helpful_count', $inputs)) {
            $consumer->helpful_count = $inputs['helpful_count'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $consumer->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $consumer->updated_at = $inputs['updated_at'];
        }
        
        if ($consumer->save()) {
            return $consumer;
        }
        
        return null;
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $where 必须传入与模型查询相关的数据
     * @param  string|array $extra 可选额外传入的参数 level weight
     * @param  int $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($where=[], $extra='level', $size=10)
    {
        // 检查分页是否是数字，如果不是设置为10
        if (!is_numeric($size)) {
            $size = 10;
        }
        
        $query = $this->createModel()->newQuery();
        
        if (array_key_exists('ids', $where)) {
            $query->whereIn('id', $where['ids']);
            $where = array_remove_keys($where, ['ids']);
        }
    
        switch ($extra) {
            // 按照等级倒序
            case 'level':
                $where = array_add($where, 'level_score', 0);
                $ret = $query->where('level_score', '>=', e($where['level_score']))
                                ->orderBy('level_score', 'desc')
                                ->paginate($size);
                break;
                // 按照权重倒序
            case 'weight':
                $where = array_add($where, 'weight', 0);
                $ret = $query->where('weight', '>=', e($where['weight']))
                                ->orderBy('weight', 'desc')
                                ->paginate($size);
                break;
            default:
                $ret = null;
                break;
        }
    
        return $ret;
    }
}