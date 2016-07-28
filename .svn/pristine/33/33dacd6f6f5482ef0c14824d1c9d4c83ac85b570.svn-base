<?php

namespace App\Salon\Repositories\V2;

use App\Salon\ReviewTag;
use App\Salon\Repositories\ReviewTagRepository as ReviewTagRep;
/**
 *
 *
 *
 * @desc 评论标签数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class ReviewTagRepository extends ReviewTagRep
{
    /**
     *
     * 创建一个评论标签数据仓库实例
     * @param App\Salon\ReviewTag $reviewtag
     * @return void
     */
    public function __construct(ReviewTag $reviewTag)
    {
        $this->model = $reviewTag;
    }

    /**
     * 更新或创建评论标签
     * @param ReviewTag $reviewtag 评论标签model
     * @param array $inputs 更新的数据
     * @return ReviewTag|null
     */
    protected function saveReviewTag(ReviewTag $reviewTag, array $inputs)
    {
        if (array_key_exists('name', $inputs)) {
            $reviewTag->name = e(trim($inputs['name']));
        }
        
        if ($reviewTag->save()) {
            return $reviewTag;
        }
        
        return null;
    }    
}