<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;
use Predis\Protocol\Text\Handler\IntegerResponse;

/**
 *
 *
 * @desc 门店Model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 *
 * @SWG\Model(id="Supplier")
 */
class Supplier extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'suppliers';
    
    /**
     * 转换为json时，禁止显示的字段
     * @var array
     */
    protected $hidden = ['password'];
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = [
            'supplier_manager_id',
            'account',
            'mobile',
            'password',
            'name',
            'staff_count',
            'business_time',
            'phones',
            'gallerys',
            'legal_name',
            'id_num',
            'id_back_photo',
            'id_front_photo',
            'license_photo',
            'basic_discount',
            'status',
            'is_first',
    ];
    
    /**
     * 每一个理发店，都拥有多个理发师
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function barber()
    {
        return $this->hasMany('App\Salon\Barber', 'supplier_id', 'id');
    }
    
    /**
     * 每一个理发店，都拥有多条评论
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function review()
    {
        return $this->hasMany('App\Salon\Review', 'supplier_id', 'id');
    }
    
    /**
     * 缓存
     * 模型对象关系：门店对应的缓存信息，1对1
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cache()
    {
        return $this->hasOne('App\Salon\SupplierCache', 'supplier_id', 'id');
    }
    
    /**
     * 地址
     * 模型对象关系：门店对应的地址，1对1
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address()
    {
        return $this->hasOne('App\Salon\Address', 'user_id', 'id');
    }
    
    /**
     * 获取门店产品
     * 模型对象关系：门店对应的地址，1对n
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Salon\Product', 'supplier_id', 'id');
    }
    
    #********
    #* 根据电话号码，查询消费者的信息
    #* @param $mobile 11位手机号码
    #********
    public function scopeOfMobile($query, $mobile)
    {
        return $query->whereMobile($mobile);
    }
    
    #********
    #* 根据状态码，显示不同商店
    #* @param $status 0:关店, 1:合作营业店, 2:未合作营业店, 3:休息中, 4:新店申请
    #********
    public function scopeOfStatus($query, $status)
    {
        return $query->whereStatus($status);
    }
    
    // ========================================
    // model的属性
    //=========================================
    /**
     * @SWG\Property(name="id", type="integer", description="主键")
     * @var int
     */
    private $id;
    
    /**
     * @SWG\Property(
     *  name="hasWithdraw",
     *  type="integer",
     *  description="是否有提现申请",
     *  enum="{'0':'没有','1':'有'}"
     * )
     * @var int
     */
    private $hasWithdraw;
    
    /**
     * @SWG\Property(name="supplier_manager_id", type="integer", description="门店管理者id，没有时设置为0")
     * @var int
     */
    private $supplier_manager_id;
    
    /**
     * @SWG\Property(name="account", type="string", description="登陆账户，一般默认为手机号")
     * @var string
     */
    private $account;
    
    /**
     * @SWG\Property(name="mobile", type="string", description="11位手机号码")
     * @var string
     */
    private $mobile;
    
    /**
     * @SWG\Property(name="password", type="string", description="密码")
     * @var string
     */
    private $password;
    
    /**
     * @SWG\Property(name="name", type="string", description="店名")
     * @var string
     */
    private $name;
    
    /**
     * @SWG\Property(name="staff_count", type="integer", description="理发师数量")
     * @var int
     */
    private $staff_count;
    
    /**
     * @SWG\Property(name="business_time", type="array", items="$ref:BusTime", description="营业时间")
     * @var string
     */
    private $business_time;
    
    /**
     * @SWG\Property(name="phones", type="array", description="店家联系电话集合")
     * @var string
     */
    private $phones;
    
    /**
     * @SWG\Property(name="gallerys", type="array", description="店家图片展示集合")
     * @var string
     */
    private $gallerys;
    
    /**
     * @SWG\Property(name="legal_name", type="string", description="法人姓名")
     * @var string
     */
    private $legal_name;
    
    /**
     * @SWG\Property(name="id_number", type="string", description="身份证号码")
     * @var string
     */
    private $id_number;
    
    /**
     * @SWG\Property(name="id_back_photo", type="string", description="身份证反面照片地址")
     * @var string
     */
    private $id_back_photo;
    
    /**
     * @SWG\Property(name="id_front_photo", type="string", description="身份证正面照片地址")
     * @var string
     */
    private $id_front_photo;
    
    /**
     * @SWG\Property(name="license_photo", type="string", description="营业执照照片")
     * @var string
     */
    private $license_photo;
    
    /**
     * @SWG\Property(name="basic_discount", type="integer", description="店家设置的基本折扣,获取后除以100")
     * @var int
     */
    private $basic_discount;
    
    /**
     * @SWG\Property(
     *  name="status",
     *  type="integer",
     *  description="门店状态",
     *  enum="{'0':'关店','1':'合作营业店','2':'未合作营业店','3':'休息中','4':'新店申请'}"
     * )
     * @var integer
     */
    private $status;
    
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
     * @SWG\Property(name="pitch", type="string", description="用户距离该门店的距离，单位m")
     */
    private $pitch;
    
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
    
    /**
     * @SWG\Property(name="reviews", type="string", description="评论的相关信息集合")
     * @var string
     */
    private $reviews;
    
    /**
     * @SWG\Property(name="avg_score", type="integer", description="门店的综合得分")
     * @var integer
     */
    private $avg_score;
    
    /**
     * @SWG\Property(name="lower_price", type="integer", description="最低价格")
     * @var int
     */
    private $lower_price;
    
    /**
     * @SWG\Property(name="hot_product_id", type="string", description="热门商品id集合")
     * @var string
     */
    private $hot_product_ids;
    
    /**
     * @SWG\Property(name="busy_index", type="integer", description="忙碌指数值")
     * @var int
     */
    private $busy_index;
    
    /**
     * @SWG\Property(name="followers", type="integer", description="关注者")
     * @var int
     */
    private $followers;
    
    /**
     * @SWG\Property(
     *  name="watcher",
     *  type="integer",
     *  description="是否关注",
     *  enum="{'1':'已经关注','0':'还未关注'}"
     * )
     * @var boolen
     */
    private $watcher;
    
    /**
     * @SWG\Property(name="tags", type="string", description="评价的标签统计")
     * @var string
     */
    private $tags;
    
    /**
     * @SWG\Property(name="products", type="array", items="$ref:Product", description="有两个最低价格产品")
     * @var string
     */
    private $products;
    
    /**
     * @SWG\Property(name="is_first", type="integer", description="是否首次登陆表示", enum="{'1':'首次登陆','0':'非首次登陆'}")
     * @var integer
     */
    private $is_first;
}
