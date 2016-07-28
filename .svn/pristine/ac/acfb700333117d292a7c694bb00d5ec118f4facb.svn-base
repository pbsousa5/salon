<?php

namespace App\Salon\Repositories\V2;

use App\Salon\Notify;
use App\Salon\Repositories\NotifyRepository as NotifyRep;
/**
 *
 *
 *
 * @desc 通知信息数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class NotifyRepository extends NotifyRep
{
    /**
     *
     * 创建一个通知信息数据仓库实例
     * @param App\Salon\Notify $notify
     * @return void
     */
    public function __construct(Notify $notify)
    {
        $this->model = $notify;
    }

    /**
     * 更新或创建通知信息
     * @param Notify $notify 通知信息model
     * @param array $inputs 更新的数据
     * @return Notify|null
     */
    protected function saveNotify(Notify $notify, array $inputs)
    {
        if (array_key_exists('user_id', $inputs)) {
            $notify->user_id = $inputs['user_id'];
        }
        if (array_key_exists('user_type', $inputs)) {
            $notify->user_type = strtolower(trim($inputs['user_type']));
        }
        if (array_key_exists('title', $inputs)) {
            $notify->title = e(trim($inputs['title']));
        }
        if (array_key_exists('push_msg', $inputs)) {
            $notify->push_msg = trim($inputs['push_msg']);
        }
        if (array_key_exists('is_read', $inputs)) {
            $notify->is_read = $inputs['is_read'];
        }
        if (array_key_exists('notify_type', $inputs)) {
            $notify->notify_type = $inputs['notify_type'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $notify->updated_at = $inputs['updated_at'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $notify->created_at = $inputs['created_at'];
        }
        
        if ($notify->save()) {
            return $notify;
        }
        
        return null;
    }    
}