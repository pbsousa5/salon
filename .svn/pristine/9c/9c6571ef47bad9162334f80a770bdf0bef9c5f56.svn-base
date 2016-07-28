<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\NotifyRepositoryInterface;
use App\Salon\Notify;

/**
 * 
 * 
 * @desc 通知信息数据仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class NotifyRepository extends BaseRepository implements NotifyRepositoryInterface
{
    /**
     * 
     * 创建一个通知消息数据仓库实例
     * @param App\Salon\Notify $notift
     * @return void
     */
    public function __construct(Notify $notify)
    {
        $this->model = $notify;
    }
    
    /**
     * 根据给定的条件，统计有多少条记录
     *
     * @param string $where 查询的条件
     * @param string $extra 额外的条件
     * @return integer
     */
    public function countByWhere($where, $extra='')
    {
        $query = $this->createModel()->newQuery();
        
        foreach ($where as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->count();
    }
    
    /**
     * 添加或者更新消息通知
     * 
     * @param Notify $notify 消息模型
     * @param array $inputs 创建或更新的数据
     * @return boolean
     */
    protected function saveNotify(Notify $notify, array $inputs)
    {
        if (array_key_exists('user_id', $inputs)) {
            $notify->user_id = $inputs['user_id'];
        }
        if (array_key_exists('user_type', $inputs)) {
            $notify->user_type = strtolower($inputs['user_type']);
        }
        if (array_key_exists('title', $inputs)) {
            $notify->title = e(trim($inputs['title']));
        }
        if (array_key_exists('push_msg', $inputs)) {
            $notify->push_msg = e(trim($inputs['push_msg']));
        }
        if (array_key_exists('is_read', $inputs)) {
            $notify->is_read = $inputs['is_read'];
        }
        if (array_key_exists('notify_type', $inputs)) {
            $notify->notify_type = $inputs['notify_type'];
        }
        
        return $notify->save();
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $data 根据用户id及用户类型获取数据['user_id', 'user_type']
     * @param  string|array $extra 可选额外传入的参数
     * @param  string $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($data, $extra='', $size=10)
    {
        $cols = $this->model
                        ->where('user_id', $data['user_id'])
                        ->where('user_type', $data['user_type'])
                        ->orderBy('created_at', 'desc')
                        ->paginate($size);
        
        return $cols;
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
        return $this->saveNotify($this->createModel(), $inputs);
    }
    
    /**
     * 获取指定资源
     *
     * @param int $id 资源id
     * @param array $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id, $extra=[])
    {
        $query = $this->createModel()->newQuery();
        
        $query->where('id', $id);
        foreach ($extra as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->first();
    }
    
    /**
     * 更新特定id资源
     *
     * @param  int $id 资源id
     * @param  array $inputs 必须传入与更新模型相关的数据
     * @param  array $extra 可选额外传入的参数
     * @return boolean
    */
    public function update($id, $inputs, $extra=[])
    {
        $notify = $this->show($id, $extra);
        
        $inputs = array_add($inputs, 'is_read', 1);
        
        return $this->saveNotify($notify, $inputs);
    }
    
    /**
     * 删除特定id资源
     *
     * @param  int $id 资源id
     * @param  array $extra 可选额外传入的参数
     * @param array $extra 可选额外传入的参数
     * @return void
    */
    public function destroy($id, $extra=[])
    {
        $query = $this->createModel()->newQuery();
        
        $query->where('id', $id);
        foreach ($extra as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->delete();
    }
}