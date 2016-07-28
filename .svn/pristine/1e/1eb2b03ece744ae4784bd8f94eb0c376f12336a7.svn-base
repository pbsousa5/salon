<?php

namespace App\Salon\Repositories;

use App\Salon\Barber;
use Illuminate\Support\Str;
use App\Salon\Contracts\Repositories\BarberRepositoryInterface;

/**
 * 
 * 
 * @desc 理发师数据仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月27日
 */
class BarberRepository extends BaseRepository implements BarberRepositoryInterface
{
    /**
     *
     * 创建一个理发师数据仓库实例
     * @param Barber $barber
     * @return void
     */
    public function __construct(Barber $barber)
    {
        $this->model = $barber;
    }
    
    /**
     * 更新或创建理发师
     * 
     */
    protected function saveBarber(Barber $barber, array $inputs)
    {
        if (array_key_exists('mobile', $inputs)) {
            $barber->account = trim($inputs['mobile']);
            $barber->mobile = trim($inputs['mobile']);
        }
        if (array_key_exists('password', $inputs)) {
            $barber->password = bcrypt($inputs['password']);
        }
        if (array_key_exists('nickname', $inputs)) {
            $barber->nickname = e(trim($inputs['nickname']));
        }
        if (array_key_exists('realname', $inputs)) {
            $barber->realname = e(trim($inputs['realname']));
        }
        if (array_key_exists('head_img', $inputs)) {
            $barber->head_img = e(trim($inputs['head_img']));
        }
        if (array_key_exists('gender', $inputs)) {
            $barber->gender = $inputs['gender'];
        }
        if (array_key_exists('email', $inputs)) {
            $barber->email = $inputs['email'];
        }
        if (array_key_exists('supplier_id', $inputs)) {
            $barber->supplier_id = $inputs['supplier_id'];
        }
        if (array_key_exists('work_life', $inputs)) {
            $barber->work_life = $inputs['work_life'];
        }
        if (array_key_exists('descript', $inputs)) {
            $barber->descript = e(trim($inputs['descript']));
        }
        if (array_key_exists('geohash', $inputs)) {
            $barber->geohash = trim($inputs['geohash']);
        }
        if (array_key_exists('longitude', $inputs)) {
            $barber->longitude = $inputs['longitude'];
        }
        if (array_key_exists('latitude', $inputs)) {
            $barber->latitude = $inputs['latitude'];
        }
        if (array_key_exists('province', $inputs)) {
            $barber->province = e(trim($inputs['province']));
        }
        if (array_key_exists('city', $inputs)) {
            $barber->city = e(trim($inputs['city']));
        }
        if (array_key_exists('district', $inputs)) {
            $barber->district = e(trim($inputs['district']));
        }
        if (array_key_exists('detail', $inputs)) {
            $barber->detail = e(trim($inputs['detail']));
        }
        if (array_key_exists('status', $inputs)) {
            $barber->status = $inputs['status'];
        }
        if (array_key_exists('birthday', $inputs)) {
            $barber->birthday = $inputs['birthday'];
        }
        
        if ($barber->save()) {
            return $barber;
        }
        
        return null;
    }
    
