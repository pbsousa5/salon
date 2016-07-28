<?php

namespace App\Salon\Repositories\V2;

use App\Salon\Barber;
use App\Salon\Repositories\BarberRepository as BarberRep;
use Illuminate\Support\Str;

/**
 *
 *
 *
 * @desc 理发师数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class BarberRepository extends BarberRep
{
    /**
     *
     * 创建一个理发师数据仓库实例
     * @param App\Salon\Barber $barber
     * @return void
     */
    public function __construct(Barber $barber)
    {
        $this->model = $barber;
    }

    /**
     * 更新或创建理发师数据
     * 
     * @param Barber $barber 理发师model
     * @param array $inputs 更新的数据
     * @return Barber|null
     */
    protected function saveBarber(Barber $barber, array $inputs)
    {
        if (array_key_exists('supplier_id', $inputs)) {
            $barber->supplier_id = $inputs['supplier_id'];
        }
        if (array_key_exists('account', $inputs)) {
            $barber->account = trim($inputs['account']);
        }
        if (array_key_exists('mobile', $inputs)) {
            $barber->mobile = trim($inputs['mobile']);
        }
        if (array_key_exists('password', $inputs)) {
            $barber->password = bcrypt($inputs['password']);
        }
        if (array_key_exists('title', $inputs)) {
            $barber->title = e(trim($inputs['title']));
        }
        if (array_key_exists('nickname', $inputs)) {
            $barber->nickname = e(trim($inputs['nickname']));
        }
        if (array_key_exists('realname', $inputs)) {
            $barber->realname = e(trim($inputs['realname']));
        }
        if (array_key_exists('head_img', $inputs)) {
            $barber->head_img = $inputs['head_img'];
        }
        if (array_key_exists('gender', $inputs)) {
            $barber->gender = $inputs['gender'];
        }
        if (array_key_exists('birthday', $inputs)) {
            $barber->birthday = $inputs['birthday'];
        }
        if (array_key_exists('email', $inputs)) {
            $barber->email = strtolower(trim($inputs['email']));
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
            $barber->longitude = trim($inputs['longitude']);
        }
        if (array_key_exists('latitude', $inputs)) {
            $barber->latitude = trim($inputs['latitude']);
        }
        if (array_key_exists('province', $inputs)) {
            $barber->province = trim($inputs['province']);
        }
        if (array_key_exists('city', $inputs)) {
            $barber->city = trim($inputs['city']);
        }
        if (array_key_exists('district', $inputs)) {
            $barber->district = trim($inputs['district']);
        }
        if (array_key_exists('detail', $inputs)) {
            $barber->detail = trim($inputs['detail']);
        }
        if (array_key_exists('status', $inputs)) {
            $barber->status = $inputs['status'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $barber->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $barber->updated_at = $inputs['updated_at'];
        }
        
        if ($barber->save()) {
            return $barber;
        }
        
        return null;
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $where 必须传入与模型查询相关的数据
     * @param  string $extra 额外查询条件
     * @param  integer $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($where, $extra='', $size=20)
    {
        $query = $this->createModel()->newQuery();
        
        $query->where('barbers.status', Barber::BARBER_STATUS_BIND);// 获取绑定状态的理发师
        
        $sortBy = '';// 如果不存在该字段，表示用距离排序
        if (array_key_exists('sortby', $where)) {
            if (Str::equals('avg_score', $where['sortby'])) {// 平均分排序
                $sortBy = 'barber_caches.avg_score';
                $orderBy = 'desc';
            } elseif (Str::equals('lower_price', $where['sortby'])) {// 最低价排序
                $sortBy = 'barber_caches.lower_price';
                $orderBy = 'asc';
            } else {// 按粉丝人气排序
                $sortBy = 'barber_caches.followers';
                $orderBy = 'desc';
            }
            
            $where = array_remove_keys($where, ['sortby']);
        }
        
        if (array_key_exists('ids', $where)) {
            $query->whereIn('barbers.id', $where['ids']);
        }
        if (array_key_exists('supplier_id', $where) && !empty($where['supplier_id'])) {
            $query->where('barbers.supplier_id', $where['supplier_id']);
        }
        
        $query->leftJoin('barber_caches', 'barbers.id', '=', 'barber_caches.barber_id')
                    ->select(
                            'barbers.id',
                            'barbers.supplier_id',
                            'barbers.status as barber_status',
                            'barbers.mobile as barber_mobile',
                            'barbers.supplier_id as barber_supplier_id',
                            'barbers.nickname as barber_nickname',
                            'barbers.title as barber_title',
                            'barbers.head_img as barber_head_img',
                            'barbers.gender as barber_gender',
                            'barbers.birthday as barber_birthday',
                            'barbers.email as barber_email',
                            'barbers.work_life as barber_work_life',
                            'barbers.descript as barber_descript',
                            'barbers.longitude as barber_longitude',
                            'barbers.latitude as barber_latitude',
                            'barbers.province as barber_province',
                            'barbers.city as barber_city',
                            'barbers.district as barber_district',
                            'barbers.detail as barber_detail',
                            'barber_caches.reviews as barber_reviews',
                            'barber_caches.count as barber_count',
                            'barber_caches.avg_score as barber_avg_score',
                            'barber_caches.lower_price as barber_lower_price',
                            'barber_caches.followers as barber_followers'
                    );
        
        if (! empty($sortBy)) {
            $query->orderBy($sortBy, $orderBy);
        }
        
        return $query->paginate($size);
    }
}