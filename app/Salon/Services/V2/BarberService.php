<?php
namespace App\Salon\Services\V2;

use App\Salon\Services\BarberService as BarberSer;
use App\Salon\Repositories\V2\BarberProductRepository;
use App\Salon\Repositories\V2\BarberRepository;
use App\Salon\Repositories\V2\BarberCacheRepository;
use App\Salon\Repositories\V2\ProductRepository;
use App\Salon\Repositories\V2\ConsumeLogRepository;
use App\Libary\Util\Geohash;
use App\Libary\Location\NearbySeller;
use App\Libary\Util\Location;
use App\Salon\Repositories\V2\BarberSampleRepository;
use App\Salon\Barber;
use DB;
use App\Salon\BarberProduct;
use Hamcrest\Arrays\IsArray;
use App\Salon\Supplier;

/**
 *
 *
 * @desc 理发师服务层
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月10日
 */
class BarberService extends BarberSer
{
    /**
     * The BarberProductRepository instance.
     * @var BarberProductRepository
     */
    protected $barberProductRe;
    
    /**
     * The BarberRepository instance.
     * @var BarberRepository
     */
    protected $barberRe;
    
    /**
     * The BarberCacheRepository instance.
     * @var BarberCacheRepository
     */
    protected $barberCacheRe;
    
    /**
     * The FollowService instance.
     * @var FollowService
     */
    protected $followSer;
    
    /**
     * The ProductRepository instance.
     * @var ProductRepository
     */
    protected $productRe;
    
    /**
     * The ConsumeLogRepository instance.
     * @var ConsumeLogRepository
     */
    protected $consumeLogRe;
    
    /**
     * geohash算法对象
     * @var Geohash
     */
    protected $geoHash;
    
    /**
     * The NotifyService instance.
     * @var NotifyService
     */
    protected $notifySer;
    
    /**
     * 理发师作品数据仓库
     * @var BarberSampleRepository
     */
    protected $barberSampleRe;
    
    public function __construct(
            BarberProductRepository $barberProductRe,
            BarberRepository $barberRe,
            Geohash $geoHash,
            FollowService $followSer,
            BarberCacheRepository $barberCacheRe,
            ProductRepository $productRe,
            ConsumeLogRepository $consumeLogRe,
            NotifyService $notifySer,
            BarberSampleRepository $barberSampleRe
    ){
        parent::__construct(
                $barberProductRe,
                $barberRe,
                $geoHash,
                $followSer,
                $barberCacheRe,
                $productRe,
                $consumeLogRe,
                $notifySer,
                $barberSampleRe
        );
    }
    
    /**
     * 获取理发师列表数据，理发师只有列表模式，无地图模式
     *
     * @param array $inputs 获取列表数据的条件
     * @param integer $page 当前请求第几页,默认1
     * @param integer $per_page 本次请求多少条数据,默认20
     * 
     * @return Illuminate\Support\Collection
     */
    public function listBarber($inputs = [], $page = 1, $per_page = 20)
    {
        $extra['consumer_id'] = array_get($inputs, 'consumer_id', 0);
        $addressArr = [];
        $flag = false; //标记是否需要排序
        
        $sortby = $inputs['sortby'];
        
        if ('distance' == $sortby) {
            $near = new NearbySeller('barber', $inputs['longitude'], $inputs['latitude']);
            $addressArr = $near->getFromNearToFar($per_page, $inputs['m_page']);
        
            // 从数组中取出所有的门店id
            $ids = array_key_value($addressArr, '_id');
            $inputs = array_add($inputs, 'ids', $ids);
            $flag = true;
            
            $inputs = array_remove_keys($inputs, ['sortby', 'm_page']);
        } elseif (array_key_exists('longitude', $inputs) && array_key_exists('latitude', $inputs)) {
            if (! empty($inputs['longitude']) && !empty($inputs['latitude'])) {
                $extra['longitude'] = $inputs['longitude'];
                $extra['latitude'] = $inputs['latitude'];
            }
        }
        
        // 移除多余数据
        $inputs = array_remove_keys(
                $inputs,
                ['longitude', 'latitude', 'type', 'kilometer', 'page', 'per_page', 'consumer_id']
        );
        
        $list = $this->barberRe->index($inputs, '', $per_page)->getCollection();
        if ($list->isEmpty()) {
            return collect();
        }
        
        // 处理数据
        foreach ($list as $key => $barber) {
            $this->handleData($barber, $addressArr, $extra);
        
            // 排序需要的字段
            $sortByDistance[$key] = $barber->pitch;
        }
        
        $data = $list->toArray();
        if ($flag) {
            array_multisort($sortByDistance, SORT_ASC, $data);
        }
        
        return collect($data);
    }
    
