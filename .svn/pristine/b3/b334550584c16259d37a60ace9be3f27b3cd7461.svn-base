<?php

namespace App\Salon\Services;

use App\Events\CouponExpireEvent;
use App\Salon\Coupon;
use App\Salon\ConsumerCoupon;
use App\Salon\Repositories\CouponRepository;
use App\Salon\Repositories\ConsumerCouponRepository;
use App\Salon\Consumer;
use App\Salon\Repositories\V2\BarberProductRepository;

/**
 * 
 * 
 * @desc 优惠券操作
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月28日
 */
class CouponService
{
    /**
     * 优惠券的数据仓库
     * @var CouponRepository
     */
    protected $couponRe;
    
    /**
     * The ConsumerCouponRepository instance.
     * @var ConsumerCouponRepository
     */
    protected $consumerCouponRe;
    
    /**
     * 理发师数据仓库
     * @var BarberProductRepository
     */
    protected $barberProductRe;
    
    public function __construct(
            CouponRepository $couponRe,
            ConsumerCouponRepository $consumerCouponRe,
            BarberProductRepository $barberProductRe
    ){
        $this->couponRe = $couponRe;
        $this->consumerCouponRe = $consumerCouponRe;
        $this->barberProductRe = $barberProductRe;
    }
    
    /**
     * 获取优惠券列表,只返回未过期的数据
     * 
     * @param integer $consumer_id 消费者id
     * @param integer $size 获取多少条
     */
    public function listCoupons($consumer_id, $size=10)
    {
        // 将所有已经过期的优惠券设置为过期
        $nowTime = time();
        $flag = ConsumerCoupon::where('consumer_id', $consumer_id)
                                ->where('deadline', '<', $nowTime)
                                ->where('status', '<>', ConsumerCoupon::COUPON_STATUS_EXPIRE)
                                ->update(['status'=>ConsumerCoupon::COUPON_STATUS_EXPIRE]);
        // 更新用户的优惠券数量
        if ($flag > 0) {
            $consumer = Consumer::where('id', $consumer_id)->first();
            $consumer->my_coupon -= $flag;
            $consumer->save();
        }
        
        
        $list = $this->consumerCouponRe->index(compact('consumer_id'), '', $size);
        if ($list->isEmpty()) {
            return collect();
        }
        
        foreach ($list as $key=>$val) {
            //unset($list[$key]->coupon->id);
            unset($list[$key]->coupon_id);
            unset($list[$key]->consumer_id);
            $list[$key]->deadline = date('Y-m-d', $val->deadline);
            $list[$key]->coupon->configs = empty(unserialize($val->coupon->configs)) ? null : unserialize($val->coupon->configs);
        }
        return $list->getCollection();
    }
    
    /**
     * 检查用户是否获取过相同的消费券，并且未过期
     *
     * @param integer $consumer_id 消费者id
     * @param integer $coupon_id 优惠券的id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function checkSameCoupon($consumer_id, $coupon_id)
    {
        $count = $this->consumerCouponRe->countByWhere($consumer_id, $coupon_id);
        if ($count != 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 添加优惠券
     * 
     * @param integer $consumer_id 消费者id
     * @param integer $coupon_id 优惠券的id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function addCoupon($consumer_id, $coupon_id)
    {
        // 根据优惠券id，获取优惠券信息
        $coupon = $this->couponRe->getById($coupon_id);
        
        // 构建用户优惠券信息
        $data['consumer_id'] = $consumer_id;
        $data['coupon_id'] = $coupon_id;
        if ($coupon->valid_term == 0) {
            $data['deadline'] = time() + config('appinit.coupon_expire');
        } else {
            $data['deadline'] = $coupon->valid_term;
        }
        
        $flag = $this->consumerCouponRe->store($data);
        if ($flag) {
            unset($coupon->id);
            return $coupon;
        }
        
        return null;
    }
    
    /**
     * 检查优惠券使用权限
     *
     * @param integer $pay_fee 用户支付的总金额(未优惠) 单位为分
     * @param Coupon $coupon 优惠券model
     * @return integer
     */
    public function checkCouponAuth($pay_fee, Coupon $coupon)
    {
        if ($pay_fee < $coupon->full_cat) {#用户是否达到满减的标准
            return 1;
        } elseif ($pay_fee < $coupon->face_fee) {#如果付款金额小于优惠券面额，则错误
            return 2;
        }
        
        return true;
    }
    
    /**
     * 获取优惠券使用失败的信息
     *
     * @param integer $type 失败类型
     * @return integer
     */
    public function getCouponFaildText($type)
    {
        switch ($type) {
            case 1:
                return '未达到满减金额';
                //no break;
            case 2:
                return '该产品付款金额小于优惠券面额，禁止使用';
                //no break;
            default:
                return '不能识别的原因';
                //no break;
        }
    }
    
    /**
     * 获取单张优惠券信息
     *
     * @param integer $consumer_coupon_id 用户优惠券id
     * @param array $products 订单产品信息
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getSignCoupon($consumer_coupon_id, $products)
    {
        $model = $this->consumerCouponRe->show($consumer_coupon_id);
        if (is_null($model)) {
            return -1;
        }
        //一对多关联
        $coupon = $model->coupon;
        $configs = unserialize($coupon->configs);
        
        if (array_key_exists('pids', $configs)) {
            if ($products[0]['product_id'] != 0 && $products[0]['barber_product_id'] == 0) {
                if (in_array($products[0]['product_id'], $configs['pids'])) {
                    return $model;
                }else {
                    return 3;
                }
            }
            
            if ($products[0]['product_id'] == 0 && $products[0]['barber_product_id'] != 0) {
                $barberProduct = $this->barberProductRe->show(['id' => $products[0]['barber_product_id']]);
                $barberProductId = $barberProduct->product_id;
                if (in_array($barberProductId, $configs['pids'])) {
                    return $model;
                }else {
                    return 4;
                }
            }
        }
        
        if (array_key_exists('sids', $configs)) {
            if ($products[0]['supplier_id'] != 0 && $products[0]['barber_product_id'] ==0) {
                if (in_array($products[0]['supplier_id'], $configs['sids'])) {
                    return $model;
                }else {
                    return 5;
                }
            }
        }
        
        $now_time = time();
        if ($model->status==ConsumerCoupon::COUPON_STATUS_NOT_USE && $model->deadline>$now_time) {#未使用且未过期
            return $model;
        } elseif ($model->status==ConsumerCoupon::COUPON_STATUS_USEED) {#已使用
            return 2;
        } elseif ($model->status==ConsumerCoupon::COUPON_STATUS_NOT_USE && $model->deadline<$now_time) {#已过期
            event(new CouponExpireEvent($model));// 触发优惠券过期事件
            return 1;
        } elseif ($model->status==ConsumerCoupon::COUPON_STATUS_EXPIRE) {#已过期
            return 1;
        } else {
            return -1;
        }
    }
    
    /**
     * 获取优惠券的状态文本信息
     *
     */
    public function getCouponStatusText($type)
    {
        switch ($type) {
            case 1:
                return '优惠券已过期';
                // no break;
            case 2:
                return '优惠券已被使用';
                // no break;
            case 3:
                return '此优惠券本产品无法使用';
                // no break;
            case 4:
                return '此优惠券本产品无法使用';
                // no break;
            case 5:
                return '此优惠券本门店无法使用';
                // no break;
            default:
                return '优惠券信息不存在或其他原因';
                break;
        }
    }
}