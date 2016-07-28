<?php

namespace App\Salon\Repositories;

use App\Salon\Banner;
use App\Salon\Contracts\Repositories\BannerRepositoryInterface;

/**
 * 
 * 
 * @desc 获取幻灯片数据仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class BannerRepository extends BaseRepository implements BannerRepositoryInterface
{
    /**
     * 
     * 创建一个幻灯片数据仓库实例
     * @param App\Salon\Banner $banner
     * @return void
     */
    public function __construct(Banner $banner)
    {
        $this->model = $banner;
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $data 必须传入与模型查询相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @param  string $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($data='', $extra='', $size=10)
    {
        return $this->model->where('status', 1)->orderBy('updated_at', 'desc')->get();
    }
    
    /**
     * 获取指定资源
     *
     * @param int $id 资源id
     * @param array|string $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($id, $extra='')
    {
        return $this->model->find($id);
    }
    
}