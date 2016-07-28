<?php

namespace App\Salon\Repositories\V2;

use App\Salon\Review;
use App\Salon\Repositories\ReviewRepository as ReviewRep;
/**
 *
 *
 *
 * @desc评论数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class ReviewRepository extends ReviewRep
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
     * 更新或创建评论数据
     * @param Review $review 评论数据model
     * @param array $inputs 更新的数据
     * @return Review|null
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
        if (array_key_exists('average_score', $inputs)) {
            $review->average_score = $inputs['average_score'];
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
            $review->order_info_id = $inputs['order_info_id'];
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
        
        if ($review->save()) {
            return $review;
        }
        
        return null;
    }    
}