    /**
     * 处理数据
     *
     * @param mixed $data 需要处理的数据
     * @param array $addrArr 门第的地址列表
     * @param array $extra 其他额外的数据
     * @return mixed
     */
    public function handleData($barber, $addrArr, $extra=[])
    {
        // 如果用户id存在，则需要检查用户是否关注该门店
        if (!empty($extra) && array_key_exists('consumer_id', $extra)) {
            $where = ['consumer_id'=>$extra['consumer_id'], 'user_id'=>$barber->id];
            $barber->watcher = $this->followSer->checkStatus($where, 'barber') ? 1 : 0;
        } else {
            $barber->watcher = 0;
        }
        
        $reviews = unserialize($barber->barber_reviews);
        $barber->tags = $reviews['tags'] ? $reviews['tags'] : null;
        unset($reviews['tags']);
        $barber->barber_reviews = $reviews;
        $barber->barber_count = unserialize($barber->barber_count);
        $barber->barber_age = age($barber->barber_birthday);
        $barber->barber_work_life = age($barber->barber_work_life);
        $barber->barber_status = (int) $barber->barber_status;
        
        if ($barber->barber_gender == 1) {
            $barber->barber_gender = '男';
        } elseif ($barber->barber_gender == 0) {
            $barber->barber_gender = '女';
        } else {
            $barber->barber_gender = '未设置';
        }
        
        // 获取门店信息
        if (! is_null($barber->supplier)) {
            $barber->supplier->business_time = unserialize($barber->supplier->business_time);
            $barber->supplier->phones = unserialize($barber->supplier->phones);
            $barber->supplier->gallerys = unserialize($barber->supplier->gallerys) ? unserialize($barber->supplier->gallerys) : [];
            unset($barber->supplier->legal_name);
            unset($barber->supplier->id_num);
            unset($barber->supplier->id_photos);
            unset($barber->supplier->license_photo);
        }
        // 获取两个产品信息
        $barber->barberProduct = $barber->barberProduct()->where('is_delete', 0)->where('status', 1)->orderBy('sell_price', 'asc')->take(2)->get();
        if (is_null($barber->barberProduct)) {
            $barber->barberProduct = [];
        } else {
            foreach ($barber->barberProduct as $key => $product) {
                $max_price = max($product->sell_price, $product->sign_price);#比价平台售价与签约价，取较大值让用户进行支付
        
                $barber->barberProduct[$key]->sell_price = $max_price;
        
                $barber->barberProduct[$key]->desc_url = config('appinit.apiurl')."products/{$product->id}/intro?type=barber";
                unset($barber->barberProduct[$key]->rich_desc);
            }
        }
        
        // 获取位置信息，距离是m
        if (empty($addrArr)) {
            $barber->pitch = 0;
            
            if (array_key_exists('longitude', $extra) && array_key_exists('latitude', $extra)) {
                $srcLongLat = implode(',', [$extra['longitude'], $extra['latitude']]);
                $destLongLat = implode(',', [$barber->barber_longitude, $barber->barber_latitude]);
                $barber->pitch = Location::getP2PDistance($srcLongLat, $destLongLat);
            }
        } else {
            if (is_string($addrArr)) {
                $destLongLat = implode(',', [$barber->barber_longitude, $barber->barber_latitude]);
                $barber->pitch = Location::getP2PDistance($addrArr, $destLongLat);
            } else {
                foreach ($addrArr as $addr) {
                    if ($addr['_id'] == $barber->id) {
                        $barber->pitch = $addr['dis'];
                        break;
                    }
                }
            }
        }
        
        // 获取是否有未读消息
        $barber->new_notify = $this->notifySer->getUserNotifyNotRead($barber->id, 'barber') ? 1 : 0;
        
        return $barber;
    }
    
