<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\ReviewRepositoryInterface;
use App\Salon\Review;

/**
 * 
 * 
 * @desc 评论数据仓库实现
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
    /**
     * 
     * 创建一个评论数据仓库实例
     * @param App\Salon\Review $review
     * @return void
     */
    public function __construct(Review $review)
    {
        $this->model = $review;
    }
    
    /**
     * 保存或者更新评论信息
     * 
     * @param Review $review 评论model
     * @param array $inputs 评论的相关数据
     * @return boolean
     */
    protected function saveReview(Review $review, array $inputs)
    {
        if (array_key_exists('service_score', $inputs)) {
            $review->service_score = $inputs['service_score'];
        }
        if (array_key_exists('skill_score', $inputs)) {
            $review->skill_score = $inputs['skill_score'];
        }
        if (array_key_exists('env_score', $inputs)) {
            $review->env_score = $inputs['env_score'];
        }
        if (array_key_exists('comment_txt', $inputs)) {
            $review->comment_txt = e(trim($inputs['comment_txt']));
        }
        if (array_key_exists('comment_imgs', $inputs)) {
            $review->comment_imgs = serialize($inputs['comment_imgs']);
        }
        if (array_key_exists('consumer_id', $inputs)) {
            $review->consumer_id = $inputs['consumer_id'];
        }
        if (array_key_exists('product_id', $inputs)) {
            $review->product_id = $inputs['product_id'];
        }
        if (array_key_exists('barber_product_id', $inputs)) {
            $review->barber_product_id = $inputs['barber_product_id'];
        }
        if (array_key_exists('supplier_id', $inputs)) {
            $review->supplier_id = $inputs['supplier_id'];
        }
        if (array_key_exists('barber_id', $inputs)) {
            $review->barber_id = $inputs['barber_id'];
        }
        if (array_key_exists('barber_nickname', $inputs)) {
            $review->barber_nickname = e(trim($inputs['barber_nickname']));
        }
        if (array_key_exists('order_info_id', $inputs)) {
            $review->order_info_id = $inputs['order_id'];
        }
        if (array_key_exists('order_product_id', $inputs)) {
            $review->order_product_id = $inputs['order_product_id'];
        }
        if (array_key_exists('queue_time', $inputs)) {
            $review->queue_time = $inputs['queue_time'];
        }
        if (array_key_exists('review_tags', $inputs)) {
            $review->review_tags = serialize($inputs['review_tags']);
        }
        if (array_key_exists('is_verify', $inputs)) {
            $review->is_verify = $inputs['is_verify'];
        }
        if (array_key_exists('is_highgrade', $inputs)) {
            $review->is_highgrade = $inputs['is_highgrade'];
        }
        if (array_key_exists('is_anonymous', $inputs)) {
            $review->is_anonymous = (int)$inputs['is_anonymous'];
        }
        if (array_key_exists('zan_num', $inputs)) {
            $review->zan_num = $inputs['zan_num'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $review->created_at = $inputs['created_at'];
        }
        
        return $review->save();
    }
    
    /**
     * 根据给定的条件，统计有多少条记录
     *
     * @param integer $consumer_id 门店的id
     * @param integer $order_product_id 字段的值
     * @return integer
     */
    public function countByWhere($consumer_id, $order_product_id)
    {
        return $this->model
                        ->where('consumer_id', $consumer_id)
                        ->where('order_product_id', $order_product_id)
                        ->count();
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $data 获取指定条件的数据
     * @param  string|array $extra 可选额外传入的参数
     * @param  string $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($data, $extra=[], $size=10)
    {
        if (array_key_exists('supplier_id', $data) && array_key_exists('is_verify', $extra)) {
            $cols = $this->model
                            ->with('consumer')
                            ->with('product')
                            ->where('supplier_id', $data['supplier_id'])
                            ->where('is_verify', $extra['is_verify'])
                            ->orderBy('created_at', 'desc')
                            ->paginate($size);
        } else {
            
        }
        
        return $cols->getCollection();
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
        $review = $this->createModel();
        $inputs = array_add($inputs, 'barber_id', 0);
        $inputs = array_add($inputs, 'barber_product_id', 0);
        $inputs = array_add($inputs, 'product_id', 0);
        $inputs = array_add($inputs, 'comment_imgs', []);
        $inputs = array_add($inputs, 'review_tags', []);
        $inputs = array_add($inputs, 'created_at', date('Y-m-d H:i:s', time()));
        
        return $this->saveReview($review, $inputs);
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
        if (empty($extra)) {
            return $this->model->find($id);
        } 
        
        if (array_key_exists('order_product_id', $extra)) {
            return $this->model
                            ->where('consumer_id', $id)
                            ->where('order_product_id', $extra['order_product_id'])
                            ->first();
        }
    }
    
    /**
     * 更新特定资源
     *
     * @param  array $where 查找资源的条件
     * @param  array $inputs 必须传入与更新模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return boolean
    */
    public function update($where, $inputs, $extra='')
    {
        $query = $this->createModel()->newQuery();
        
        foreach ($where as $field => $value) {
            $query->where($field, $value);
        }
        
        $review = $query->first();
        if (is_null($review)) {
            return false;
        }
        
        if (array_key_exists('zan_num', $inputs)) {
            $inputs['zan_num'] = $review->zan_num + $inputs['zan_num'];
        }
        
        return $this->saveReview($review, $inputs);
    }
    
}