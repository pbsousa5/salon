<?php

namespace App\Salon\Services;

use DB;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use App\Salon\Repositories\OrderInfoRepository;
use App\Salon\Repositories\OrderProductRepository;
use App\Salon\OrderProduct;

/**
 * 
 * 
 * @desc 支付异步通知的接口
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月10日
 */
class PayNotifyService
{
    /**
     * 订单数据仓库
     * @var OrderInfoRepository
     */
    protected $infoRe;
    
    /**
     * 订单产品数据仓库
     * @var OrderProductRepository
     */
    protected $orderProductRe;
    
    public function __construct(
            OrderInfoRepository $info,
            OrderProductRepository $product
    ){
        $this->infoRe = $info;
        $this->orderProductRe = $product;
    }
    
    /**
     * 检查订单是否被支付
     * 已支付：true  未支付：false
     * @param string $trade_no 订单编号
     * @return boolean
     */
    public function checkPayStatus($trade_no)
    {
        $model = $this->infoRe->show(['trade_no'=>$trade_no]);
        if ($model->pay_status==1 || $model->order_status!=1) {
            return true;
        }
        
        return false;
    }
    
    /**
     * 支付成功，更新订单状态
     * @param array $inputs 更新的数据
     * @return boolean|integer
     */
    public function updateOrder($trade_no, $inputs)
    {
        DB::beginTransaction();
        $orderInfo = $this->infoRe->update(['trade_no'=>$trade_no], $inputs);
        if (empty($orderInfo)) {
            DB::rollback();
            return null;
        }
        
        // 更新订单产品
        $flag = $this->orderProductRe->batchUpdate(
                ['order_info_id'=>$orderInfo->id],
                ['product_status'=>OrderProduct::PRODUCT_STATUS_CAN_USE]
        );
        if (!$flag) {
            DB::rollback();
            return null;
        }
        
        // 更新用户资金
        
        DB::commit();
        return $orderInfo;
    }
}