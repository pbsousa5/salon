<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V1\CouponController as CouponCon;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\V2\CouponService;
use App\Salon\Services\V2\LoginService;

class CouponController extends CouponCon
{
    /**
     * 优惠券的服务层
     * @var CouponService
     */
    protected $couponSer;
    
    /**
     * 登陆服务层
     * @var LoginService
     */
    protected $loginSer;
    
    public function __construct(EncrypterInterface $aes, CouponService $couponSer, LoginService $loginSer)
    {
        parent::__construct($aes, $couponSer, $loginSer);
    }
}