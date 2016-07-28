<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 * 
 * @desc 理发师模型
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月27日
 *
 *
 * @SWG\Model(id="Barber")
 */
class Barber extends Model
{
    //'1':'从未登陆','2':'登陆未完善资料','3':'登陆未添加项目','4':'拒绝绑定','5':'已绑定门店','6':'已解绑'
    const BARBER_STATUS_NOT_LOGIN = 1;#从未登陆
    const BARBER_STATUS_LOGIN_NOT_ADD_INFO = 2; #登陆未完善资料
    const BARBER_STATUS_LOGIN_NOT_ADD_PRO = 3;# 登陆未添加项目
    const BARBER_STATUS_LOGIN_REFUSE = 4;#拒绝绑定
    const BARBER_STATUS_BIND = 5;#理发师已经被绑定
    const BARBER_STATUS_NOT_BIND = 6;#理发师已解绑
    
    /**
     * 表名
     * @var string
     */
    protected $table = 'barbers';
    
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
            'supplier_id',
            'account',
            'mobile',
            'password',
            'nickname',
            'realname',
            'head_img',
            'gender',
            'age',
            'email',
            'work_life',
            'title',
            'distance',
            'longitude',
            'latitude',
            'province',
            'city',
            'district',
            'detail',
            'status',
            'descript',
    ];
    
    /**
     * 每一个理发师都属于一个门店
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo('App\Salon\Supplier', 'supplier_id', 'id');
    }
    
    /**
     * 获取理发师的缓存信息
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cache()
    {
        return $this->hasOne('App\Salon\BarberCache', 'barber_id', 'id');
    }
    
    /**
     * 每一个理发师，都拥有多条评论
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function review()
    {
        return $this->hasMany('App\Salon\Review', 'barber_id', 'id');
    }
    
    /**
     * 一个理发师拥有多个项目
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function barberProduct()
    {
        return $this->hasMany('App\Salon\BarberProduct', 'barber_id', 'id');
    }
    
    // ========================================
    // model的属性
    //=========================================
    /**
     * @var int
     * @SWG\Property(name="id", type="integer", description="理发师的主键")
     */
    private $id;
    
    /**
     * @var int
     * @SWG\Property(name="supplier_id", type="integer", description="理发师所属门店id，如果没有则为0")
     */
    private $supplier_id;
    
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
     * @SWG\Property(name="nickname", type="string", description="昵称")
     * @var string
     */
    private $nickname;
    
    /**
     * @SWG\Property(name="realname", type="string", description="真实姓名")
     * @var string
     */
    private $realname;
    
    /**
     * @SWG\Property(name="title", type="string", description="理发师职位")
     * @var string
     */
    private $title;
    
    /**
     * @SWG\Property(name="head_img", type="string", description="头像图片地址,可修改")
     * @var string
     */
    private $head_img;
    
    /**
     * @SWG\Property(
     *  name="gender",
     *  type="integer",
     *  description="性别,可修改",
     *  enum="{'0':'女','1':'男','-1':'未设置'}"
     * )
     * @var int
     */
    private $gender;
    
    /**
     * @SWG\Property(name="age", type="integer", description="年龄，只能为整数")
     * @var integer
     */
    private $age;
    
    /**
     * @SWG\Property(name="email", type="string", description="邮箱")
     * @var string
     */
    private $email;
    
    /**
     * @SWG\Property(name="work_life", type="integer", description="工作年限")
     * @var integer
     */
    private $work_life;
    
    /**
     * @SWG\Property(name="supplier", type="Supplier", description="所属门店信息")
     * @var integer
     */
    
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
    /**
     * @SWG\Property(
     *  name="status",
     *  type="integer",
     *  description="理发师状态",
     *  enum="{'1':'从未登陆','2':'登陆未完善资料','3':'登陆未添加项目','4':'拒绝绑定','5':'已绑定门店','6':'已解绑'}"
     * )
     * @var integer
     */
    private $status;
    
    /**
     * @var string
     * @SWG\Property(name="descript", type="string", description="理发师擅长的技能，用逗号分隔")
     */
    private $descript;
}