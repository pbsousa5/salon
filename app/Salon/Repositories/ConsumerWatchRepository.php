<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\ConsumerWatchRepositoryInterface;
use App\Salon\ConsumerWatch;
use Illuminate\Support\Str;

/**
 * 
 * 
 * @desc 消费者关注门店仓库数据DB是实现
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class ConsumerWatchRepository extends BaseRepository implements ConsumerWatchRepositoryInterface
{
    /**
     * 
     * 创建一个关注数据仓库实例
     * @param App\Salon\ConsumerWatch $watch
     * @return void
     */
    public function __construct(ConsumerWatch $watch)
    {
        $this->model = $watch;
    }
    
    /**
     * 添加消费者关注的对象信息
     * @param ConsumerWatch $follower 消费者关注model
     * @param array $inputs 关注资料
     * @return boolean
     */
    protected function saveWatch(ConsumerWatch $follower, array $inputs)
    {
        if (array_key_exists('consumer_id', $inputs)) {
            $follower->consumer_id = $inputs['consumer_id'];
        }
        if (array_key_exists('user_id', $inputs)) {
            $follower->user_id = $inputs['user_id'];
        }
        if (array_key_exists('user_type', $inputs)) {
            $follower->user_type = $inputs['user_type'];
        }
        $follower->created_at = date('Y-m-d H:i:s', time());
        
        return $follower->save();
    }
    
    /**
     * 获取资源列表
     *
     * @param  int $id 传入用户id数组
     * @param  string $user_type 用户类型 supplier barber
     * @param  string $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($id, $user_type='', $size=10)
    {
        return $this->model
                        ->where('consumer_id', $id)
                        ->where('user_type', $user_type)
                        ->orderBy('created_at', 'desc')
                        ->paginate($size)
                        ->lists('user_id');
    }
    
    /**
     * 存储资源
     *
     * @param  array $inputs 必须传入与存储模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model
     */
    public function store($inputs, $user_type='')
    {
        $inputs = array_add($inputs, 'user_type', $user_type);
        return $this->saveWatch($this->createModel(), $inputs);
    }
    
    /**
     * 获取指定资源
     *
     * @param array $where 查询条件
     * @param array|string $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($where, $type='supplier')
    {
        $query = $this->createModel()->newQuery();
        $query->where('user_type', $type);
        foreach ($where as $key => $val) {
            $query->where($key, $val);
        }
        
        return $query->first();
    }
    
    /**
     * 删除特定资源
     *
     * @param  array $where 删除条件
     * @param  string $user_type 用户类型 supplier barber
     * @return void
    */
    public function destroy($where, $user_type='')
    {
        $query = $this->createModel()->newQuery();
        
        foreach ($where as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->delete();
    }
}