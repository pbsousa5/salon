<?php

namespace App\Salon\Repositories\V2;

use App\Salon\Banner;
use App\Salon\Repositories\BannerRepository as BannerRep;
/**
 *
 *
 *
 * @desc 获取幻灯片数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class BannerRepository extends BannerRep
{
    /**
     *
     * 建一个幻灯片数据仓库实例
     * @param App\Salon\Banner $banner
     * @return void
     */
    public function __construct(Banner $banner)
    {
        $this->model = $banner;
    }

    /**
     * 保存或者更新幻灯片信息
     * @param Banner $banner 幻灯片model
     * @param array $inputs 更新的数据
     * @return Banner|null
     */
    protected function saveBanner(Banner $banner, array $inputs)
    {
        if (array_key_exists('title', $inputs)) {
            $banner->title = e(trim($inputs['title']));
        }
        if (array_key_exists('img_url', $inputs)) {
            $banner->img_url = trim($inputs['img_url']);
        }
        if (array_key_exists('banner_info', $inputs)) {
            $banner->banner_info = $inputs['banner_info'];
        }
        if (array_key_exists('read_num', $inputs)) {
            $banner->read_num = $inputs['read_num'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $banner->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $banner->updated_at = $inputs['updated_at'];
        }
        
        if ($banner->save()) {
            return $banner;
        }
        
        return null;
    }
}