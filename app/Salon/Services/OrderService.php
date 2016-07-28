<?php

namespace App\Salon\Services;

use DB;
use Illuminate\Support\Arr;
use App\Salon\OrderInfo;
use Cache;
use App\Libary\Util\String;
use App\Events\CouponExpireEvent;
use App\Salon\Repositories\ConsumerCouponRepository;
use App\Salon\ConsumerCoupon;
use App\Events\ConsumerBeanEvent;
use App\Salon\Repositories\OrderInfoRepository;
use App\Salon\Repositories\OrderProductRepository;
use App\Salon\Repositories\ConsumerRepository;
use App\Salon\Repositories\SupplierRepository;
use App\Salon\Repositories\CouponRepository;
use App\Salon\Repositories\ProductRepository;
use App\Salon\BackOrder;
use App\Salon\OrderProduct;
use App\Salon\Repositories\FundRecordRepository;
use App\Salon\Repositories\ConsumeLogRepository;
use App\Events\OrderExpireEvent;
use App\Salon\IncomeCashLog;
use App\Salon\Repositories\BarberProductRepository;
use Illuminate\Support\Str;
use App\Salon\Consumer;
use App\Salon\ProductCategory;
/**
 * 
 * 
 * @desc 订单服务
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月28日
 */
class OrderService
{
    
    /**
     * 订单信息数据仓库
     * @var OrderInfoRepository
     */
    protected $infoRe;
    
    /**
     * 订单产品数据仓库
     * @var OrderProductRepository
     */
    protected $oproductRe;
    
    /**
     * 消费者数据仓库
     * @var ConsumerRepository
     */
    protected $consumerRe;
    
    /**
     * 门店的数据仓库
     * @var SupplierRepository
     */
    protected $supplierRe;
    
    /**
     * 优惠券数据仓库
     * @var ConsumerCouponRepository
     */
    protected $userCouponRe;
    
    /**
     * 优惠券数据仓库
     * @var CouponRepository
     */
    protected $couponRe;
    
    /**
     * 产品数据仓库
     * @var ProductRepository
     */
    protected $productRe;
    
    /**
     * 退单的服务层
     * @var BackOrderService
     */
    protected $backSer;
    
    /**
     * 门店资金数据仓库
     * @var FundRecordRepository
     */
    protected $fundRe;
    
    /**
     * The BarberProductRepository instance.
     * @var BarberProductRepository
     */
    protected $barberProduct;
    
    public function __construct(
            OrderInfoRepository $info,
            OrderProductRepository $pro,
            ConsumerRepository $consumer,
            SupplierRepository $supplier,
            ConsumerCouponRepository $ccoupon,
            CouponRepository $coupon,
            ProductRepository $porduct,
            BackOrderService $backSer,
            FundRecordRepository $fund,
            BarberProductRepository $barberProduct
    ){
        $this->infoRe = $info;
        $this->oproductRe = $pro;
        $this->consumerRe = $consumer;
        $this->supplierRe = $supplier;
        $this->userCouponRe = $ccoupon;
        $this->couponRe = $coupon;
        $this->productRe = $porduct;
        $this->backSer = $backSer;
        $this->fundRe = $fund;
        $this->barberProduct = $barberProduct;
    }
    
    /**
     * 根据用户id与用户评论的产品id及订单id，检查用户是否评论过该产品。
     *
     * @param integer $consumer_id 用户id
     * @param integer $order_id 订单id
     * @param integer $order_product_id 购买的订单中产品id
     * @return boolean
     */
    public function checkExist($consumer_id, $order_id, $order_product_id)
    {
        // 检查是否支付
        $order = $this->infoRe->getById($order_id);
        $product = $this->oproductRe->getById($order_product_id);
        if ($order->consumer_id != $consumer_id) {
            return 1;
        } elseif ($order->pay_status != 1) {
            return 2;
        } elseif ($product->product_status != 2) {
            return 3;
        } elseif ($product->review_status == 1) {
            return 4;
        } else {
            return true;
        }
    }
    // 返回评论相关的错误信息
    public function getReviewErrorTxt($type)
    {
        switch ($type) {
            case 1:
                return '用户只能评论个人的订单';
                break;
            case 2:
                return '订单未支付，不能评论';
                break;
            case 3:
                return '已消费的订单，才能进行评论';
                break;
            case 4:
                return '订单已评论';
                break;
            default :
                return '未知错误';
                break;
        }
    }
    
