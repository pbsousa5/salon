<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 横幅model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="Banner")
 */
class Banner extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'banners';
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
            'title',
            'img_url',
            'banner_info',
            'read_num',
    ];
    
    // ========================================
    // model的属性
    //=========================================
    
    /**
     * @var int
     * @SWG\Property(name="id", type="integer", description="地址的主键")
     */
    private $id;
    
    /**
     * @SWG\Property(name="title", type="string", description="banner标题")
     * @var string
     */
    private $title;
    
    /**
     * @SWG\Property(name="img_url", type="string", description="图片地址")
     * @var string
     */
    private $img_url;
    
    /**
     * @SWG\Property(name="banner_info", type="string", description="描述")
     * @var string
     */
    private $banner_info;
    
    /**
     * @SWG\Property(name="read_num", type="integer", description="阅读数")
     * @var integer
     */
    private $read_num;
}