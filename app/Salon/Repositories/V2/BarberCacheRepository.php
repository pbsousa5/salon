<?php

namespace App\Salon\Repositories\V2;

use App\Salon\BarberCache;
use App\Salon\Repositories\BarberCacheRepository as BarberCacheRep;
/**
 *
 *
 *
 * @desc 理发师缓存数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class BarberCacheRepository extends BarberCacheRep
{
    /**
     *
     * 创建一个理发师产品数据仓库实例
     * @param App\Salon\BarberCache $barbercache
     * @return void
     */
    public function __construct(BarberCache $barberCache)
    {
        $this->model = $barberCache;
    }

    /**
     * 更新或创建理发师缓存
     * @param BarberCache $barbercache 理发师缓存model
     * @param array $inputs 更新的数据
     * @return BarberCache|null
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
        if (array_key_exists('created_at', $inputs)) {
            $barberCache->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $barberCache->updated_at = $inputs['updated_at'];
        }
        
        if ($barberCache->save()) {
            return $barberCache;
        }
        
        return null;
    }    
}