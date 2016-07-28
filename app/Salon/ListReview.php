<?php

namespace App\Salon;

/**
 * 
 * 
 * @desc 评论列表
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="ListReview")
 */
class ListReview
{
    /**
     * @SWG\Property(name="cache", type="SupplierCache", description="门店评论相关的缓存")
     * @var SupplierCache
     */
    private $cache;
    
    /**
     * @SWG\Property(name="list", type="array", items="$ref:Review", description="门店评论相关的缓存")
     * @var Review
     */
    private $list;
}