    /**
     * 检查订单价格
     *
     * @param array 订单中的产品
     * @return array[original_fee, pay_fee]|null
     */
    public function reckonCostPrice($products)
    {
        $pay_fee = 0;#未优惠的售价
        $original_fee = 0;#原价
        $total_sign_fee = 0; #平台该订单应该支付给商家的金额
        
        // 计算产品原始总价与实际价格
        foreach ($products as $key=>$val) {
            #获取产品信息
            if (array_key_exists('product_id', $val) && $val['product_id'] != 0) {
                $product = $this->productRe->show(['id'=>$val['product_id']]);
            } else {
                $product = $this->barberProduct->show(['id'=>$val['barber_product_id']]);
            }
            
            // 获取平台设置的售价与签约价二者中的最大值
            $max_price = max($product->sell_price, $product->sign_price);
            
            $total_sign_fee += $product->sign_price;
            $pay_fee += $max_price;
            $original_fee += $product->original_price;
        }
        
        return compact('original_fee', 'pay_fee', 'total_sign_fee');
    }
    
    /**
     * 计算用户优惠后的价格
     * 
     * @param integer $consumer_id 用户id
     * @param integer $pay_fee 未优惠的价格，单位分
     * @param integer $coupon_fee 优惠券的面值，单位分
     * @param integer $is_user_bean 是否使用用户的积分进行抵扣
     * @return mixed
     */
    public function reckonDiscountPrice($consumer_id, $pay_fee, $coupon_fee, $is_user_bean)
    {
        $consumer = $this->consumerRe->show(['id'=>$consumer_id]);
        $sub_coupon_fee = $pay_fee-$coupon_fee;// 减去美发币后的价格
        if ($is_user_bean==0) {#不使用美发币抵扣
            $consumer->pay_fee = $sub_coupon_fee;
            $consumer->bean_amount = 0;
            $consumer->bean_fee = 0;
            return $consumer;
        }
        
        $fact_bean_count = $consumer->my_bean;# 拥有的没法币总数
        $sign_bean_fee = config('appinit.bean_value'); # 美发币单价
        
        // 获取当前用户能够使用的美发币数量
        $use_bean_count = $sub_coupon_fee % $sign_bean_fee;
        if ($use_bean_count == 0) {#如果余数为0，则需要重新计算可以使用的美发币数量
            $all_bean_fee = $fact_bean_count * $sign_bean_fee;#美发币总价值
            $tmp_count = $sub_coupon_fee - $all_bean_fee;#如果小于0，表示美发币未用完，大于等于0，表示美发币用完
            if ($tmp_count < 0) {
                $use_bean_count = ($all_bean_fee + $tmp_count) / $sign_bean_fee;
            } else {
                $use_bean_count = $fact_bean_count;
            }
        }
        
        // 计算美发币价值
        if ($fact_bean_count >= $use_bean_count) {
            $use_bean_fee = $use_bean_count * $sign_bean_fee;#使用的美发币总价值
        } else {
            $use_bean_fee = $fact_bean_count * $sign_bean_fee;
        }
        
        // 触发更新用户美发币数量
        event(new ConsumerBeanEvent($consumer, -$use_bean_count));
        
        $consumer->pay_fee = $sub_coupon_fee - $use_bean_fee;
        $consumer->bean_amount = $use_bean_count;
        $consumer->bean_fee = $use_bean_fee;
        return $consumer;
    }
    
