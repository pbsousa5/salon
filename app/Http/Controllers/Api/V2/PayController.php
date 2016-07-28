<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V1\PayController as PayCon;
use App\Libary\Contracts\Encryption\EncrypterInterface;
use App\Libary\Pay\Alipay\Lib\AlipayNotify;
use App\Salon\Services\V2\WxpayService;
use App\Salon\Services\V2\PayNotifyService;
use App\Salon\Services\V2\OrderService;

class PayController extends PayCon
{
    /**
     * 微信异步通知
     * @var WxpayService
     */
    protected $wxpaySer;
    
    /**
     * 支付宝异步通知
     * @var AlipayNotify
     */
    protected $alipaySer;
    
    /**
     * 支付的服务层
     * @var PayNotifyService
     */
    protected $paySer;
    
    /**
     * 订单服务层
     * @var OrderService
     */
    protected $orderSer;
    
    public function __construct(
            EncrypterInterface $aes,
            AlipayNotify $alipaySer,
            WxpayService $wxpaySer,
            PayNotifyService $paySer,
            OrderService $orderSer
    ){
        parent::__construct($aes, $alipaySer, $wxpaySer, $paySer, $orderSer);
    }
}