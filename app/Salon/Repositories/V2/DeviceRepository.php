<?php

namespace App\Salon\Repositories\V2;

use App\Salon\Device;
use App\Salon\Repositories\DeviceRepository as DeviceRep;
/**
 *
 *
 *
 * @desc 设备类型数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class DeviceRepository extends DeviceRep
{
    /**
     *
     * 创建一个设备类型数据仓库实例
     * @param App\Salon\Device $device
     * @return void
     */
    public function __construct(Device $device)
    {
        $this->model = $device;
    }

    /**
     * 更新或创建设备类型
     * @param Device $device 设备类型model
     * @param array $inputs 更新的数据
     * @return Device|null
     */
    protected function saveDevice(Device $device, array $inputs)
    {
        if (array_key_exists('name', $inputs)) {
            $device->name = e(trim($inputs['name']));
        }
        if (array_key_exists('key', $inputs)) {
            $device->key = trim($inputs['key']);
        }
        if (array_key_exists('status', $inputs)) {
            $device->status = $inputs['status'];
        }
        
        if ($device->save()) {
            return $device;
        }
        
        return null;
    }    
}