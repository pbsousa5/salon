<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V1\BackOrderController as BackOrderCon;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Salon\Services\V2\OrderService;
use App\Salon\Services\V2\BackOrderService;
use App\Salon\Services\V2\ConsumerService;
use App\Salon\Services\V2\LoginService;

class BackOrderController extends BackOrderCon
{
    /**
     * 订单服务层
     * @var OrderService
     */
    protected $orderSer;
    
    /**
     * 退单服务层
     * @var BackOrderService
     */
    protected $backSer;
    
    /**
     * 消费者服务层
     * @var ConsumerService
     */
    protected $consumerSer;
    
    /**
     * The LoginService instance.
     * @var LoginService
     */
    protected $loginSer;
    
    public function __construct(
            EncrypterInterface $aes,
            OrderService $orderSer,
            BackOrderService $backSer,
            ConsumerService $consumerSer,
            LoginService $loginSer
    ){
        parent::__construct($aes, $orderSer, $backSer, $consumerSer, $loginSer);
    }
}