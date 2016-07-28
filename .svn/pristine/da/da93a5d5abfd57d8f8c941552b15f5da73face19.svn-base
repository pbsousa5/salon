<?php
namespace App\Salon\Services\V2;

use App\Salon\Services\SupplierService as SupplierSer;
use Illuminate\Support\Str;
use App\Libary\Location\NearbySeller;
use App\Salon\Repositories\V2\SupplierRepository;
use App\Libary\Util\Geohash;
use App\Salon\Repositories\V2\ConsumeLogRepository;
use App\Salon\Repositories\V2\FundRecordRepository;
use App\Salon\Repositories\V2\ReviewRepository;
use App\Salon\Repositories\V2\BarberRepository;
use App\Salon\Repositories\V2\WithdrawCashLogRepository;
use App\Salon\Barber;
use App\Salon\Supplier;
use Cache;
use App\Libary\Util\Location;
use DB;

/**
 * 
 * 
 * @desc 店家服务层
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月10日
 */
class SupplierService extends SupplierSer
{
    
    /**
     * 门店的数据仓库
     * @var SupplierRepository
     */
    protected $supplierRe;
    
    /**
     * 关注服务层
     * @var FollowService
     */
    protected $followSer;
    
    /**
     * geohash算法对象
     * @var App\Libary\Util\Geohash
     */
    protected $g;
    
    /**
     * 消费关系数据仓库
     * @var ConsumeLogRepository
     */
    protected $consumeLogRe;
    
    /**
     * 资金数据仓库
     * @var FundRecordRepository
     */
    protected $fundRe;
    
    /**
     * 评论数据仓库
     * @var ReviewRepository
     */
    protected $reviewRe;
    
    /**
     * The BarberRepository instance.
     * @var BarberRepository
     */
    protected $barberRe;
    
    /**
     * The BarberService instance.
     * @var BarberService
     */
    protected $barberSer;
    
    /**
     * The NotifyService instance.
     * @var NotifyService
     */
    protected $notifySer;
    
    /**
     * The WithdrawCashLogRepository instance.
     * @var WithdrawCashLogRepository
     */
    protected $withdrawCashLogRe;
    
    public function __construct(
            SupplierRepository $supplierRe,
            FollowService $followSer,
            Geohash $geoHash,
            ConsumeLogRepository $consumeLogRe,
            FundRecordRepository $fundRecordRe,
            ReviewRepository $reviewRe,
            BarberRepository $barberRe,
            BarberService $barberSer,
            NotifyService $notifySer,
            WithdrawCashLogRepository $withdrawCashLogRe
    ){
        parent::__construct(
                $supplierRe,
                $followSer,
                $geoHash,
                $consumeLogRe,
                $fundRecordRe,
                $reviewRe,
                $barberRe,
                $barberSer,
                $notifySer,
                $withdrawCashLogRe
        );
    }
    
