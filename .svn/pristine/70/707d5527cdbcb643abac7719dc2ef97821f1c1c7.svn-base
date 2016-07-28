<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\FeedbackRepositoryInterface;
use App\Salon\Feedback;

/**
 * 
 * 
 * @desc 反馈仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class FeedbackRepository extends BaseRepository implements FeedbackRepositoryInterface
{
    /**
     * 
     * 创建一个反馈意见数据仓库实例
     * @param App\Salon\Feedback $feedback
     * @return void
     */
    public function __construct(Feedback $feedback)
    {
        $this->model = $feedback;
    }
    
    /**
     * 添加或者更新反馈意见
     * 
     * @param Feedback $feedback 反馈意见的model
     * @param array $inputs 添加或者更新数据
     * 
     * @return Feedback|null
     */
    protected function saveFeedback(Feedback $feedback, array $inputs)
    {
        if (array_key_exists('mobile', $inputs)) {
            $feedback->mobile = $inputs['mobile'];
        }
        if (array_key_exists('source', $inputs)) {
            $feedback->source = $inputs['source'];
        }
        if (array_key_exists('user_type', $inputs)) {
            $feedback->user_type = $inputs['user_type'];
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
        
        if ($feedback->save()) {
            return $feedback;
        }
        
        return null;
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
        $inputs = array_add($inputs, 'feedback_imgs', []);
        return $this->saveFeedback($this->createModel(), $inputs);
    }
    
}