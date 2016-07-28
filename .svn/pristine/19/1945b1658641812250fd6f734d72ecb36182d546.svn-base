<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 * 
 * @desc 理发师作品集model
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月29日
 * 
 * @SWG\Model(id="BarberSample")
 */
class BarberSample extends Model
{
    /**
     * 数据库表
     * @var string
     */
    protected $table = 'barber_samples';
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
            'barber_id',
            'opus_img',
            'small_title',
            'describe',
    ];
    
    /**
     * @SWG\Property(name="id", type="integer", description="主键")
     * @var int
     */
    private $id;
    
    /**
     * @SWG\Property(name="barber_id", type="integer", description="对应的理发师键")
     * @var int
     */
    private $barber_id;
    
    /**
     * @SWG\Property(name="opus_img", type="array", description="作品图片路径")
     * @var int
     */
    private $opus_img;
    
    /**
     * @SWG\Property(name="small_title", type="string", description="作品图片小标题")
     * @var int
     */
    private $small_title;
    
    /**
     * @SWG\Property(name="describe", type="string", description="作品图片描述")
     * @var int
     */
    private $describe;
    
    /**
     * @SWG\Property(name="updated_at", type="string", description="作品图片更新时间")
     * @var int
     */
    private $updated_at;
}