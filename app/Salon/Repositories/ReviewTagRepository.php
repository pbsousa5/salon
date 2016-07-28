<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\ReviewTagRepositoryInterface;
use App\Salon\ReviewTag;

/**
 * 
 * 
 * @desc 评论标签数据仓库实现
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class ReviewTagRepository extends BaseRepository implements ReviewTagRepositoryInterface
{
    /**
     * 
     * 创建一个评论标签数据仓库实例
     * @param App\Salon\ReviewTag $tag
     * @return void
     */
    public function __construct(ReviewTag $tag)
    {
        $this->model = $tag;
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
        return $this->model->get();
    }
    
}