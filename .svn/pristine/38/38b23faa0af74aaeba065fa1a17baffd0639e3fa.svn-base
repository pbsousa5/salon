<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V1\OrderController as OrderCon;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\V2\OrderService;
use App\Salon\Services\V2\ConsumerService;
use App\Salon\Services\V2\CouponService;
use App\Salon\Services\V2\ProductService;
use App\Salon\Services\V2\SupplierService;
use App\Salon\Services\V2\LoginService;

class OrderController extends OrderCon
{
    /**
     * 订单的服务层
     * @var OrderService
     */
    protected $orderSer;
    
    /**
     * 消费者服务层
     * @var ConsumerService
     */
    protected $consumerSer;
    
    /**
     * 优惠券服务层
     * @var CouponService
     */
    protected $couponSer;
    
    /**
     * 产品的服务层
     * @var ProductService
     */
    protected $productSer;
    
    /**
     * 门店服务层
     * @var SupplierService
     */
    protected $supplierSer;
    
    /**
     * The LoginService instance.
     * @var LoginService
     */
    protected $loginSer;
    
    public function __construct(
            EncrypterInterface $aes,
            OrderService $orderSer,
            ConsumerService $consumerSer,
            CouponService $couponSer,
            ProductService $productSer,
            SupplierService $supplierSer,
            LoginService $loginSer
    ){
        parent::__construct($aes, $orderSer, $consumerSer, $couponSer, $productSer, $supplierSer, $loginSer);
    }
}