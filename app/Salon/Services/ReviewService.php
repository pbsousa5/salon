<?php

namespace App\Salon\Services;

use App\Salon\Supplier;
use App\Salon\SupplierCache;
use App\Salon\Review;
use App\Salon\Repositories\ReviewRepository;
use App\Salon\Repositories\BarberCacheRepository;
use App\Salon\Repositories\SupplierRepository;
use App\Salon\Repositories\BarberRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Salon\Repositories\AvailReviewRepository;
use App\Salon\Repositories\OrderInfoRepository;

/**
 * 
 * 
 * @desc 评论操作
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月28日
 */
class ReviewService
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
    
    public function __construct(
            ReviewRepository $rev,
            AvailReviewRepository $avail,
            SupplierRepository $supplierRe,
            BarberRepository $barberRe,
            OrderInfoRepository $orderInfoRe
    ){
        $this->reviewRe = $rev;
        $this->availRe = $avail;
        $this->supplierRe = $supplierRe;
        $this->barberRe = $barberRe;
        $this->orderInfoRe = $orderInfoRe;
    }
    
    /**
     * 根据用户id与用户评论的产品id，检查用户是否评论过该产品。
     * 
     * @param integer $consumer_id 用户id
     * @param integer $order_product_id 购买的订单中产品id
     * @return boolean
     */
    public function checkExist($consumer_id, $order_product_id)
    {
        $count = $this->reviewRe->countByWhere($consumer_id, $order_product_id);
        if ($count != 0) {
            return true;
        }
        
        return false;
    }
    
    /**
     * 检查用户是否点赞了该评论
     *
     * @param integer $consumer_id 用户id
     * @param integer $review_id 评论id
     * @return boolean
     */
    public function checkClickHeart($consumer_id, $review_id)
    {
        $count = $this->availRe->countByWhere($consumer_id, $review_id);
        if ($count != 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 获取门店或理发师的评论列表，以及对应的评论标签
     * 
     * @param integer $user_id 门店或理发师的id
     * @param string $user_type 用户类型 supplier barber
     * @param integer $size 获取几条数据
     * @return Illuminate\Support\Collection
     */
    public function listReviews($user_id, $user_type, $size=10)
    {
        $list = new Collection();
        // 获取缓存的信息
        if ($user_type == 'supplier') {
            $user = $this->supplierRe->getById($user_id);
            $user->cache;
            $list->put('supplier', $user);
            
            // 获取评论及评论的产品信息
            $reviews = $user->review()->with('barber', 'consumer')->orderBy('created_at', 'desc')->where('is_verify', 1)->paginate($size);
        } elseif ($user_type == 'barber') {
            $user = $this->barberRe->getById($user_id);
            $user->cache;
            $list->put('barber', $user);
            
            // 获取评论及评论的产品信息
            $reviews = $user->review()->with('barber', 'consumer')->orderBy('created_at', 'desc')->where('is_verify', 1)->paginate($size);
        } else {
            return $list;
        }
        
        $data = $reviews->getCollection();
        if ($data->isEmpty()) {
            return collect();
        }
        
        $list->put('list', $data);
        return $this->handleData($list);
    }
    
    /**
     * 处理评论信息
     * 
     * @param Illuminate\Database\Eloquent\Collection $list 评论的集合
     * @return Illuminate\Database\Eloquent\Collection
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
            $tags = unserialize($supplier->cache->tags);
            $tmp = [];
            foreach ($tags as $key => $tag) {
                if (array_key_exists('type', $tag)) {
                    $tmp[$tag['name']] = $tag['count'];
                }
            }
            if (! empty($tmp)) {
                $supplier->cache->tags = $tmp;
            } else {
                $supplier->cache->tags = $tags;
            }
            
            $supplier->cache->count = unserialize($supplier->cache->count);
            $list->put($user_type, $supplier);
        }
        
        if ($list->has('barber')) {
            $user_type = 'barber';
        
            $barber = $list->get($user_type);

            $reviews = unserialize($barber->cache->reviews);
            $tags = $reviews['tags'] ? $reviews['tags'] : null;
            $tmp = [];
            foreach ($tags as $key => $tag) {
                if (array_key_exists('type', $tag)) {
                    $tmp[$tag['name']] = $tag['count'];
                }
            }
            if (! empty($tmp)) {
                $barber->cache->tags = $tmp;
            } else {
                $barber->cache->tags = $tags;
            }
            
            unset($reviews['tags']);
            $barber->cache->reviews = $reviews;
            $barber->cache->count = unserialize($barber->cache->count);
            $list->put($user_type, $barber);
        }
        $reviews = $list->get('list');
        foreach ($reviews as $key=>$val) {
            $reviews[$key]->comment_imgs = unserialize($val->comment_imgs);
            $reviews[$key]->review_tags = unserialize($val->review_tags);
        }
        $list->put('list', $reviews);
        
        return $list;
    }
    
    /**
     * 更新红心数量+1
     * @param integer $consumer_id 用户id
     * @param integer $review_id 评论id
     * @return boolean
     */
    public function clickHeart($consumer_id, $review_id)
    {
        $flag = $this->reviewRe->update(['id'=>$review_id], ['zan_num'=>1]);
        if (! $flag) {
            return false;
        }

        // 记录用户已经操作点击有用
        $this->availRe->store(compact('consumer_id', 'review_id'));

        return $flag;
    }
    
    /**
     * 添加评论，传入评论的数组
     * @param array $review 评论的数组
     * @return boolean
     */
    public function addReview(array $review)
    {
        // 修改该订单状态为已评价
        $this->orderInfoRe->update(['id'=>$review['order_id']], ['review_status'=>1]);
        
        return $this->reviewRe->store($review);
    }
}