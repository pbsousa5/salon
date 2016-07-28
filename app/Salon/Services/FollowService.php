<?php

namespace App\Salon\Services;

use App\Salon\Repositories\ConsumerWatchRepository;
/**
 * 
 * 
 * @desc 消费者关注商家服务
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月28日
 */
class FollowService
{
    /**
     *关注关系类
     * @var ConsumerWatchRepository
     */
    protected $followerRe;
    
    /**
     * 门店服务层
     * @var SupplierService
     */
    protected $supplierSer;
    
    public function __construct(
            ConsumerWatchRepository $followerRe
    ){
        $this->followerRe = $followerRe;
    }
    
    /**
     * 添加关注
     * 
     * @param array $inputs 添加关注信息
     * @param string $user_type 用户类型
     * @return boolean
     */
    public function addFollower($inputs, $user_type)
    {
        return $this->followerRe->store($inputs, $user_type);
    }
    
    /**
     * 移除关注
     *
     * @param array $inputs 添加关注信息
     * @param string $user_type 用户类型
     * @return boolean
     */
    public function delFollower($inputs, $user_type)
    {
        return $this->followerRe->destroy($inputs, $user_type);
    }
    
    /**
     * 检查是否已经关注
     *
     * @param array $where 查询条件，限定为[consumer_id=>'', user_id='']
     * @param string $user_type 用户类型 supplier barber
     * @return boolean true:已经关注 false:未关注
     */
    public function checkStatus($where, $user_type)
    {
        $where = array_add($where, 'consumer_id', 0);
        $where = array_add($where, 'user_id', 0);
        $flag = $this->followerRe->show($where, $user_type);
        if (is_null($flag)) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * 获取关注者列表
     *
     * @param int $id 关注主体，这里指消费者
     * @param string $user_type 用户类型 supplier barber
     * @param int $size 获取几条关注信息,默认按照时间先后排序
     * @return Illuminate\Support\Collection|null
     */
    public function listMaster($id, $user_type, $size=10)
    {
        // 找出所有门店的id
        $ids = $this->followerRe->index($id, $user_type, $size);
        if ($ids->isEmpty()) {
            return collect();
        }
        
        return $ids->toArray();
    }
}