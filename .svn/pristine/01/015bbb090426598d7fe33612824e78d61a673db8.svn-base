<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\VersionAppRepositoryInterface;
use App\Salon\VersionApp;

/**
 * 
 * 
 * @desc app版本数据仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class VersionAppRepository extends BaseRepository implements VersionAppRepositoryInterface
{
    /**
     * 
     * 创建一个app版本数据仓库实例
     * @param App\Salon\VersionApp $version
     * @return void
     */
    public function __construct(VersionApp $version)
    {
        $this->model = $version;
    }
    
}