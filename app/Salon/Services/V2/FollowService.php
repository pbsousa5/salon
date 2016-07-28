<?php
namespace App\Salon\Services\V2;

use App\Salon\Services\FollowService as FollowSer;
use App\Salon\Repositories\V2\ConsumerWatchRepository;
use App\Salon\Repositories\V2\ConsumerRepository;

/**
 *
 *
 * @desc 消费者关注商家服务层
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月10日
 */
class FollowService extends FollowSer
{
    /**
     *关注关系类
     * @var ConsumerWatchRepository
     */
    protected $followerRe;
    
    /**
     * 用户数据层
     * @var ConsumerRepository
     */
    protected $consumerRe;
    
    public function __construct(
            ConsumerWatchRepository $followerRe,
            ConsumerRepository $consumerRe
    ){
        parent::__construct($followerRe);
        $this->consumerRe = $consumerRe;
    }
    
     /**
     * 获取粉丝数
     *
     * @param integer $id 门店、理发师id
     * @param string $user_type 用户类型
     * @param integer $size 分页参数
     * @return array|null
     */
    public function getFans($id, $user_type, $size)
    {
        //获取所有fans的id
        $ids = $this->followerRe->getFansId($id, $user_type, $size);
        if (empty($ids)) {
            return null;
        }
        $ids = $ids->toArray();
        $where = [];
        $where = array_add($where, 'ids', $ids);
        $list = $this->consumerRe->index($where, 'level', $size)->getCollection();
        if ($list->isEmpty()) {
            return null;
        }
        foreach ($list as $key => $fans) {
            $fans->nickname = empty($fans->nickname) ? '平台用户' : $fans->nickname;
            unset($fans->account);
            unset($fans->mobile);
            unset($fans->gender);
            unset($fans->age_tag);
            unset($fans->level_score);
            unset($fans->my_bean);
            unset($fans->my_coupon);
            unset($fans->weight);
            unset($fans->comment_time);
            unset($fans->helpful_count);
            unset($fans->created_at);
            unset($fans->updated_at);
            unset($fans->invitation_code);
        }
        
        return $list;
    }
}