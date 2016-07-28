<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\AvailReviewRepositoryInterface;
use App\Salon\AvailReview;

/**
 * 
 * 
 * @desc 记录用户是否点击有用的数据仓库类
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月5日
 */
class AvailReviewRepository extends BaseRepository implements AvailReviewRepositoryInterface
{
    /**
     *
     * 创建一个点击有用数据仓库实例
     * @param App\Salon\BackOrder $back
     * @return void
     */
    public function __construct(AvailReview $avail)
    {
        $this->model = $avail;
    }
    
    /**
     * 创建或者更新有用评论点击的操作
     * 
     * @param AvailReview $availReview 记录用户与评论是否操作过有用的记录表
     * @param array $inputs 更新的数据
     */
    protected function saveAvailReview(AvailReview $availReview, array $inputs)
    {
        $availReview->consumer_id = $inputs['consumer_id'];
        $availReview->review_id = $inputs['review_id'];
        
        return $availReview->save();
    }
    
    /**
     * 根据给定的条件，统计有多少条记录
     *
     * @param integer $consumer_id 用户id
     * @param integer $review_id 评论id
     * @return integer
     */
    public function countByWhere($consumer_id, $review_id)
    {
        return $this->model
                        ->where('consumer_id', $consumer_id)
                        ->where('review_id', $review_id)
                        ->count();
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
        return $this->saveAvailReview($this->createModel(), $inputs);
    }
    
    /**
     * 获取指定资源
     *
     * @param int $consumer_id 资源id
     * @param int $review_id 要查询的评论id
     * @return Illuminate\Support\Collection
     */
    public function show($consumer_id, $review_id)
    {
        return $this->model
                        ->where('consumer_id', $consumer_id)
                        ->where('review_id', $review_id)
                        ->first();
    }
}