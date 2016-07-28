<?php

namespace App\Salon\Services;

use App\Salon\Repositories\OrderInfoRepository;
use App\Salon\Repositories\OrderProductRepository;
use App\Salon\Repositories\BackOrderRepository;
use App\Salon\Repositories\BackProductRepository;
use DB;
use App\Salon\OrderInfo;
use App\Salon\BackOrder;
use App\Salon\OrderProduct;

/**
 * 
 * 
 * @desc 退单服务层
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月12日
 */
class BackOrderService
{
    /**
     * 订单信息数据仓库
     * @var OrderInfoRepository
     */
    protected $orderInfoRe;
    
    /**
     * 订单产品数据仓库
     * @var OrderProductRepository
     */
    protected $orderProductRe;
    
    /**
     * 退单信息数据仓库
     * @var BackOrderRepository
     */
    protected $backOrderRe;
    
    /**
     * 退单产品数据仓库
     * @var BackProductRepository
     */
    protected $backProductRe;
    
    public function __construct(
            OrderInfoRepository $orderInfoRe,
            OrderProductRepository $orderProductRe,
            BackOrderRepository $backOrderRe,
            BackProductRepository $backProductRe
    ){
        $this->orderInfoRe = $orderInfoRe;
        $this->orderProductRe = $orderProductRe;
        $this->backOrderRe = $backOrderRe;
        $this->backProductRe = $backProductRe;
    }
    
    /**
     * 添加退单申请
     * 
     * @param OrderInfo $orderInfo 订单信息
     * @param integer $order_product_id 订单产品id
     * @param string $postscript 用户退单留言
     * @return boolean
     */
    public function addBackOrder($orderInfo, $order_product_id, $postscript)
    {
        DB::beginTransaction();
        // 添加退单申请
        $backOrder = $this->createBackOrder($orderInfo, $order_product_id, $postscript);
        if (is_null($backOrder)) {
            return false;
            DB::rollback();
        }
        // 添加退单产品信息
        $backProduct = $this->createBackProduct($orderInfo, $backOrder, $order_product_id);
        if (is_null($backProduct)) {
            return false;
            DB::rollback();
        }
        
        // 更改订单状态
        $flag = $this->changeOrderStatus($orderInfo->id, $order_product_id);
        if (!$flag) {
            return false;
            DB::rollback();
        }
        
        DB::commit();
        return true;
    }
    
    /**
     * 创建退单申请
     * 
     * @param OrderInfo $orderInfo 订单信息的model
     * @param integer $order_product_id 订单产品id
     * @param string $postscript 退单理由
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function createBackOrder(OrderInfo $orderInfo, $order_product_id, $postscript)
    {
        $param['order_info_id'] = $orderInfo->id;
        $param['order_product_id'] = $order_product_id;
        $param['trade_no'] = $orderInfo->trade_no;
        $param['consumer_id'] = $orderInfo->consumer_id;
        $param['postscript'] = $postscript;
        $param['back_fee'] = $orderInfo->pay_fee;
        $param['bean_amount'] = $orderInfo->bean_amount;
        $param['consumer_coupon_id'] = $orderInfo->consumer_coupon_id;
        $param['consumer_name'] = $orderInfo->consumer_name;
        $param['consumer_mobile'] = $orderInfo->consumer_mobile;
        $param['consumer_head'] = $orderInfo->consumer_head;
        
        return $this->backOrderRe->store($param);
    }
    
    /**
     * 创建退单产品信息
     *
     * @param OrderInfo $orderInfo 订单信息的model
     * @param BackOrder $backOrder 退单model
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function createBackProduct(OrderInfo $orderInfo, BackOrder $backOrder, $order_product_id)
    {
        $inputs = [];
        $products = $orderInfo->orderProducts[0];
        if ($products->id != $order_product_id) {
            return null;
        }
        
        $inputs['back_order_id'] = $backOrder->id;
        $inputs['barber_id'] = $products->barber_id;
        $inputs['barber_product_id'] = $products->barber_product_id;
        $inputs['product_id'] = $products->product_id;
        $inputs['supplier_id'] = $products->supplier_id;
        $inputs['category_name'] = $products->category_name;
        $inputs['product_name'] = $products->product_name;
        $inputs['back_fee'] = $products->pay_price;
        $inputs['back_number'] = $products->good_number;
        
        return $this->backProductRe->store($inputs);
    }
    
    /**
     * 将订单状态设置为退单状态
     *
     * @param integer $order_id 需要退单的退单订单id
     * @param integer $order_product_id 订单产品id
     * @return boolean
     */
    protected function changeOrderStatus($order_id, $order_product_id)
    {
        $model = $this->orderProductRe->show(['order_info_id'=>$order_id, 'id'=>$order_product_id]);
        
        $model->product_status = OrderProduct::PRODUCT_STATUS_REFUND;
        return $model->save();
    }
    
    /**
     * 获取退单状态的id
     * @param integer $order_id 订单id
     * @param integer $order_product_id 订单产品id
     */
    public function getBackOrderStatus($order_id, $order_product_id)
    {
        $model = $this->backOrderRe->show(['order_info_id'=>$order_id, 'order_product_id'=>$order_product_id]);
        
        if ($model->back_status == 0) {// 退款中
            return 7;
        } elseif ($model->back_status == 1) {//退款成功
            return 8;
        } else {//退款失败
            return 9;
        }
    }
}