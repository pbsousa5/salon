<?php

namespace App\Salon\Repositories\V2;

use App\Salon\SupplierCache;
use App\Salon\Repositories\SupplierCacheRepository as SupplierCacheRep;
/**
 *
 *
 *
 * @desc 门店缓存数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class SupplierCacheRepository extends SupplierCacheRep
{
    /**
     *
     * 创建一个门店缓存数据仓库实例
     * @param App\Salon\SupplierCache $supplierCache
     * @return void
     */
    public function __construct(SupplierCache $supplierCache)
    {
        $this->model = $supplierCache;
    }

    /**
     * 更新或创建门店缓存
     * @param SupplierCache $supplierCache 门店缓存model
     * @param array $inputs 更新的数据
     * @return SupplierCache|null
     */
    protected function saveSupplier(SupplierCache $supplierCache, array $inputs)
    {
        if (array_key_exists('supplier_id', $inputs)) {
            $supplierCache->supplier_id = $inputs['supplier_id'];
        }
        if (array_key_exists('reviews', $inputs)) {
            $supplierCache->reviews = serialize($inputs['reviews']);
        }
        if (array_key_exists('avg_score', $inputs)) {
            $supplierCache->avg_score = $inputs['avg_score'];
        }
        if (array_key_exists('lower_price', $inputs)) {
            $supplierCache->lower_price = $inputs['lower_price'];
        }
        if (array_key_exists('hot_product_ids', $inputs)) {
            $supplierCache->hot_product_ids = serialize($inputs['hot_product_ids']);
        }
        if (array_key_exists('busy_index', $inputs)) {
            $supplierCache->busy_index = $inputs['busy_index'];
        }
        if (array_key_exists('followers', $inputs)) {
            $supplierCache->followers = $inputs['followers'];
        }
        if (array_key_exists('tags', $inputs)) {
            $supplierCache->tags = serialize($inputs['tags']);
        }
        
        if ($supplierCache->save()) {
            return $supplierCache;
        }
        
        return null;
    }    
}