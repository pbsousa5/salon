<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 消费者model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月23日
 *
 * @SWG\Model(id="Consumer")
 */
class Consumer extends Model
{
    
    /**
     * 数据库表名
     * @var string
     */
    protected $table = 'consumers';
    
    /**
     * 允许批量赋值的字段
     * @var array
     */
    protected $fillable = ['account', 'mobile', 'password', 'invitation_code'];
    
    /**
     * 转换为json时，禁止显示的字段
     * @var array
     */
    protected $hidden = ['password'];
    
    #********
    #* 一个消费者，可以关注多个门店，一个门店也可以被多个消费关注
    #* 通过设置中间表，将关系转换为一个消费对应多条关注记录，但是一个关注记录只属于一个消费者
    #* table: consumer_watchs
    #********
    public function watchs()
    {
        return $this->hasMany('App\Salon\ConsumerWatch', 'consumer_id', 'id');
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
    #* 根据性别查找
    #* @param $gender 1:男性， 0:女性
    #********
    public function scopeOfGender($query, $gender)
    {
        return $query->whereGender($gender);
    }
    
    #********
    #* 一个消费者可以拥有多张优惠券,一张优惠券，也可以被多个消费者拥有
    #* table: coupons
    #********
    public function coupons()
    {
        return $this->belongsToMany('App\Salon\Coupon', 'consumer_coupons', 'consumer_id', 'coupon_id');
    }
    
    /**
     * 获取门店评论
     * 模型对象关系：门店对应的评论，1对n
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany('App\Salon\Review', 'consumer_id', 'id');
    }
    
    /**
     * 获取客户消费日志
     * 模型对象关系：消费者对应消费日志，1对n
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consumeLog()
    {
        return $this->hasMany('App\Salon\ConsumeLog', 'consumer_id', 'id');
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
     * @SWG\Property(name="account", type="string", description="登陆账号")
     * @var string
     */
    private $account;
    
    /**
     * @SWG\Property(name="mobile", type="string", description="手机号码")
     * @var string
     */
    private $mobile;
    
    /**
     * @SWG\Property(name="password", type="string", description="密码")
     * @var string
     */
    private $password;
    
    /**
     * @SWG\Property(name="nickname", type="string", description="昵称,可修改")
     * @var string
     */
    private $nickname;
    
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
     * @SWG\Property(
     *  name="age_tag",
     *  type="integer",
     *  description="年龄标签,可修改",
     *  enum="{'0':'其他','1':'60后', '2':'70后', '3':'80后', '4':'90后', '5':'00后'}"
     * )
     * @var int
     */
    private $age_tag;
    
    /**
     * @SWG\Property(name="level_score", type="integer", description="等级积分")
     * @var int
     */
    private $level_score;
    
    /**
     * @SWG\Property(name="my_bean", type="integer", description="美发币数量")
     * @var int
     */
    private $my_bean;
    
    /**
     * @SWG\Property(name="my_coupon", type="integer", description="优惠券数量")
     * @var int
     */
    private $my_coupon;
    
    /**
     * @SWG\Property(name="weight", type="integer", description="用户权重值")
     * @var int
     */
    private $weight;
    
    /**
     * @SWG\Property(name="invitation_code", type="string", description="用户专属邀请码")
     * @var string
     */
    private $invitation_code;
    
    /**
     * @SWG\Property(name="token", type="string", description="32位长，获取后请使用token进行aes加密")
     * @var string
     */
    private $token;
}
