<?php

namespace App\Salon\Repositories\V2;

use App\Salon\Address;
use App\Salon\Repositories\AddressRepository as AddressRep;
/**
 * 
 *
 * 
 * @desc 店家地址数据仓库层
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class AddressRepository extends AddressRep
{
    /**
     * 
     * 创建一个店家地址数据仓库实例
     * @param App\Salon\Address $address
     * @return void
     */
    public function __construct(Address $address)
    {
        $this->model = $address;
    }
    
    /**
     * 保存或者更新店家地址信息
     * @param Address $address 店家地址model
     * @param array $inputs 更新的数据
     * @return Address|null
     */
    protected function saveAddress(Address $address, array $inputs)
    {
        if (array_key_exists('user_id', $inputs)) {
            $address->user_id = $inputs['user_id'];
        }
        if (array_key_exists('user_type', $inputs)) {
            $address->user_type = strtolower(trim($inputs['user_type']));
        }
        if (array_key_exists('longitude', $inputs)) {
            $address->longitude = $inputs['longitude'];
        }
        if (array_key_exists('latitude', $inputs)) {
            $address->latitude = $inputs['latitude'];
        }
        if (array_key_exists('province', $inputs)) {
            $address->province = e(trim($inputs['province']));
        }
        if (array_key_exists('city', $inputs)) {
            $address->city = e(trim($inputs['city']));
        }
        if (array_key_exists('district', $inputs)) {
            $address->district = e(trim($inputs['district']));
        }
        if (array_key_exists('detail', $inputs)) {
            $address->detail = e(trim($inputs['detail']));
        }
        
        if ($address->save()) {
            return $address;
        }
        
        return null;
    }
}