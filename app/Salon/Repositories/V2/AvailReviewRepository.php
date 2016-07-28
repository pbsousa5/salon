<?php

namespace App\Salon\Repositories\V2;

use  App\Salon\AvailReview;
use App\Salon\Repositories\AvailReviewRepository as AvailReviewRep;
/**
 * 
 * 
 * @desc 记录用户是否点击有用的数据仓库类
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class AvailReviewRepository extends AvailReviewRep
{
    /**
     *
     * 创建一个记录用户是否点击有用仓库实例
     * @param App\Salon\AvailReview $availreview
     * @return void
     */
    public function __construct(AvailReview $availReview)
    {
        $this->model = $availReview;
    }
    
    /**
     * 创建或者更新有用评论点击的操作
     * @param AvailReview $availreview 记录用户是否点击有用model
     * @param array $inputs 更新的数据
     * @return AvailReview|null
     */
    protected function saveAvailReview (AvailReview $availReview, array $inputs)
    {
        if (array_key_exists('consumer_id', $inputs)) {
            $availReview->consumer_id = $inputs['consumer_id'];
        }
        if (array_key_exists('review_id', $inputs)) {
            $availReview->review_id = $inputs['review_id'];
        }
        
        if ($availReview->save()) {
            return $availReview;
        }
        
        return null;
    }
}