    /**
     * 重载方法，此时上传的图片为多张图片，用逗号进行的分隔
     *
     * @param array $inputs 需要保存的数据
     *
     * @return boolean
     */
    public function addSample($inputs)
    {
        $inputs['opus_img'] = explode(',', $inputs['opus_img']);
        
        $sample = $this->barberSampleRe->store($inputs);
        if ($sample === false) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * 删除相册或者相册中的某一张图片
     *
     * @param string $sample_ids 理发师作品的id
     *
     * @return boolean
     */
    public function destroySample($sample_ids, $barber_id, $img_name = '')
    {
        
        $extra['barber_id'] = $barber_id;
        if ($img_name) {
            $extra['img_name'] = explode(',', $img_name);
        }
        
        $ret = $this->barberSampleRe->destroy(explode(',', $sample_ids), $extra);
        if ($ret) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 修改指定美发师、指定的相册的内容(图片或简介)
     *
     * @param integer $barber_id 理发师id
     * @param integer $sample_id 作品id
     * @param array $inputs 需要修改的内容
     * @param string $img_type 修改的图片类型，inside:内容页，cover:封面
     *
     * @return boolean
     */
    public function updateSample($barber_id, $sample_id, $inputs, $img_type = 'inside')
    {
        $where['barber_id'] = $barber_id;
        $where['id'] = $sample_id;
        
        foreach ($inputs as $field => $value) {
            if (! $value) $inputs = array_remove_keys($inputs, [$field]);
        }
        
        if (array_key_exists('opus_img', $inputs)) {
            $ret = $this->barberSampleRe->update($where, $inputs, $img_type);
        } else {
            $ret = $this->barberSampleRe->update($where, $inputs);
        }
        
        if ($ret) return true;
        return false;
    }
    
    /**
     * 如果请求的项目id不存在于理发师项目中，则增加该项目，如果该项目已经存在，则删除该项目
     *
     * @param Barber $barber 理发师model
     * @param array $product_ids 添加的项目数组
     * @return boolean
     */
    public function addProduct(Barber $barber, array $product_ids)
    {
        $barber_status = $barber->barber_status;// 理发师状态
        
        $param = [];
        $productList = $this->productRe->index(['ids'=>$product_ids]);
        // 检查添加的项目是否属于该理发师所属门店
        foreach ($productList as $product) {
            if ($product->supplier_id != $barber->supplier_id) return false;
        }
        
        // 进行批量添加操作
        $deleteIds = [];
        $addParams = [];
        foreach ($productList as $key => $product) {
            if ($this->isExistProduct($barber->id, $product->id)) {// 存在的项目，删除
                array_push($deleteIds, $product->id);
            } else {// 不存在的项目，添加
                $addParams[$key] = [
                        'supplier_id' => $barber->supplier_id,
                        'product_id' => $product->id,
                        'barber_id' => $barber->id,
                        'category_id' => $product->category->id,
                        'product_name' => $product->product_name,
                        'product_desc' => $product->product_desc,
                        'sell_price' => $product->sell_price,
                        'sign_price' => $product->sign_price,
                        'original_price' => $product->original_price,
                        'const_stock' => $product->const_stock,
                        'total_stock' => $product->total_stock,
                        'quota_num' => $product->quota_num,
                        'status' => $product->status,
                        'sold_type' => $product->sold_type,
                        'start_sold_time' => $product->start_sold_time,
                        'rich_desc' => $product->rich_desc,
                        'is_real' => $product->is_real,
                        'is_delete' => $product->is_delete,
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time()),
                ];
                
                // 排序使用
                $sortprice[$key] = $product->sell_price;
            }
        }
        
        
        // 对添加的项目进行升序排列，便于获取最低价
        if (! empty($addParams)) {
            array_multisort($sortprice, SORT_ASC, $addParams);
        }
    
        // 如果是状态为3的理发师，需更新理发师数量，请注意，在门店解绑时，需要减少理发师数量
        if ($barber->barber_status == 3) {
            $supplier = Supplier::where('id', $barber->supplier_id)->first();
            $supplier->staff_count += 1;
        }
    
        DB::beginTransaction();
    
        // 插入理发师项目
        if (! empty($addParams)) {
            $flag = BarberProduct::insert($addParams);
            if (! $flag) {
                DB::rollback();
                return false;
            }
            
            $this->updateLowerPrice($barber->id, $addParams[0]['sell_price']);
        }
        
        if ($barber->barber_status == 3) {
            $flag = $barber->update(['status'=>Barber::BARBER_STATUS_BIND]);
            if (! $flag) {
                DB::rollback();
                return false;
            }
            
            $supplier->save();
        }
        
        if (! empty($deleteIds)) {// 需要删除的项目不为空
            $ret = $this->barberProductRe->destroy([], ['product_id' => $deleteIds, 'barber_id' => $barber->id]);
        }
    
        DB::commit();
        
        //V2版本中，仅在状态为3时，修改mongodb的数据
        if ($barber_status == 3) {
            $this->barberToMongdb($barber, 'insert');
        }
        
        return true;
    }
    
    /**
     * 检查理发师是否有该项目
     *
     * @param integer $barber_id 理发师id
     * @param integer $product_id 项目id
     * 
     * @return boolean
     */
    protected function isExistProduct($barber_id, $product_id)
    {
        $count = $this->barberProductRe->count(compact('barber_id', 'product_id'));
        
        if (0 == $count) return false;// 不存在该项目
        
        return true;
    }
    
    /**
     * 更新理发师的最低价，更新前需检测，是否为最低价
     *
     * @param integer $barber_id 理发师id
     * @param integer $lower_price 最低价格
     *
     * @return
     */
    protected function updateLowerPrice($barber_id, $lower_price)
    {
        // 获取项目中的最低价，存入理发师缓存中
        $this->barberCacheRe->update($barber_id, ['lower_price' => $lower_price]);
    }
}