    /**
     * 添加一个订单
     * 
     * @param array $inputs 用户提交的信息
     * @return Illuminate\Support\Collection
     */
    public function addOrder(array $inputs)
    {
        $consumer = $this->consumerRe->show(['id'=>$inputs['consumer_id']]);
        
        // 生成订单信息,使用事务
        DB::beginTransaction();
        
        $orderInfo = $this->createOrderInfo($inputs);
        if (empty($orderInfo)) {
            return null;
            DB::rollback();
        }
        
        $orderProduct =  $this->createOrderProduct($inputs, $orderInfo);
        if (empty($orderProduct)) {
            return null;
            DB::rollback();
        }
        
        // 更新用户优惠券状态
        if ($inputs['consumer_coupon_id'] != 0) {
            $flag = $this->userCouponRe->update($inputs['consumer_coupon_id'], ['status'=>1]);
            if (!$flag) {
                return null;
                DB::rollback();
            }
            
            // 减少用户优惠券的数量
            $consumer->my_coupon -= 1;
            if (! $consumer->save()) {
                return null;
                DB::rollback();
            }
        }
        
        
        DB::commit();
        
        // 加上产品
        $orderInfo->product = $orderProduct;
        
        return $orderInfo;
    }
    
    /**
     * 添加一个订单信息数据
     *
     * @param array $inputs 用户提交的信息
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function createOrderInfo(array $inputs)
    {
        $parm = [];
        
        $parm['trade_no'] = String::buildTimeString(time(), 'BNS');
        
        $parm['postscript'] = e(trim($inputs['postscript']));
        $parm['advance_time'] = $inputs['advance_time'] ? $inputs['advance_time'] : time();
        
        $parm['consumer_id'] = $inputs['consumer_id'];
        $parm['consumer_name'] = $inputs['consumer_name'] ? $inputs['consumer_name'] : '未设置';
        $parm['consumer_head'] = $inputs['consumer_head'] ? $inputs['consumer_head'] : '';
        $parm['consumer_mobile'] = $inputs['consumer_mobile'];
        
        $parm['consumer_coupon_id'] = $inputs['consumer_coupon_id'];
        $parm['coupon_face_fee'] = $inputs['coupon_face_fee'];
        $parm['bean_amount'] = $inputs['bean_amount'];
        $parm['bean_fee'] = $inputs['bean_fee'];
        $parm['original_fee'] = $inputs['original_fee'];
        $parm['total_sign_fee'] = $inputs['total_sign_fee'];
        $parm['pay_fee'] = $inputs['pay_fee'];
        
        return $this->infoRe->store($parm);
    }
    
    /**
     * 添加订单产品数据
     *
     * @param array $inputs 用户提交的信息
     * @param OrderInfo $orderInfo 订单信息
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function createOrderProduct(array $inputs, OrderInfo $orderInfo)
    {
        foreach ($inputs['products'] as $key=>$val) {
            $parm['order_info_id'] = $orderInfo->id;
            $parm['consumer_id'] = $inputs['consumer_id'];
            $parm['pay_price'] = $inputs['pay_fee']; // 用户实际支付的价格
            $parm['original_price'] = $inputs['original_fee'];
            $parm['product_id'] = $val['product_id'];
            $parm['barber_id'] = $val['barber_id'];
            $parm['barber_product_id'] = $val['barber_product_id'];
            $parm['supplier_id'] = $val['supplier_id'];
            $parm['category_name'] = $val['category_name'];
            $parm['good_number'] = $val['good_number'];
            
            // 获取产品信息
            if (array_key_exists('product_id', $val) && $val['product_id'] != 0) {
                $p = $this->productRe->show(['id'=>$val['product_id']]);
            } else {
                $p = $this->barberProduct->show(['id'=>$val['barber_product_id']]);
            }
            
            $parm['product_name'] = $p->product_name;
            $parm['product_desc'] = $p->product_desc;
            $parm['sign_price'] = $p->sign_price;# 平台应该向门店支付的金额

            $parm['is_action'] = 0;
            $parm['is_real'] = 1;
            $parm['is_back'] = 1;
            $parm['consume_code'] = String::randString(9, 1);
            
            $product = $this->oproductRe->store($parm);
            
            // 更新产品的库存，针对有库存的产品
            if ($p->total_stock == 1) {// 如果库存是1，表示是最后一个产品
                $p->total_stock = -1;
                $p->save();
            } elseif ($product->total_stock > 1) {
                $p->total_stock -= 1;
                $p->save();
            }
        }
        
        return $product;
    }
    
    /**
     * 获取指定用户订单列表
     * 1:取消,2:未支付,3:未消费,4:待评价,5:已评价,6:退款(7:退款中,8:已退款,9:退款失败)
     * @param array $where 查询的条件['consumer_id'=>xx]或者['supplier_id'=>xx]
     * @param integer $order_type 获取的订单类型
     * @param integer $size 获取多少条
     * @param Illuminate\Support\Collection
     */
    public function listOrders($where, $order_type=0, $size=10)
    {
        $list = $this->infoRe->index($where, $order_type, $size)->getCollection();
        if ($list->isEmpty()) {
            return null;
        }
        
        foreach ($list as $key=>$val) {
            $this->handleData($val);
        }

        $tmp = $list->toArray();
        foreach ($tmp as $tk => $tv) {
            if ($tv['order_status'] == -1) {
                unset($tmp[$tk]);
            }
        }
        $tmp = array_values($tmp);
        $list = collect($tmp);
        
        return $list;
    }
    
