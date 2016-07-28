<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Salon\OrderInfo;

class SupplierIncomeEvent extends Event
{
    use SerializesModels;
    
    /**
     * 订单信息
     * @var OrderProduct
     */
    public $orderProduct;
    
    /**
     * 支付的方式
     * @var string
     */
    public $payType;
    
    /**
     * 订单id
     * @var integer
     */
    public $orderNo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order_id, $pay_type)
    {
        // 根据order_id获取门店id(目前仅需支持一个门店一个产品)
        $orderInfo = OrderInfo::find($order_id);
        $this->orderProduct = $orderInfo->orderProducts;
        $this->payType = $pay_type;
        $this->orderNo = $orderInfo->trade_no;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