    /**
     * 门店列表，目前支持按照距离排序，按照评分排序，按照价格排序
     * 
     * @param array $inputs 获取列表数据的条件
     * @param integer $page 当前请求第几页,默认1
     * @param integer $per_page 本次请求多少条数据,默认20
     * 
     * @return Illuminate\Support\Collection
     */
    public function listSupplier($inputs = [], $page = 1, $per_page = 20)
    {
        $extra['consumer_id'] = array_get($inputs, 'consumer_id', 0);
        $addressArr = [];
        $flag = false; //标记是否需要排序
        
        // 地图模式
        if (Str::equals('map', $inputs['type'])) {
            if (empty($inputs['longitude']) || empty($inputs['latitude'])) return null;
            
            $near = new NearbySeller('supplier', $inputs['longitude'], $inputs['latitude']);
            $addressArr = $near->getRadiusBydisorder($inputs['kilometer']);// 从mongodb中获取到了指定范围的门店信息
            
            // 从数组中取出所有的门店id
            $ids = array_key_value($addressArr, '_id');
            $inputs = array_add($inputs, 'ids', $ids);
            
            // 移除多余数据
            $inputs = array_remove_keys(
                    $inputs,
                    ['longitude', 'latitude', 'type', 'kilometer', 'page', 'per_page', 'sortby', 'consumer_id']
            );
            
            $per_page = 100;
        // 列表模式
        } else {
            $sortby = $inputs['sortby'];
            
            if ('distance' == $sortby && 'search' != $inputs['type']) {
                $near = new NearbySeller('supplier', $inputs['longitude'], $inputs['latitude']);
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
        }
        
        $list = $this->supplierRe->index($inputs, '', $per_page)->getCollection();
        if ($list->isEmpty()) {
            return collect();
        }
        
        // 处理数据
        foreach ($list as $key => $supplier) {
            $this->handleData($supplier, $addressArr, $extra);
            
            // 排序需要的字段
            $sortByDistance[$key] = $supplier->pitch;
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
     * @param mixed $supplier 门店数据
     * @param array $addrArr 门店的地址列表
     * @param array $extra 其他额外的数据
     * @return
     */
    public function handleData($supplier, $addrArr, $extra=[])
    {
        // 获取该门店是否被用户关注
        if (array_key_exists('consumer_id', $extra)) {
            $where = ['consumer_id' => $extra['consumer_id'], 'user_id' => $supplier->id];
            $supplier->watcher = $this->followSer->checkStatus($where, 'supplier') ? 1 : 0;
        } else {
            $supplier->watcher = 0;
        }
        
        // 获取该门店是否有未提现的申请
        $count = $this->withdrawCashLogRe->countApplyInfo($supplier->id, ['is_verify'=>0]);
        $supplier->hasWithdraw = $count ? 1 : 0;// 1表示有提现，0：表示没有提现
        
        $supplier->business_time = unserialize($supplier->business_time);
        $supplier->phones = unserialize($supplier->phones);
        $supplier->gallerys = unserialize($supplier->gallerys);
        $supplier->reviews = unserialize($supplier->reviews);
        $supplier->tags = unserialize($supplier->tags);
        $supplier->hot_product_ids = unserialize($supplier->hot_product_ids);
        $supplier->count = unserialize($supplier->count);
        
        // 获取绑定的理发师的头像
        $supplier->barber = $supplier->barber()->where('status', Barber::BARBER_STATUS_BIND)->take(2)->lists('head_img');
        // 获取最低价格的产品
        $supplier->products = $supplier->products()->where('is_delete', 0)->where('status', 1)->orderBy('sell_price', 'asc')->take(2)->get();
        foreach ($supplier->products as $key => $product) {
            $max_price = max($product->sell_price, $product->sign_price);#比价平台售价与签约价，取较大值让用户进行支付
        
            $supplier->products[$key]->sell_price = $max_price;
            $supplier->products[$key]->url = config('appinit.apiurl')."products/{$product->id}/intro?type=supplier";
            unset($supplier->products[$key]->rich_desc);
            unset($supplier->products[$key]->sign_price);
        }
        
        // 获取位置信息，距离是m
        if (empty($addrArr)) {
            $supplier->pitch = 0;
            
            if (array_key_exists('longitude', $extra) && array_key_exists('latitude', $extra)) {
                $srcLongLat = implode(',', [$extra['longitude'], $extra['latitude']]);
                $destLongLat = implode(',', [$supplier->longitude, $supplier->latitude]);
                $supplier->pitch = Location::getP2PDistance($srcLongLat, $destLongLat);
            }
        } else {
            foreach ($addrArr as $addr) {
                if ($addr['_id'] == $supplier->id) {
                    $supplier->pitch = $addr['dis'];
                    $supplier->longitude = $addr['loc']['longitude'];
                    $supplier->latitude = $addr['loc']['latitude'];
                    break;
                }
            }
        }
        
        // 获取是否有未读消息
        $supplier->new_notify = $this->notifySer->getUserNotifyNotRead($supplier->id, 'supplier') ? 1 : 0;
        
        //$this->setSupplierToCache($supplier);
    }
    
    /**
     * 将门店数据写入缓存中
     * 
     * @param Supplier $supplier 门店model
     * 
     * @return
     */
    protected function setSupplierToCache($supplier)
    {
        // 存入缓存
        $cacheValue = [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'mobile' => $supplier->mobile,
                'token' => '',
                'longitude' => $supplier->longitude,
                'latitude' => $supplier->latitude,
                'channel_id' => '',
                'source' => '',
        ];
        $cacehKey = 'supplier'.$supplier->id;
        if (!Cache::has($cacehKey)) {
            Cache::add($cacehKey, $cacheValue, config('appinit.expire'));
        }
    }
    
    /**
     *
     * 获取单个用户详细信息
     * @param array $condition 查找信息，使用['id'=>1] or ['mobile'=>123]等等
     * @param array $extra 传入的用户经纬度数组[longitude, latitude]
     * @return array|null
     */
    public function getSingleInfo($condition=[], $extra=[])
    {
        $supplier = $this->supplierRe->show($condition);
        if (is_null($supplier)) {
            return null;
        }
        
        if (array_key_exists('consumer_id', $extra) && $extra['consumer_id'] == 0) {
            $extra = array_remove_keys($extra, ['consumer_id']);
        }
        
        $extra = array_add($extra, 'longitude', '');
        $extra = array_add($extra, 'latitude', '');
        if (! $extra['longitude'] || ! $extra['latitude']) {
            $extra = array_remove_keys($extra, ['longitude']);
            $extra = array_remove_keys($extra, ['latitude']);
        }
    
        $this->handleData($supplier, '', $extra);
        
        return $supplier;
    }
    
    /**
     *
     * 修改消费者基本信息
     * @param array $where 更新的条件
     * @param array $inputs 修改门店数据
     * @return App\Salon\Consumer|null
     */
    public function modifySupplier($where, $inputs)
    {
        $supplier = $this->supplierRe->update($where, $inputs);
        if (is_null($supplier)) {
            return null;
        }
        
        // 更新mongodb的数据
        $this->supplierToMongdb($supplier, $supplier->address, 'update');
        
        return $supplier;
    }
    
    /**
     * 在新增或者修改门店时，向mongodb插入或者更新门店信息
     * @param Supplier $supplier 门店对象
     * @param string $type 操作类型， insert:插入对象 update:更新对象
     * @param Address $address 地址
     *
     * @return ;
     */
    protected function supplierToMongdb($supplier, $address, $type = 'insert')
    {
        $mongodb = DB::connection('mongodb');
        $db = $mongodb->collection('supplier');
    
        $values = [
                'status' => (int)$supplier->status,
                'loc' => [
                        'longitude' => (float)$address->longitude,
                        'latitude' => (float)$address->latitude,
                ],
                'province' => $address->province,
                'city' => $address->city,
                'district' => $address->district,
                'detail' => $address->detail,
        ];
    
        if ($type == 'insert') {
            $values = array_add($values, '_id', $supplier->id);
            $db->insert($values);
        } elseif ($type == 'update') {
            $db->where('_id', $supplier->id)->update($values);
        }
    }
}