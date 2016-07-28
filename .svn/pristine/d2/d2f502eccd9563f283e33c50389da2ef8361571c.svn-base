<?php

namespace App\Salon\Repositories\V2;

use App\Salon\Feedback;
use App\Salon\Repositories\FeedbackRepository as FeedbackRep;
/**
 *
 *
 *
 * @desc 反馈数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class FeedbackRepository extends FeedbackRep
{
    /**
     *
     * 创建一个反馈数据仓库实例
     * @param App\Salon\Feedback $feedback
     * @return void
     */
    public function __construct(Feedback $feedback)
    {
        $this->model = $feedback;
    }

    /**
     * 更新或创建反馈
     * @param Feedback $feedback 反馈model
     * @param array $inputs 更新的数据
     * @return Feedback|null
     */
    protected function saveFeedback(Feedback $feedback, array $inputs)
    {
        if (array_key_exists('mobile', $inputs)) {
            $feedback->mobile = trim($inputs['mobile']);
        }
        if (array_key_exists('source', $inputs)) {
            $feedback->source = strtolower(trim($inputs['source']));
        }
        if (array_key_exists('user_type', $inputs)) {
            $feedback->user_type = strtolower(trim($inputs['user_type']));
        }
        if (array_key_exists('feedback_txt', $inputs)) {
            $feedback->feedback_txt = e(trim($inputs['feedback_txt']));
        }
        if (array_key_exists('feedback_imgs', $inputs)) {
            $feedback->feedback_imgs = serialize($inputs['feedback_imgs']);
        }
        if (array_key_exists('status', $inputs)) {
            $feedback->status = $inputs['status'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $feedback->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $feedback->updated_at = $inputs['updated_at'];
        }
        
        if ($feedback->save()) {
            return $feedback;
        }
        
        return null;
    }    
}