<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 版本信息Model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 *
 * @SWG\Model(id="VersionApp")
 */
class VersionApp extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'version_apps';
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
            'device_id',
            'version_code',
            'version_id',
            'upgrade_point',
            'package_url',
    ];
    
    // ========================================
    // model的属性
    //=========================================
    /**
     * @SWG\Property(name="id", type="integer", description="主键")
     * @var int
     */
    private $id;
    
    /**
     * @SWG\Property(name="device_id", type="integer", description="设备id")
     * @var int
     */
    private $device_id;
    
    /**
     * @SWG\Property(name="version_code", type="string", description="版本编码")
     * @var string
     */
    private $version_code;
    
    /**
     * @SWG\Property(name="version_id", type="integer", description="版本号，以此来识别新版本")
     * @var int
     */
    private $version_id;
    
    /**
     * @SWG\Property(name="upgrade_point", type="string", description="升级说明")
     * @var string
     */
    private $upgrade_point;
    
    /**
     * @SWG\Property(name="package_url", type="string", description="app包地址")
     * @var string
     */
    private $package_url;
}
