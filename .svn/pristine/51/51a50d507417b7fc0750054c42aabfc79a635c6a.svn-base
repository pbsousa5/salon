<?php

namespace App\Salon\Repositories\V2;

use App\Salon\OrderInfo;
use App\Salon\Repositories\OrderInfoRepository as OrderInfoRep;
/**
 *
 *
 *
 * @desc 订单信息数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class OrderInfoRepository extends OrderInfoRep
{
    /**
     *
     * 创建一个订单信息数据仓库实例
     * @param App\Salon\OrderInfo $orderInfo
     * @return void
     */
    public function __construct(OrderInfo $orderInfo)
    {
        $this->model = $orderInfo;
    }

    /**
     * 更新或创建订单信息
     * @param OrderInfo $orderInfo 订单信息model
     * @param array $inputs 更新的数据
     * @return OrderInfo|null
     */
    protected function saveOrderInfo(OrderInfo $orderInfo, array $inputs)
    {
        if (array_key_exists('trade_no', $inputs)) {
            $orderInfo->trade_no = trim($inputs['trade_no']);
        }
        if (array_key_exists('consumer_id', $inputs)) {
            $orderInfo->consumer_id = $inputs['consumer_id'];
        }
        if (array_key_exists('postscript', $inputs)) {
            $orderInfo->postscript = e(trim($inputs['postscript']));
        }
        if (array_key_exists('order_status', $inputs)) {
            $orderInfo->order_status = $inputs['order_status'];
        }
        if (array_key_exists('pay_status', $inputs)) {
            $orderInfo->pay_status = $inputs['followers'];
        }
        if (array_key_exists('review_status', $inputs)) {
            $orderInfo->review_status = $inputs['review_status'];
        }
        if (array_key_exists('consumer_coupon_id', $inputs)) {
            $orderInfo->consumer_coupon_id = $inputs['consumer_coupon_id'];
        }
        if (array_key_exists('coupon_face_fee', $inputs)) {
            $orderInfo->coupon_face_fee = $inputs['coupon_face_fee'];
        }
        if (array_key_exists('bean_amount', $inputs)) {
            $orderInfo->bean_amount = $inputs['bean_amount'];
        }
        if (array_key_exists('bean_fee', $inputs)) {
            $orderInfo->bean_fee = $inputs['bean_fee'];
        }
        if (array_key_exists('pay_fee', $inputs)) {
            $orderinfo->pay_fee = $inputs['pay_fee'];
        }
        if (array_key_exists('total_sign_fee', $inputs)) {
            $orderinfo->total_sign_fee = $inputs['total_sign_fee'];
        }
        if (array_key_exists('original_fee', $inputs)) {
            $orderinfo->original_fee = $inputs['original_fee'];
        }
        if (array_key_exists('advance_time', $inputs)) {
            $orderinfo->advance_time = $inputs['advance_time'];
        }
        if (array_key_exists('pay_name', $inputs)) {
            $orderinfo->pay_name = trim($inputs['pay_name']);
        }
        if (array_key_exists('pay_code', $inputs)) {
            $orderinfo->pay_code = $inputs['pay_code'];
        }
        if (array_key_exists('re_trade_no', $inputs)) {
            $orderinfo->re_trade_no = $inputs['re_trade_no'];
        }
        if (array_key_exists('re_cash_fee', $inputs)) {
            $orderInfo->re_cash_fee = $inputs['re_cash_fee'];
        }
        if (array_key_exists('re_payment_time', $inputs)) {
            $orderInfo->re_payment_time = $inputs['re_payment_time'];
        }
        if (array_key_exists('consumer_name', $inputs)) {
            $orderInfo->consumer_name = e(trim($inputs['consumer_name']));
        }
        if (array_key_exists('consumer_mobile', $inputs)) {
            $orderInfo->consumer_mobile = e(trim($inputs['consumer_mobile']));
        }
        if (array_key_exists('consumer_head', $inputs)) {
            $orderInfo->consumer_head = $inputs['consumer_head'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $orderInfo->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $orderInfo->updated_at = $inputs['updated_at'];
        }
        
        if ($orderInfo->save()) {
            return $orderInfo;
        }
        
        return null;
    }    
}