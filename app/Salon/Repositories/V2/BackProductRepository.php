<?php

namespace App\Salon\Repositories\V2;

use App\Salon\BackProduct;
use App\Salon\Repositories\BackProductRepository as BackProductRep;
/**
 *
 *
 *
 * @desc 退单中的产品仓库层
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class BackProductRepository extends BackProductRep
{
    /**
     *
     * 创建一个退单中的产品仓库实例
     * @param App\Salon\BackProduct $backproduct
     * @return void
     */
    public function __construct(BackProduct $backProduct)
    {
        $this->model = $backProduct;
    }

    /**
     * 保存或者更新店家地址信息
     * @param BackProduct $backproduct 退单model
     * @param array $inputs 更新的数据
     * @return BackProduct|null
     */
    protected function saveBackProduct(BackProduct $backProduct, array $inputs)
    {
        if (array_key_exists('back_order_id', $inputs)) {
            $backProduct->back_order_id = $inputs['back_order_id'];
        }
        if (array_key_exists('product_id', $inputs)) {
            $backProduct->product_id = $inputs['product_id'];
        }
        if (array_key_exists('barber_id', $inputs)) {
            $backProduct->barber_id = $inputs['barber_id'];
        }
        if (array_key_exists('barber_product_id', $inputs)) {
            $backProduct->barber_product_id = $inputs['barber_product_id'];
        }
        if (array_key_exists('supplier_id', $inputs)) {
            $backProduct->supplier_id = $inputs['supplier_id'];
        }
        if (array_key_exists('category_name', $inputs)) {
            $backProduct->category_name = e(trim($inputs['category_name']));
        }
        if (array_key_exists('product_name', $inputs)) {
            $backProduct->product_name = e(trim($inputs['product_name']));
        }
        if (array_key_exists('back_fee', $inputs)) {
            $backProduct->back_fee = $inputs['back_fee'];
        }
        if (array_key_exists('back_number', $inputs)) {
            $backProduct->back_number = $inputs['back_number'];
        }

        if ($backProduct->save()) {
            return $backProduct;
        }
        
        return null;
    }
}