    /**
     * 处理订单信息数据
     *
     * @param mixed $orderInfo 订单数据
     * @param array
     */
    public function handleData($orderInfo)
    {
        $orderInfo->order_status = $this->getOrderStatus($orderInfo);
        
        if ($orderInfo->order_status==6) {#如果订单时退单，则获取退单状态
            $orderInfo->order_status = $this->backSer->getBackOrderStatus($orderInfo->id, $orderInfo->orderProducts[0]->id);
        }
        
        unset($orderInfo->pay_status);
        unset($orderInfo->review_status);
        unset($orderInfo->consumer_coupon_id);
        unset($orderInfo->re_trade_no);
        unset($orderInfo->re_cash_fee);
        unset($orderInfo->re_payment_time);
        $orderInfo->advance_time = date('Y-m-d H:i:s', $orderInfo->advance_time);
        
        foreach ($orderInfo->orderProducts as $key=>$orderProduct) {
            unset($orderInfo->orderProducts[$key]->order_info_id);
            unset($orderInfo->orderProducts[$key]->consumer_id);
            if ($orderInfo->order_status<3) {
                $orderInfo->orderProducts[$key]->consume_code = '';
            }
            unset($orderInfo->orderProducts[$key]->product_status);
            unset($orderInfo->orderProducts[$key]->created_at);
            unset($orderInfo->orderProducts[$key]->updated_at);
            
            // 获取店家信息
            $supplier = $orderProduct->supplier()->first();
            // 处理门店信息
            unset($supplier->legal_name);
            unset($supplier->id_num);
            unset($supplier->id_photos);
            unset($supplier->license_photo);
            unset($supplier->is_first);
            unset($supplier->created_at);
            unset($supplier->updated_at);
            $supplier->business_time = unserialize($supplier->business_time);
            $supplier->phones = unserialize($supplier->phones);
            $supplier->gallerys = unserialize($supplier->gallerys);
            $orderInfo->orderProducts[$key]->supplier = $supplier;
            
            // 获取门店经纬度信息
            $address = $supplier->address()->where('user_type', 'supplier')->first();
            $supplier->longitude = $address->longitude;
            $supplier->latitude = $address->latitude;
            unset($supplier->id);
            
            $barber = $orderProduct->barber()->first();
            $orderInfo->orderProducts[$key]->barber = $barber;
            if (! is_null($barber)) {
                $orderInfo->orderProducts[$key]->supplier->phones = [$barber->mobile];
            }
        }
        
        return $orderInfo;
    }
    
    /**
     * 获取订单状态
     * 0:订单过期 1:取消,2:未支付,3:未消费,4:待评价,5:已评价,6:退款(7:退款中,8:已退款,9:退款失败)
     * @param integer $order_id 订单id
     * @return integer
     */
    public function getOrderStatus($model)
    {
        if (is_null($model)) {
            return false;
        }
        
        $product = $model->orderProducts[0];
        $start_time = strtotime($model->created_at) + config('appinit.order_expire');
        $now_time = time();
        
        if ($model->order_status==3 || $model->order_status==4) {#订单已经被删除，3：门店删除，4：用户删除
            return -1;
        } elseif ($model->order_status==0) {#该订单已失效(过期)
            return 0;
        } elseif ($model->order_status==2) {#订单被取消
            return 1;
        } elseif ($model->pay_status==0 && $model->order_status==1) {#订单未支付
            // 检查是否过期
            if ($start_time<$now_time) {#订单过期
                event(new OrderExpireEvent($model));
                return 0;
            }
            return 2;
        } elseif ($product->product_status==1) {#订单未消费
            return 3;
        } elseif ($product->product_status==3) {#订单退款中
            return 6;
        } elseif ($model->review_status==0) {#订单待评价
            return 4;
        } elseif ($model->review_status==1) {#订单已评价
            return 5;
        } else {
            return false;
        }
    }
    
