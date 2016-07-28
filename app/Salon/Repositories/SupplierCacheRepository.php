<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\SupplierCacheRepositoryInterface;
use App\Salon\SupplierCache;

/**
 * 
 * 
 * @desc 门店缓存实现
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class SupplierCacheRepository extends BaseRepository implements SupplierCacheRepositoryInterface
{
    /**
     * 
     * 创建一个门店缓存数据仓库实例
     * @param App\Salon\SupplierCache $cache
     * @return void
     */
    public function __construct(SupplierCache $cache)
    {
        $this->model = $cache;
    }
    
}