    /**
     * 根据传入的条件，获取对象
     * 
     * @param array $credentials 查询条件
     * @param Barber|null
     */
    public function getByCredentials(array $credentials = [])
    {
        $query = $this->createModel()->newQuery();
        
        foreach ($credentials as $key => $value) {
            if (!Str::contains($key, 'password')) {
                $query->where($key, $value);
            }
        }

        return $query->leftJoin('barber_caches', 'barbers.id', '=', 'barber_caches.barber_id')
                        ->select(
                            'barbers.id as id',
                            'barbers.status as barber_status',
                            'barbers.password as password',
                            'barbers.mobile as barber_mobile',
                            'barbers.supplier_id',
                            'barbers.nickname as barber_nickname',
                            'barbers.title as barber_title',
                            'barbers.head_img as barber_head_img',
                            'barbers.gender as barber_gender',
                            'barbers.birthday as barber_birthday',
                            'barbers.email as barber_email',
                            'barbers.work_life as barber_work_life',
                            'barbers.descript as barber_descript',
                            'barbers.geohash as barber_geohash',
                            'barbers.longitude as barber_longitude',
                            'barbers.latitude as barber_latitude',
                            'barbers.province as barber_province',
                            'barbers.city as barber_city',
                            'barbers.district as barber_district',
                            'barbers.detail as barber_detail',
                            'barbers.birthday as barber_birthday',
                            'barber_caches.reviews as barber_reviews',
                            'barber_caches.count as barber_count',
                            'barber_caches.avg_score as barber_avg_score',
                            'barber_caches.lower_price as barber_lower_price',
                            'barber_caches.followers as barber_followers'
                        )->first();
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $data 必须传入与模型查询相关的数据
     * @param  string $scope 查询距离的范围:1-11取值
     * @param  string $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($data, $scope='', $size=10)
    {
        $order_by = isset($data['order']) ? $data['order'] : 'desc';
        $sort_by = 'barbers.updated_at';
        if (array_key_exists('sortby', $data)) {
            if (Str::equals('avg_score', $data['sortby'])) {
                $sort_by = 'barber_caches.avg_score';
            } elseif (Str::equals('lower_price', $data['sortby'])) {
                $sort_by = 'barber_caches.lower_price';
            }  else {
                $sort_by = 'barber_caches.avg_score';
                //$sort_by = 'barbers.updated_at';
            }
        }
        
        $query = $this->createModel()->newQuery();
        $query->where('barbers.status', Barber::BARBER_STATUS_BIND);
        if (array_key_exists('ids', $data)) {
            $query->whereIn('barbers.id', $data['ids']);
        } elseif (array_key_exists('supplier_id', $data) && !empty($data['supplier_id'])) {
            $query->where('barbers.supplier_id', $data['supplier_id']);
        }
        if (Str::equals('distance', $data['sortby'])) {
            $query->whereRaw("LEFT(`geohash`, {$scope}) IN ({$data['neighbors']})");
        }
        
        $query->leftJoin('barber_caches', 'barbers.id', '=', 'barber_caches.barber_id')
                ->select(
                        'barbers.id as id',
                        'barbers.status as barber_status',
                        'barbers.mobile as barber_mobile',
                        'barbers.supplier_id',
                        'barbers.nickname as barber_nickname',
                        'barbers.title as barber_title',
                        'barbers.head_img as barber_head_img',
                        'barbers.gender as barber_gender',
                        'barbers.birthday as barber_birthday',
                        'barbers.email as barber_email',
                        'barbers.work_life as barber_work_life',
                        'barbers.descript as barber_descript',
                        'barbers.geohash as barber_geohash',
                        'barbers.longitude as barber_longitude',
                        'barbers.latitude as barber_latitude',
                        'barbers.province as barber_province',
                        'barbers.city as barber_city',
                        'barbers.district as barber_district',
                        'barbers.detail as barber_detail',
                        'barbers.birthday as barber_birthday',
                        'barber_caches.reviews as barber_reviews',
                        'barber_caches.count as barber_count',
                        'barber_caches.avg_score as barber_avg_score',
                        'barber_caches.lower_price as barber_lower_price',
                        'barber_caches.followers as barber_followers'
                );
        if (Str::equals('distance', $data['sortby'])) {
            return $query->paginate($size);
        } else {
            return $query->orderBy($sort_by, $order_by)->paginate($size);
        }
    }
    
    /**
     * 存储资源
     *
     * @param  array $inputs 必须传入与存储模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model
     */
    public function store($inputs, $extra='')
    {
        $inputs = array_add($inputs, 'password', config('appinit.init_pwd'));
        $inputs = array_add($inputs, 'title', '');
        $inputs = array_add($inputs, 'nickname', '');
        $inputs = array_add($inputs, 'realname', '');
        $inputs = array_add($inputs, 'head_img', '');
        $inputs = array_add($inputs, 'gender', -1);
        $inputs = array_add($inputs, 'email', '');
        $inputs = array_add($inputs, 'work_life', 0);
        $inputs = array_add($inputs, 'descript', '暂无');
        $inputs = array_add($inputs, 'head_img', '');
        $inputs = array_add($inputs, 'gender', -1);
        $inputs = array_add($inputs, 'geohash', '');
        $inputs = array_add($inputs, 'longitude', '');
        $inputs = array_add($inputs, 'latitude', '');
        $inputs = array_add($inputs, 'province', '');
        $inputs = array_add($inputs, 'city', '');
        $inputs = array_add($inputs, 'district', '');
        $inputs = array_add($inputs, 'detail', '');
        $inputs = array_add($inputs, 'province', '');
        $inputs = array_add($inputs, 'status', Barber::BARBER_STATUS_NOT_LOGIN);
        
        return $this->saveBarber($this->createModel(), $inputs);
    }
    
    /**
     * 获取指定资源
     *
     * @param array $where 资源查询条件
     * @param array|string $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($where, $extra='')
    {
        if (array_key_exists('id', $where)) {
            $field = 'barbers.id';
            $value = $where['id'];
        } elseif (array_key_exists('mobile', $where)) {
            $field = 'barbers.mobile';
            $value = $where['mobile'];
        }
        
        return $this->model->where($field, $value)
                        ->leftJoin('barber_caches', 'barbers.id', '=', 'barber_caches.barber_id')
                        ->select(
                            'barbers.id as id',
                            'barbers.status as barber_status',
                            'barbers.password as password',
							'barbers.account as barber_account',
                            'barbers.mobile as barber_mobile',
                            'barbers.supplier_id',
                            'barbers.nickname as barber_nickname',
                            'barbers.title as barber_title',
                            'barbers.head_img as barber_head_img',
                            'barbers.gender as barber_gender',
                            'barbers.birthday as barber_birthday',
                            'barbers.email as barber_email',
                            'barbers.work_life as barber_work_life',
                            'barbers.descript as barber_descript',
                            'barbers.geohash as barber_geohash',
                            'barbers.longitude as barber_longitude',
                            'barbers.latitude as barber_latitude',
                            'barbers.province as barber_province',
                            'barbers.city as barber_city',
                            'barbers.district as barber_district',
                            'barbers.detail as barber_detail',
                            'barbers.birthday as barber_birthday',
                            'barber_caches.reviews as barber_reviews',
                            'barber_caches.count as barber_count',
                            'barber_caches.avg_score as barber_avg_score',
                            'barber_caches.lower_price as barber_lower_price',
                            'barber_caches.followers as barber_followers'
                        )->first();
    }
    
    /**
     * 更新特定id资源
     *
     * @param  array $where 更新的条件
     * @param  array $inputs 必须传入与更新模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return model|null
     */
    public function update($where, $inputs, $extra='')
    {
        $query = $this->createModel()->newQuery();
        
        foreach ($where as $field=>$value) {
            $query->where($field, $value);
        }
        $barber = $query->first();
        if (is_null($barber)) {
            return null;
        }
        
        // 检查是否是初次登陆
        if ($barber->status == 1) {
            $inputs['status'] = Barber::BARBER_STATUS_LOGIN_NOT_ADD_INFO;
        }
        
        return $this->saveBarber($barber, $inputs);
    }
    
}