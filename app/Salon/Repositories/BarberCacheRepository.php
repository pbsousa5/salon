<?php

namespace App\Salon\Repositories;

use Illuminate\Support\Str;
use App\Salon\BarberCache;
use App\Salon\Contracts\Repositories\BarberCacheRepositoryInterface;

/**
 * 
 * 
 * @desc 理发师缓存数据仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月27日
 */
class BarberCacheRepository extends BaseRepository implements BarberCacheRepositoryInterface
{
    /**
     *
     * 创建一个理发师产品数据仓库实例
     * @param Barber $barber
     * @return void
     */
    public function __construct(BarberCache $barberCache)
    {
        $this->model = $barberCache;
    }
    
    /**
     * 更新或创建理发师缓存
     * 
     */
    protected function saveBarberCache(BarberCache $barberCache, array $inputs)
    {
        if (array_key_exists('barber_id', $inputs)) {
            $barberCache->barber_id = $inputs['barber_id'];
        }
        if (array_key_exists('reviews', $inputs)) {
            $barberCache->reviews = serialize($inputs['reviews']);
        }
        if (array_key_exists('count', $inputs)) {
            $barberCache->count = serialize($inputs['count']);
        }
        if (array_key_exists('avg_score', $inputs)) {
            $barberCache->avg_score = $inputs['avg_score'];
        }
        if (array_key_exists('lower_price', $inputs)) {
            $barberCache->lower_price = $inputs['lower_price'];
        }
        if (array_key_exists('followers', $inputs)) {
            $barberCache->followers = $inputs['followers'];
        }
        
        $flag = $barberCache->save();
        return $barberCache;
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
        $inputs = array_add($inputs, 'reviews', ['k_score'=>0, 's_score'=>0, 'e_score'=>0, 'tags'=>[]]);
        $inputs = array_add($inputs, 'count', ['fund'=>0, 'review'=>0, 'order'=>0, 'consumer'=>0]);
        $inputs = array_add($inputs, 'avg_score', 0);
        $inputs = array_add($inputs, 'lower_price', 0);
        $inputs = array_add($inputs, 'followers', 0);
        
        return $this->saveBarberCache($this->createModel(), $inputs);
    }
    
    /**
     * 更新特定id资源
     *
     * @param  int $id 资源id
     * @param  array $inputs 必须传入与更新模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return void
     */
    public function update($id, $inputs, $extra='')
    {
        $barberCache = $this->model->where('id', $id)->first();
        if (is_null($barberCache)) {
            return null;
        }
        
        // 检查是否更新最低价
        if (array_key_exists('lower_price', $inputs)) {
            // 如果现存价格小于传入的价格，则保持不变
            if ($barberCache->lower_price <= $inputs['lower_price']) {
                $inputs = array_remove_keys($inputs, ['lower_price']);
            }
        }
        
        return $this->saveBarberCache($barberCache, $inputs);
    }
    
}