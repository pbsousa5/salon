<?php

namespace App\Salon\Repositories;

use App\Salon\Consumer;
use App\Salon\Contracts\Repositories\ConsumerRepositoryInterface;
use Illuminate\Support\Str;
use App\Libary\Util\String;

/**
 * 
 * @desc 消费者数据仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class ConsumerRepository extends BaseRepository implements ConsumerRepositoryInterface
{
    /**
     * 
     * 创建一个消费者数据仓库实例
     * @param App\Salon\Consumer $consumer
     * @return void
     */
    public function __construct(Consumer $consumer)
    {
        $this->model = $consumer;
    }
    
    /**
     * 创建、或者更新用户信息
     * 
     * @param Consumer $consumer 用户model
     * @param array $inputs 创建或者更新的数据
     * @return Consumer|null
     */
    protected function saveConsumer(Consumer $consumer, array $inputs)
    {
        if (array_key_exists('mobile', $inputs)) {
            $consumer->account = $inputs['mobile'];
            $consumer->mobile = $inputs['mobile'];
        }
        if (array_key_exists('password', $inputs)) {
            $consumer->password = bcrypt($inputs['password']);
        }
        if (array_key_exists('nickname', $inputs)) {
            $consumer->nickname = e(trim($inputs['nickname']));
        }
        if (array_key_exists('head_img', $inputs)) {
            $consumer->head_img = trim($inputs['head_img']);
        }
        if (array_key_exists('gender', $inputs)) {
            $consumer->gender = $inputs['gender'];
        }
        if (array_key_exists('age_tag', $inputs)) {
            $consumer->age_tag = $inputs['age_tag'];
        }
        if (array_key_exists('level_score', $inputs)) {
            $consumer->level_score = $inputs['level_score'];
        }
        if (array_key_exists('my_bean', $inputs)) {
            $consumer->my_bean = $inputs['my_bean'];
        }
        if (array_key_exists('my_coupon', $inputs)) {
            $consumer->my_coupon = $inputs['my_coupon'];
        }
        if (array_key_exists('weight', $inputs)) {
            $consumer->weight = $inputs['weight'];
        }
        if (array_key_exists('invitation_code', $inputs)) {
            $consumer->invitation_code = $inputs['invitation_code'];
        }

        if ($consumer->save()) {
            return $consumer;
        } else {
            return null;
        }
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $data 必须传入与模型查询相关的数据
     * @param  string|array $extra 可选额外传入的参数 level weight 
     * @param  int $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($data=[], $extra='level', $size=10)
    {
        // 检查分页是否是数字，如果不是设置为10
        if (!is_numeric($size)) {
            $size = 10;
        }

        switch ($extra) {
            // 按照等级倒序
            case 'level':
                array_add($data, 'level_score', 0);
                $ret = $this->model
                                ->where('level_score', '>', e($data['level_score']))
                                ->orderBy('level_score', 'desc')
                                ->paginate($size);
                break;
            // 按照权重倒序
            case 'weight':
                array_add($data, 'weight', 0);
                $ret = $this->model
                                ->where('weight', '>', e($data['weight']))
                                ->orderBy('weight', 'desc')
                                ->paginate($size);
                break;
            default:
                $ret = null;
                break;
        }
        
        return $ret;
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
        $inputs = array_add($inputs, 'invitation_code', String::randString(6, 2));
        return $this->saveConsumer($this->createModel(), $inputs);
    }
    
    /**
     * 获取指定资源
     *
     * @param array $where 查询资源的条件
     * @param array|string $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($where, $extra='')
    {
        $query = $this->createModel()->newQuery();
        
        foreach ($where as $field => $value) {
            if (!Str::equals($field, 'password')) {
                $query->where($field, $value);
            }
        }
        
        return $query->first();
    }
    
    /**
     * 更新特定资源
     *
     * @param  array $where 更新的特定资源查找条件
     * @param  array $inputs 必须传入与更新模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model|null
    */
    public function update($where, $inputs, $extra='')
    {
        $consumer = $this->show($where);
        if (is_null($consumer)) {
            return null;
        }
         return $this->saveConsumer($consumer, $inputs);
    }
    
}