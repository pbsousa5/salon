<?php

namespace App\Salon\Repositories\V2;

use App\Salon\ProductCategory;
use App\Salon\Repositories\ProductCategoryRepository as ProductCategoryRep;
/**
 *
 *
 *
 * @desc 产品分类数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class ProductCategoryRepository extends ProductCategoryRep
{
    /**
     *
     * 创建一个产品分类数据仓库实例
     * @param App\Salon\ProductCategory $productcategory
     * @return void
     */
    public function __construct(ProductCategory $productCategory)
    {
        $this->model = $productCategory;
    }

    /**
     * 更新或创建产品分类
     * @param ProductCategory $productcategory 产品分类model
     * @param array $inputs 更新的数据
     * @return ProductCategory|null
     */
    protected function saveProductCategory(ProductCategory $productCategory, array $inputs)
    {
        if (array_key_exists('name', $inputs)) {
            $productCategory->name = e(trim($inputs['name']));
        }
        if (array_key_exists('sort_num', $inputs)) {
            $productCategory->sort_num = $inputs['sort_num'];
        }
        if (array_key_exists('describe', $inputs)) {
            $productCategory->describe = e(trim($inputs['describe']));
        }        
        
        if ($productCategory->save()) {
            return $productCategory;
        }
        
        return null;
    }    
}