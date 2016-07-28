<?php

namespace App\Salon\Services;

use App\Salon\Notify;
use Illuminate\Support\Str;
use App\Salon\Repositories\NotifyRepository;

/**
 * 
 * 
 * @desc 消息通知服务
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月28日
 */
class NotifyService
{
    /**
     * 消息通知的数据仓库
     * @var NotifyRepository
     */
    protected $notifyRe;
    
    public function __construct(NotifyRepository $notifyRe)
    {
        $this->notifyRe = $notifyRe;
    }
    
    /**
     * 获取用户是否有未读消息
     * 
     * @param integer $user_id 用户id
     * @param string $user_type 用户
     * 
     * @return boolean
     */
    public function getUserNotifyNotRead($user_id, $user_type)
    {
        $where = [
                'user_id' => $user_id,
                'user_type' => $user_type,
                'is_read' => 0,
        ];
        $count = $this->notifyRe->countByWhere($where);
        if ($count != 0) {
            return true;
        }
        
        return false;
    }
    
    /**
     * 添加一个消息
     * 
     * @param App\Salon\Notify $obj 消息model
     * @return boolean
     */
    public function addNotify(Notify $obj)
    {
        return $this->notifyRe->store($obj->toArray());
    }
    
    /**
     * 显示指定id的资源
     * 
     * @param integer $id 指定的消息id
     * @param array $extra 查询的额外信息
     * @return App\Salon\Notify
     */
    public function show($id, array $extra)
    {
        $notify = $this->notifyRe->show($id, $extra);
        
        if (!is_null($notify) && $notify->is_read==0) {
            $this->notifyRe->update($id, ['is_read'=>1]);
        }
        
        return $notify;
    }
    
    /**
     * 删除指定id资源
     *
     * @param integer $id 指定的消息id
     * @param array $extra 查询的额外信息
     * @return boolen
     */
    public function delNotify($id, array $extra)
    {
        return $this->notifyRe->destroy($id, $extra);
    }
    
    /**
     * 根据用户id，以及用户类型，获取该用户的所有消息
     *
     * @param integer $user_id 指定的用户资源id
     * @param string $user_type 指定的用户类型,consumer:用户 supplier:门店
     * @return Illuminate\Support\Collection
     */
    public function listNotify($user_id, $user_type, $size)
    {
        return $this->notifyRe->index(compact('user_id', 'user_type'), '', $size);
    }
}