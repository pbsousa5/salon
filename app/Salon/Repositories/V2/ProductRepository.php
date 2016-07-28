<?php

namespace App\Salon\Repositories\V2;

use App\Salon\Product;
use App\Salon\Repositories\ProductRepository as ProductRep;
/**
 *
 *
 *
 * @desc 理发师缓存数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class ProductRepository extends ProductRep
{
    /**
     *
     * 创建一个理发师产品数据仓库实例
     * @param App\Salon\Product $product
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    /**
     * 更新或创建理发师缓存
     * @param Product $product 理发师缓存model
     * @param array $inputs 更新的数据
     * @return Product|null
     */
    protected function saveProduct(Product $product, array $inputs)
    {
        if (array_key_exists('supplier_id', $inputs)) {
            $product->supplier_id = $inputs['supplier_id'];
        }
        if (array_key_exists('category_id', $inputs)) {
            $product->category_id = $inputs['category_id'];
        }
        if (array_key_exists('product_name', $inputs)) {
            $product->product_name = e(trim($inputs['product_name']));
        }
        if (array_key_exists('product_desc', $inputs)) {
            $product->product_desc = e(trim($inputs['product_desc']));
        }
        if (array_key_exists('sell_price', $inputs)) {
            $product->sell_price = $inputs['sell_price'];
        }
        if (array_key_exists('sing_price', $inputs)) {
            $product->sing_price = $inputs['sing_price'];
        }
        if (array_key_exists('original_price', $inputs)) {
            $product->original_price = $inputs['original_price'];
        }
        if (array_key_exists('const_stock', $inputs)) {
            $product->const_stock = $inputs['const_stock'];
        }
        if (array_key_exists('total_stock', $inputs)) {
            $product->total_stock = $inputs['total_stock'];
        }
        if (array_key_exists('quota_num', $inputs)) {
            $product->quota_num = $inputs['quota_num'];
        }
        if (array_key_exists('status', $inputs)) {
            $product->status = $inputs['status'];
        }
        if (array_key_exists('sold_type', $inputs)) {
            $product->sold_type = $inputs['sold_type'];
        }
        if (array_key_exists('start_sold_time', $inputs)) {
            $product->start_sold_time = $inputs['start_sold_time'];
        }
        if (array_key_exists('rich_desc', $inputs)) {
            $product->rich_desc = $inputs['rich_desc'];
        }
        if (array_key_exists('is_real', $inputs)) {
            $product->is_real = $inputs['is_real'];
        }
        if (array_key_exists('is_delete', $inputs)) {
            $product->is_delete = $inputs['is_delete'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $product->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $product->updated_at = $inputs['updated_at'];
        }
        
        if ($product->save()) {
            return $product;
        }
        
        return null;
    }    
}