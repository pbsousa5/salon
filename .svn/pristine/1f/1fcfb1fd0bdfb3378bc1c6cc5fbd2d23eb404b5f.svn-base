<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 * 
 * @desc 地址类
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="Address")
 */
class Address extends Model
{
    /**
     * 数据库表
     * @var string
     */
    protected $table = 'addresss';
    
    /**
     * 关闭自动更新时间戳
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
            'user_id',
            'user_type',
            'distance',
            'longitude',
            'latitude',
            'province',
            'city',
            'district',
            'detail',
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
     * @var int
     * @SWG\Property(name="user_id", type="integer", description="关联的用户id")
     */
    private $user_id;
    
    /**
     * @var int
     * @SWG\Property(
     *  name="user_type",
     *  type="integer",
     *  description="用户的身份",
     *  enum="{'supplier':'门店','barber':'理发师','manager':'门店管理者'}"
     * )
     */
    private $user_type;
    
    /**
     * @var string
     * @SWG\Property(name="longitude", type="string", description="地址的经度，范围(-180, 180)")
     */
    private $longitude;
    
    /**
     * @var string
     * @SWG\Property(name="latitude", type="string", description="地址的纬度，范围(-90， 90)")
     */
    private $latitude;
    
    /**
     * @var string
     * @SWG\Property(name="province", type="string", description="省")
     */
    private $province;
    
    /**
     * @var string
     * @SWG\Property(name="city", type="string", description="市")
     */
    private $city;
    
    /**
     * @var string
     * @SWG\Property(name="district", type="string", description="区")
     */
    private $district;
    
    /**
     * @var string
     * @SWG\Property(name="detail", type="string", description="详细地址")
     */
    private $detail;
}
