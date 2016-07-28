<?php

namespace App\Salon\Services;

use App\Libary\Pay\Wxpay\Lib\WxPayNotify;
use App\Libary\Pay\Wxpay\Lib\Data\WxPayUnifiedOrder;
use App\Libary\Util\String;
use App\Libary\Pay\Wxpay\Lib\WxPayApi;
use App\Libary\Pay\Wxpay\Util\Log\Log;
use App\Libary\Pay\Wxpay\Lib\Data\WxPayOrderQuery;
use App\Events\NotifyPayOrderEvent;
use App\Events\OrderPushEvent;

/**
 * 
 * 
 * @desc 微信支付相关的服务层
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月11日
 */
class WxpayService extends WxPayNotify
{
    
    /**
     * 支付的服务层
     * @var PayNotifyService
     */
    protected $paySer;
    
    public function __construct(PayNotifyService $paySer)
    {
        $this->paySer = $paySer;
    }
    /**
     * 统一下单接口
     * 
     * @param array $param 订单需要的参数
     */
    public function unifiedorder(array $param)
    {
        //统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetNonce_str(String::randString(6, 2));// 设置随机字符串
        $input->SetBody($param['body']);// 设置商品描述
        $input->SetDetail($param['detail']);// 设置商品详情
        $input->SetAttach($param['attach']);// 设置请求来源(ios android)，用于消息推送
        $input->SetOut_trade_no($param['trade_no']);// 设置服务端生成的订单号
        $input->SetTotal_fee($param['total_fee']);// 设置支付金额
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + config('appinit.order_expire')));
        $input->SetNotify_url(config('wxpay.notify_url'));// 设置回调接口
        $input->SetTrade_type('APP');// 设置支付类型
        $result = WxPayApi::unifiedOrder($input);
        return $result;
    }
    
    /**
     * 查询订单是否存在
     * @param string $transaction_id 微信支付订单号
     * @return boolean
     */
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        if(
                array_key_exists("return_code", $result) &&
                array_key_exists("result_code", $result) &&
                $result["return_code"] == "SUCCESS" &&
                $result["result_code"] == "SUCCESS"
        ){
            return true;
        }
        
        return false;
    }
    
    /**
     * 重新回调函数
     * @see \App\Libary\Pay\Wxpay\Lib\WxPayNotify::NotifyProcess()
     */
    public function NotifyProcess($data, &$msg)
    {
        $notfiyOutput = array();
        
        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }
        
        // 检查是否已回调过了
        $flag = $this->paySer->checkPayStatus($data['out_trade_no']);
        if ($flag) {
            return true;
        }
        
        // 获取数据，更新订单状态
        $parm['re_trade_no'] = $data['transaction_id'];//微信交易号
        $parm['re_cash_fee'] = $data['total_fee'];//支付的总金额
        $parm['re_payment_time'] = date('Y-m-d H:i:s',strtotime($data['time_end']));//支付时间
        $parm['pay_status'] = 1;
        $parm['pay_code'] = 'WEIXIN_DEBIT';
        $parm['pay_name'] = '微信支付';
        
        $orderInfo = $this->paySer->updateOrder($data['out_trade_no'], $parm);
        if (! is_null($orderInfo)) {
            // 触发订单支付成功的事件
            event(new NotifyPayOrderEvent($orderInfo));
            event(new OrderPushEvent($orderInfo));
            
            return true;
        } else {
            return false;
        }
    }
}