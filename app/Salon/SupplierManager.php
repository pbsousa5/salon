<?php

namespace App\Salon;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @desc 门店管理者Model
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月24日
 *
 * @SWG\Model(id="SupplierManager")
 */
class SupplierManager extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'supplier_managers';
    
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
            'mobile',
            'password',
            'company_name',
            'legal_name',
            'id_num',
            'id_back_photo',
            'id_front_photo',
            'license_photo',
    ];
    
    // ========================================
    // model的属性
    //=========================================
    /**
     * @SWG\Property(name="id", type="integer", description="主键")
     * @var int
     */
    private $id;
    
    /**
     * @SWG\Property(name="mobile", type="string", description="作为唯一登陆，手机号码")
     * @var string
     */
    private $mobile;
    
    /**
     * @SWG\Property(name="password", type="string", description="登陆密码")
     * @var string
     */
    private $password;
    
    /**
     * @SWG\Property(name="company_name", type="string", description="公司名称")
     * @var string
     */
    private $company_name;
    
    /**
     * @SWG\Property(name="legal_name", type="string", description="法人姓名")
     * @var string
     */
    private $legal_name;
    
    /**
     * @SWG\Property(name="id_num", type="string", description="身份证号码")
     * @var string
     */
    private $id_num;
    
    /**
     * @SWG\Property(name="id_back_photo", type="string", description="身份证反面照片")
     * @var string
     */
    private $id_back_photo;
    
    /**
     * @SWG\Property(name="id_front_photo", type="string", description="身份证正面照片")
     * @var string
     */
    private $id_front_photo;
    
    /**
     * @SWG\Property(name="license_photo", type="string", description="营业执照照片")
     * @var string
     */
    private $license_photo;
}
