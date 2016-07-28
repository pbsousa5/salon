<?php

namespace App\Salon\Repositories\V2;

use App\Salon\SupplierManager;
use App\Salon\Repositories\SupplierManagerRepository as SupplierManagerRep;
/**
 *
 *
 *
 * @desc 门店管理者数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class SupplierManagerRepository extends SupplierManagerRep
{
    /**
     *
     * 创建一个门店管理者数据仓库实例
     * @param App\Salon\SupplierManager $supplierManager
     * @return void
     */
    public function __construct(SupplierManager $supplierManager)
    {
        $this->model = $supplierManager;
    }

    /**
     * 更新或创建门店管理者
     * @param SupplierManager $supplierManager 门店管理者model
     * @param array $inputs 更新的数据
     * @return SupplierManager|null
     */
    protected function saveSupplierManager(SupplierManager $supplierManager, array $inputs)
    {
        if (array_key_exists('account', $inputs)) {
            $supplierManager->account = trim($inputs['account']);
        }
        if (array_key_exists('mobile', $inputs)) {
            $supplierManager->mobile = trim($inputs['mobile']);
        }
        if (array_key_exists('password', $inputs)) {
            $supplierManager->password = bcrypt($inputs['password']);
        }
        if (array_key_exists('company_name', $inputs)) {
            $supplierManager->company_name = e(trim($inputs['company_name']));
        }
        if (array_key_exists('legal_name', $inputs)) {
            $supplierManager->legal_name = e(trim($inputs['legal_name']));
        }
        if (array_key_exists('id_num', $inputs)) {
            $supplierManager->id_num = trim($inputs['id_num']);
        }
        if (array_key_exists('id_photos', $inputs)) {
            $supplierManager->id_photos = serialize($inputs['id_photos']);
        }
        if (array_key_exists('license_photo', $inputs)) {
            $supplierManager->license_photo = $inputs['license_photo'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $supplierManager->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $supplierManager->updated_at = $inputs['updated_at'];
        }
        
        if ($supplierManager->save()) {
            return $supplierManager;
        }
        
        return null;
    }    
}