<?php

namespace App\Salon\Repositories\V2;

use App\Salon\VersionApp;
use App\Salon\Repositories\VersionAppRepository as VersionAppRep;
/**
 *
 *
 *
 * @descapp版本数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class VersionAppRepository extends VersionAppRep
{
    /**
     *
     * 创建一个app版本数据仓库实例
     * @param App\Salon\VersionApp $versionapp
     * @return void
     */
    public function __construct(VersionApp $versionApp)
    {
        $this->model = $versionApp;
    }

    /**
     * 更新或创建app版本
     * @param VersionApp $versionapp app版本model
     * @param array $inputs 更新的数据
     * @return VersionApp|null
     */
    protected function saveVersionApp(VersionApp $versionApp, array $inputs)
    {
        if (array_key_exists('device_id', $inputs)) {
            $versionApp->device_id = $inputs['device_id'];
        }
        if (array_key_exists('version_code', $inputs)) {
            $versionApp->version_code = trim($inputs['version_code']);
        }
        if (array_key_exists('version_id', $inputs)) {
            $versionApp->version_id = $inputs['version_id'];
        }
        if (array_key_exists('upgrade_point', $inputs)) {
            $versionApp->upgrade_point = e(trim($inputs['upgrade_point']));
        }
        if (array_key_exists('package_url', $inputs)) {
            $versionApp->package_url = $inputs['package_url'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $versionApp->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $versionApp->updated_at = $inputs['updated_at'];
        }
        
        if ($versionApp->save()) {
            return $versionApp;
        }
        
        return null;
    }    
}