    /**
     * 获取订单的状态
     * 
     */
    public function getOrderStatusTxt($type)
    {
        if ($type === false) {
            return '该订单不存在';
        }
        
        switch ($type) {
            case -1:
                return '订单被删除';
                // no break;
            case 0:
                return '订单已过期';
                // no break;
            case 1:
                return '订单被取消';
                // no break;
            case 2:
                return '订单未支付';
                // no break;
            case 3:
                return '订单未消费';
                // no break;
            case 4:
                return '订单待评价';
                // no break;
            case 5:
                return '订单已评价';
                // no break;
            case 6:
                return '订单退款中';
                // no break;
            default:
                return '查找的订单不存在，或其他原因';
                // no break;
        }
    }
    
    /**
     * 获取指定用户指定订单id的订单信息
     * 
     * @param integer $user_id 用户id
     * @param integer $order_id 订单id
     * @param string $user_type 用户类型
     * 
     * @return Illuminate\Support\Collection
     */
    public function getSignInfo($user_id, $order_id, $user_type='consumer')
    {
        $order = $this->infoRe->show(['id'=>$order_id]);
        if (is_null($order)) {
            return null;
        }
        
        if (Str::equals('consumer', $user_type)) {
            if (!empty($user_id) && $order->consumer_id!=$user_id) {
                return null;
            }
        }
        
        return $this->handleData($order);
    }
    
    /**
     * 根据消费码，获取订单中的model
     * 
     * @param string $code 消费码
     * @param integer $supplier_id 门店id
     * 
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getModelByConsumeCode($code, $supplier_id)
    {
        return $this->oproductRe->show(['consume_code'=>$code, 'supplier_id'=>$supplier_id]);
    }
    
    /**
     * 消费产品，更新相关内容
     * 
     * @param OrderProduct $product 订单产品的model
     * @return boolean
     */
    public function useConsumeCode(OrderProduct $product)
    {
        DB::beginTransaction();
        
        // 首先更新订单为已使用状态
        $orderProduct = $this->oproductRe->update($product->id, ['product_status'=>OrderProduct::PRODUCT_STATUS_USED]);
        if (is_null($orderProduct)) {
            DB::rollback();
            return null;
        }
        
        // 更新门店金额情况,此处门店金额应该是签约价而非用户支付价
        $flag = $this->fundRe->update($product->supplier_id, ['sign_fee'=>$product->sign_price, 'pay_fee'=>$product->pay_price]);
        if (!$flag) {
            DB::rollback();
            return null;
        }

        DB::commit();
        return $orderProduct;
    }
    
    /**
     * 限购购买的判断
     *
     * @param OrderProduct $product 订单产品的model
     * @return boolean
     */
    public function limitPurchase($consumer_id, $category_id)
    {
        // 获取分类名称
        $category = ProductCategory::where('id', $category_id)->first();
        $categoryName = $category->name;
        
        // 获取产品
        $orderIds = OrderProduct::where('consumer_id', $consumer_id)->where('category_name', $categoryName)->groupBy('order_info_id')->lists('order_info_id');
        $count = OrderInfo::whereIn('id', $orderIds)->where('order_status', 1)->count();
        if ($count > 0) {
            return false;
        }
        
        return true;
    }
    
    /**
     * 检查门店是否营业中
     * @param integer $supplier_id 门店id
     * @return boolean
     */
    public function isBusiness($supplier_id)
    {
        $supplier = $this->supplierRe->getById($supplier_id);
        $status = $supplier->status;
        if ($status == 1) {
            return true;
        }else {
            return false;
        }
    }
}