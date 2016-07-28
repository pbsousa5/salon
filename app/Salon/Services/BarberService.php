<?php

namespace App\Salon\Services;

use App\Salon\Repositories\BarberProductRepository;
use App\Salon\Repositories\BarberRepository;
use App\Libary\Util\Geohash;
use App\Libary\Util\Location;
use Illuminate\Support\Str;
use App\Salon\Barber;
use DB;
use App\Salon\Repositories\BarberCacheRepository;
use App\Salon\Supplier;
use Illuminate\Database\Eloquent\Collection;
use App\Salon\Repositories\ProductRepository;
use App\Salon\BarberProduct;
use App\Salon\Repositories\ConsumeLogRepository;
use App\Salon\Repositories\BarberSampleRepository;

/**
 * 
 * 
 * @desc 理发师服务层
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月27日
 */
class BarberService
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
            Geohash $g,
            FollowService $followSer,
            BarberCacheRepository $barberCacheRe,
            ProductRepository $productRe,
            ConsumeLogRepository $consumeLogRe,
            NotifyService $notifySer,
            BarberSampleRepository $barberSampleRe
    ){
        $this->barberProductRe = $barberProductRe;
        $this->barberRe = $barberRe;
        $this->geoHash = $g;
        $this->followSer = $followSer;
        $this->barberCacheRe = $barberCacheRe;
        $this->productRe = $productRe;
        $this->consumeLogRe = $consumeLogRe;
        $this->notifySer = $notifySer;
        $this->barberSampleRe = $barberSampleRe;
    }
    
    /**
     * 获取理发师列表数据
     * 
     * @param array $inputs 获取列表数据的条件
     * @param integer $size 每页显示多少条数据
     * @param integer $scope 截取字符串的范围(影响距离)
     */
    public function listBarber($inputs = [], $scope = -1, $size = 10)
    {
        $flag = false;
        if (!empty($inputs['latitude']) && !empty($inputs['longitude']) && $scope != -1) {
            $hash = $this->geoHash->encode($inputs['latitude'], $inputs['longitude']);
            $prefix = substr($hash, 0, $scope);
            //取出相邻八个区域
            $neighbors = $this->geoHash->neighbors($prefix);
            array_push($neighbors, $prefix);
            
            $values = '';
            foreach ($neighbors as $key=>$val) {
                $values .= '\'' . $val . '\'' .',';
            }
            $inputs['neighbors'] = substr($values, 0, -1);
        }
        
        if (Str::equals('distance', $inputs['sortby'])) {
            $flag = true;
        }
        
        $list = $this->barberRe->index($inputs, $scope, $size)->getCollection();
        // 经纬度
        $srcLongLat = implode(',', [$inputs['longitude'], $inputs['latitude']]);
        foreach ($list as $key=>$val) {
            $list[$key] = $this->handleData($val, $srcLongLat, $inputs);
            // 排序需要
            $sortdistance[$key] = $list[$key]->pitch;
        }
        // 检查是否需要排序
        $data = $list->toArray();
        if ($flag) {
            array_multisort($sortdistance, SORT_ASC, $data);
        }
        // 判断如果门店已经下线，该门店的理发师全部不再显示
        foreach ($data as $key => $barber) {
            if ($barber['supplier']['status'] == 0) {
                unset($data[$key]);
            }
        }
        $data = array_values($data);

        return collect($data);
    }
    
    /**
     *
     * 获取单个用户详细信息
     * @param array $condition 查找信息，使用['id'=>1] or ['mobile'=>123]等等
     * @param array $extra 传入的用户经纬度数组[longitude, latitude]
     * @return array|null
     */
    public function getSignleInfo($condition=[], $extra=[])
    {
        $barber = $this->barberRe->show($condition);
        if (is_null($barber)) {
            return null;
        }
        
        $srcLongLat = ',';
        if (! empty($extra) && array_key_exists('longitude', $extra) && array_key_exists('latitude', $extra)) {
            $srcLongLat = implode(',', [$extra['longitude'], $extra['latitude']]);
        }
        
        if (isset($extra['consumer_id'])) {
            $consumer_id = $extra['consumer_id'];
        } else {
            $consumer_id = 0;
        }
        
        $this->handleData($barber, $srcLongLat, ['consumer_id'=>$consumer_id]);
        return $barber;
    }
    
    /**
     * 添加理发师
     * @param array $inputs 添加理发师的数据
     * @return Barber|null
     */
    public function addBarber(array $inputs)
    {
        $inputs = array_add($inputs, 'account', $inputs['mobile']);
        DB::beginTransaction();
        $barber = $this->barberRe->store($inputs);
        if (! $barber->id) {
            DB::rollback();
            return null;
        }
        
        $inputs['barber_id'] = $barber->id;
        $barberCache = $this->barberCacheRe->store($inputs);
        if (! $barberCache->id) {
            DB::rollback();
            return null;
        }
        
        DB::commit();
        $barber = $this->barberRe->show(['id'=>$barber->id]);
        $this->handleData($barber, []);
        return $barber;
    }
    
    /**
     * 处理数据
     * 
     * @param mixed $data 需要处理的数据
     * @param string $srcLongLat 用户的经纬度，用于计算距离
     * @param array $extra 其他额外的数据
     * @return mixed
     */
    public function handleData($data, $srcLongLat, $extra=[])
    {
        // 如果用户id存在，则需要检查用户是否关注该门店
        if (!empty($extra) && array_key_exists('consumer_id', $extra)) {
            $where = ['consumer_id'=>$extra['consumer_id'], 'user_id'=>$data->id];
            $data->watcher = $this->followSer->checkStatus($where, 'barber') ? 1 : 0;
        } else {
            $data->watcher = 0;
        }
        
        $reviews = unserialize($data->barber_reviews);
        $data->barber_tags = $reviews['tags'] ? $reviews['tags'] : null;
        unset($reviews['tags']);
        $data->barber_reviews = $reviews;
        $data->barber_count = unserialize($data->barber_count);
        $data->barber_age = age($data->barber_birthday);
        $data->barber_work_life = age($data->barber_work_life);
        $data->barber_status = (int) $data->barber_status;
        
        if ($data->barber_gender == 1) {
            $data->barber_gender = '男';
        } elseif ($data->barber_gender == 0) {
            $data->barber_gender = '女';
        } else {
            $data->barber_gender = '未设置';
        }
        unset($data->barber_geohash);
        
        // 获取门店信息
        if (! is_null($data->supplier)) {
            $data->supplier->business_time = unserialize($data->supplier->business_time);
            $data->supplier->phones = unserialize($data->supplier->phones);
            $data->supplier->gallerys = unserialize($data->supplier->gallerys) ? unserialize($data->supplier->gallerys) : [];
            unset($data->supplier->legal_name);
            unset($data->supplier->id_num);
            unset($data->supplier->id_photos);
            unset($data->supplier->license_photo);
        }
        
        // 获取两个产品信息
        $data->barberProduct = $data->barberProduct()->where('is_delete', 0)->where('status', 1)->orderBy('sell_price', 'asc')->take(2)->get();
        if (is_null($data->barberProduct)) {
            $data->barberProduct = [];
        } else {
            foreach ($data->barberProduct as $key => $product) {
                $max_price = max($product->sell_price, $product->sign_price);#比价平台售价与签约价，取较大值让用户进行支付
                
                $data->barberProduct[$key]->sell_price = $max_price;
                
                $data->barberProduct[$key]->desc_url = config('appinit.apiurl')."products/{$product->id}/intro?type=barber";
                unset($data->barberProduct[$key]->rich_desc);
            }
        }
        
        if (empty($data->barber_longitude) || empty($data->barber_latitude) || Str::equals(',', $srcLongLat)) {
            // 如果店家为设置距离
            $data->pitch = 0;
        } else {
            $destLongLat = implode(',', [$data->barber_longitude, $data->barber_latitude]);
            $data->pitch = Location::getP2PDistance($srcLongLat, $destLongLat);
        }
        
        $data->new_notify = 0;// 没有新消息
        // 获取是否有未读消息
        $hasNewNotify = $this->notifySer->getUserNotifyNotRead($data->id, 'barber');
        if ($hasNewNotify) {
            $data->new_notify = 1;// 有新消息
        }
        
        return $data;
    }
    
    /**
     * 添加项目到指定的理发师项目列表
     * 
     * @param Barber $barber 理发师model
     * @param array $product_ids 添加的项目数组
     * @return boolean
     */
    public function addProduct(Barber $barber, array $product_ids)
    {
        $param = [];
        $productList = $this->productRe->index(['ids'=>$product_ids], '', 100);
        // 进行批量添加操作
        foreach ($productList as $key => $product) {
            $where = [
                    'supplier_id' => $barber->supplier_id,
                    'barber_id' => $barber->id,
                    'product_name' => $product->product_name,
            ];
            $count = $this->barberProductRe->countByWhere($where);
            if ($count != 0) {
                return false;
            }
            
            $param[$key] = [
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
        
        // 对添加的项目进行升序排列，便于获取最低价
        array_multisort($sortprice, SORT_ASC, $param);
        
        // 更新理发师数量，请注意，在门店解绑时，需要减少理发师数量
        $supplier = Supplier::where('id', $barber->supplier_id)->first();
        $supplier->staff_count += 1;
        
        DB::beginTransaction();
        
        $flag = BarberProduct::insert($param);
        if (! $flag) {
            DB::rollback();
            return false;
        }
        
        // 插入理发师项目
        $flag = $barber->update(['status'=>Barber::BARBER_STATUS_BIND]);
        if (! $flag) {
            DB::rollback();
            return false;
        }
        
        $supplier->save();
        
        $lower_price = $param[0]['sell_price'];
        // 获取项目中的最低价，存入理发师缓存中
        $count = $this->barberCacheRe->update($barber->id, ['lower_price'=>$param[0]['sell_price']]);
        
        DB::commit();
        
        // V1版本中理发师添加时，将其状态初始化为1
        $this->barberToMongdb($barber, 'insert');
        
        return true;
    }
    
    /**
     * 获取门店的所有客户
     *
     * @param integer $barber_id 理发师id
     * @param integer $size 获取多少条
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getCustomerList($barber_id, $size=10)
    {
        $consumer = [];
        $list = $this->consumeLogRe->index(compact('barber_id'), '', $size);
    
        foreach ($list as $key=>$val) {
            if (empty($val->consumer->nickname)) {
                $val->consumer->nickname = '';
            }
            if (empty($val->consumer->head_img)) {
                $val->consumer->head_img = '';
            }
            if (empty($val->consumer->age_tag)) {
                $val->consumer->age_tag = '';
            }
    
            unset($val->consumer->my_bean);
            unset($val->consumer->my_coupon);
            unset($val->consumer->weight);
            unset($val->consumer->invitation_code);
    
            $consumer[$key] = $val->consumer;
        }
    
        return collect($consumer);
    }
    
    /**
     * 保存美发师上传的作品集
     *
     * @param array $inputs 需要保存的数据
     * 
     * @return boolean
     */
    public function addSample($inputs)
    {
        $sample = $this->barberSampleRe->store($inputs);
        if ($sample === false) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * 获取美发师作品相册列表
     *
     * @param integer $id 理发师id
     * @param integer $per_page 每次请求多少条
     *
     * @return Illuminate\Support\Collection
     */
    public function listSample($id, $per_page = 20)
    {
        $list = $this->barberSampleRe->index($id, '', $per_page)->getCollection();
    
        foreach ($list as $sample) {
            $sample->opus_img = unserialize($sample->opus_img);
            if (! is_array($sample->opus_img)) {
                $sample->opus_img = (array) $sample->opus_img;
            }
        }
    
        return $list;
    }
    
    /**
     * 在新增或者修改门店时，向mongodb插入或者更新理发师信息
     * @param Barber $barber 理发师对象
     * @param string $type 操作类型， insert:插入对象 update:更新对象
     *
     * @return ;
     */
    public function barberToMongdb($barber, $type = 'insert')
    {
        $mongodb = DB::connection('mongodb');
        $db = $mongodb->collection('barber');
    
        if ($barber->barber_status == 5) {
            $status = 1;
        } else {
            $status = 0;
        }
        
        $values = [
                'status' => $status,
                'loc' => [
                        'longitude' => (float)$barber->barber_longitude,
                        'latitude' => (float)$barber->barber_latitude,
                ],
                'province' => $barber->barber_province,
                'city' => $barber->barber_city,
                'district' => $barber->barber_district,
                'detail' => $barber->barber_detail,
        ];
    
        if ($type == 'insert') {
            $barber_id = (int)$barber->id;
            $values = array_add($values, '_id', $barber_id);
            $db->insert($values);
        } elseif ($type == 'update') {
            $db->where('_id', $barber->id)->update($values);
        }
    }
}