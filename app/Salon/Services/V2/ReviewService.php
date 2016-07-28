<?php
namespace App\Salon\Services\V2;

use App\Salon\Services\ReviewService as ReviewSer;
use App\Salon\Repositories\V2\ReviewRepository;
use App\Salon\Repositories\V2\AvailReviewRepository;
use App\Salon\Repositories\V2\SupplierRepository;
use App\Salon\Repositories\V2\BarberRepository;
use App\Salon\Repositories\V2\OrderInfoRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 *
 *
 * @desc 评论操作服务层
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月10日
 */
class ReviewService extends ReviewSer
{
    /**
     * 评论数据仓库
     * @var ReviewRepository
     */
    protected $reviewRe;
    
    /**
     * 点击有用数据仓库
     * @var AvailReviewRepository
     */
    protected $availRe;
    
    /**
     * The SupplierRepository instance.
     * @var SupplierRepository
     */
    protected $supplierRe;
    
    /**
     * The BarberRepository instance.
     * @var BarberRepository
     */
    protected $barberRe;
    
    /**
     * The OrderInfoRepository instance.
     * @var OrderInfoRepository
     */
    protected $orderInfoRe;
    
    /**
     * 订单服务层
     * @var OrderService
     */
    protected $orderSer;
    
    public function __construct(
            ReviewRepository $reviewRe,
            AvailReviewRepository $availRe,
            SupplierRepository $supplierRe,
            BarberRepository $barberRe,
            OrderInfoRepository $orderInfoRe,
            OrderService $orderSer
    ){
        parent::__construct($reviewRe, $availRe, $supplierRe, $barberRe, $orderInfoRe);
        $this->orderSer = $orderSer;
    }
    
    /**
     * 检查用户对该订单与产品是否有权限进行评论
     * 
     * @param integer $consumer_id 用户id
     * @param integer $order_id 订单id
     * @param integer $order_product_id 订单产品id
     * 
     * @return boolean
     */
    public function checkReviewAuth($consumer_id, $order_id, $order_product_id)
    {
        $orderInfo = $this->orderInfoRe->show(['id' => $order_id, 'consumer_id' => $consumer_id]);
        $status = $this->orderSer->getOrderStatus($orderInfo);
        if ($status != 4) {
            return false;
        }
        if ($this->checkExist($consumer_id, $order_product_id)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * 处理评论信息
     *
     * @param Collection $list 评论的集合
     * @return Collection
     */
    protected function handleData(Collection $list)
    {
        if ($list->has('supplier')) {
            $user_type = 'supplier';
        
            $supplier = $list->get($user_type);
        
            unset($supplier->legal_name);
            unset($supplier->id_num);
            unset($supplier->id_photos);
            unset($supplier->license_photo);
            $supplier->business_time = unserialize($supplier->business_time);
            $supplier->phones = unserialize($supplier->phones);
            $supplier->gallerys = unserialize($supplier->gallerys);
        
            $supplier->cache->reviews = unserialize($supplier->cache->reviews);
            $supplier->cache->hot_product_ids = unserialize($supplier->cache->hot_product_ids);
            $supplier->cache->tags = unserialize($supplier->cache->tags);
            $supplier->cache->count = unserialize($supplier->cache->count);
            $list->put($user_type, $supplier);
        }
        
        if ($list->has('barber')) {
            $user_type = 'barber';
        
            $barber = $list->get($user_type);
        
            $reviews = unserialize($barber->cache->reviews);
            $barber->cache->tags = $reviews['tags'] ? $reviews['tags'] : null;
            unset($reviews['tags']);
            $barber->cache->reviews = $reviews;
            $barber->cache->count = unserialize($barber->cache->count);
            $list->put($user_type, $barber);
        }
        $reviews = $list->get('list');
        foreach ($reviews as $key=>$val) {
            $reviews[$key]->comment_imgs = unserialize($val->comment_imgs);
            //$reviews[$key]->review_tags = unserialize($val->review_tags);
            
            // 此处稍有疑问：如果用户名为空，并且选择了匿名，该如何处理
            if ($reviews[$key]->consumer->nickname == '') {// 用户未设置名字，则显示手机号
                $reviews[$key]->consumer->nickname = substr_replace($reviews[$key]->consumer->mobile, '****', 3, 4);
            } 
            if ($reviews[$key]->is_anonymous == 1) {// 用户选择匿名评价
                $reviews[$key]->consumer->nickname = '平台用户';
            }
            unset($reviews[$key]->review_tags);
        }
        $list->put('list', $reviews);
        
        return $list;
    }
}