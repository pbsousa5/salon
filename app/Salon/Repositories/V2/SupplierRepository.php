<?php

namespace App\Salon\Repositories\V2;

use App\Salon\Supplier;
use Illuminate\Support\Str;
use App\Salon\Repositories\SupplierRepository as SupplierRep;

/**
 * 
 * 
 * @desc 门店数据仓库实现
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月11日
 */
class SupplierRepository extends SupplierRep
{
    /**
     * 
     * 创建一个门店数据仓库实例
     * @param App\Salon\Supplier $supplier
     * @return void
     */
    public function __construct(Supplier $supplier)
    {
        $this->model = $supplier;
    }
    
    /**
     * 保存或者更新门店的信息
     * @param Supplier $supplier 门店model
     * @param array $inputs 更新的数据
     * @return boolean
     */
    protected function saveSupplier(Supplier $supplier, array $inputs)
    {
        if (array_key_exists('supplier_manager_id', $inputs)) {
            $supplier->supplier_manager_id = $inputs['supplier_manager_id'];
        }
        if (array_key_exists('account', $inputs)) {
            $supplier->account = trim($inputs['account']);
        }
        if (array_key_exists('mobile', $inputs)) {
            $supplier->mobile = trim($inputs['mobile']);
        }
        if (array_key_exists('password', $inputs)) {
            $supplier->password = bcrypt($inputs['password']);
        }
        if (array_key_exists('name', $inputs)) {
            $supplier->name = e(trim($inputs['name']));
        }
        if (array_key_exists('staff_count', $inputs)) {
            $supplier->staff_count = $inputs['staff_count'];
        }
        if (array_key_exists('business_time', $inputs)) {
            $supplier->business_time = serialize($inputs['business_time']);
        }
        if (array_key_exists('phones', $inputs)) {
            $supplier->phones = serialize($inputs['phones']);
        }
        if (array_key_exists('gallerys', $inputs)) {
            $supplier->gallerys = serialize($inputs['gallerys']);
        }
        if (array_key_exists('legal_name', $inputs)) {
            $supplier->legal_name = e(trim($inputs['legal_name']));
        }
        if (array_key_exists('id_num', $inputs)) {
            $supplier->id_num = trim($inputs['id_num']);
        }
        if (array_key_exists('id_photos', $inputs)) {
            $supplier->id_photos = serialize($inputs['id_photos']);
        }
        if (array_key_exists('license_photo', $inputs)) {
            $supplier->license_photo = e(trim($inputs['license_photo']));
        }
        if (array_key_exists('basic_discount', $inputs)) {
            $supplier->basic_discount = $inputs['basic_discount'];
        }
        if (array_key_exists('status', $inputs)) {
            $supplier->status = $inputs['status'];
        }
        if (array_key_exists('is_first', $inputs)) {
            $supplier->is_first = $inputs['is_first'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $supplier->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $supplier->updated_at = $inputs['updated_at'];
        }
        
        if ($supplier->save()) {
            return $supplier;
        }
        
        return null;
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $where 查询参数
     * @param  integer $kilometer 查询的范围，km
     * @param  integer $size 本次获取多少条数据
     * @return Illuminate\Support\Collection
     */
    public function index($where, $kilometer = 1, $size = 10)
    {
        $query = $this->createModel()->newQuery();
        
        $query->where('suppliers.status', 1);// 获取状态不为0(未关闭)的门店
        
        $sortBy = '';
        // 如果没有改字段，则按照距离进行排序
        if (array_key_exists('sortby', $where)) {
            if (Str::equals('avg_score', $where['sortby'])) {// 按照评分排序
                $sortBy = 'supplier_caches.avg_score';
                $orderBy = 'desc';
            } elseif (Str::equals('lower_price', $where['sortby'])) {// 按照低价排序
                $sortBy = 'supplier_caches.lower_price';
                $orderBy = 'asc';
            }  elseif (Str::equals('blend', $where['sortby'])) {// 综合排序
                $sortBy = 'supplier_caches.avg_score';
                $orderBy = 'asc';
            }
            
            $where = array_remove_keys($where, ['sortby']);
        }
        
        if (array_key_exists('ids', $where)) {
            $query->whereIn('suppliers.id', $where['ids']);
        }
        
        // 搜索时会用到
        if (array_key_exists('name', $where)) {
            $query->where('suppliers.name', 'like', "%{$where['name']}%");
        } elseif (array_key_exists('district', $where)) {
            $query->where('addresss.district', 'like', "%{$where['district']}%");
        } elseif (array_key_exists('detail', $where)) {
            $query->where('addresss.detail', 'like', "%{$where['detail']}%");
        } elseif (array_key_exists('q', $where)) {
            $query->where('suppliers.name', 'like', "%{$where['q']}%")
                    ->orWhere('addresss.district', 'like', "%{$where['q']}%")
                    ->orWhere('addresss.detail', 'like', "%{$where['q']}%");
        }
        
        // 获取相关数据
        $query->leftJoin('addresss', 'suppliers.id', '=', 'addresss.user_id')
                ->leftJoin('supplier_caches', 'suppliers.id', '=', 'supplier_caches.supplier_id')
                ->select(
                        'suppliers.id',
                        'suppliers.supplier_manager_id',
                        'suppliers.mobile',
                        'suppliers.password',
                        'suppliers.name',
                        'suppliers.staff_count',
                        'suppliers.business_time',
                        'suppliers.phones',
                        'suppliers.gallerys',
                        'suppliers.status',
                        'suppliers.created_at',
                        'suppliers.updated_at',
                        'suppliers.basic_discount',
                        'supplier_caches.reviews',
                        'supplier_caches.count',
                        'supplier_caches.avg_score',
                        'supplier_caches.lower_price',
                        'supplier_caches.hot_product_ids',
                        'supplier_caches.busy_index',
                        'supplier_caches.followers',
                        'supplier_caches.tags',
                        'addresss.distance',
                        'addresss.longitude',
                        'addresss.latitude',
                        'addresss.province',
                        'addresss.city',
                        'addresss.district',
                        'addresss.detail'
                );
        
        if (! empty($sortBy)) {
            $query->orderBy($sortBy, $orderBy);
        }
        
        return $query->paginate($size);
    }
    

    /**
     * 更新特定id资源,重新组装数组business_time
     *
     * @param  array $where 更新的条件
     * @param  array $inputs 必须传入与更新模型相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @return void
     */
    public function update($where, $inputs, $extra='')
    {
        $query = $this->createModel()->newQuery();
        foreach ($where as $field=>$value) {
            $query->where($field, $value);
        }
        $supplier = $query->first();
        if (is_null($supplier)) {
            return null;
        }
        
        $business_time = unserialize($supplier->business_time);
        if (array_key_exists('morning_time', $inputs)) {
            $business_time['morning_time'] = $inputs['morning_time'];
        }
        if (array_key_exists('noon_time', $inputs)) {
            $business_time['noon_time'] = $inputs['noon_time'];
        }        
        if (array_key_exists('afternoon_time', $inputs)) {
            $business_time['afternoon_time'] = $inputs['afternoon_time'];
        }
        if (array_key_exists('night_time', $inputs)) {
            $business_time['night_time'] = $inputs['night_time'];
        }
        $inputs['business_time'] = $business_time;
        
        if ($supplier->is_first == 1) {
            $inputs['is_first'] = 0;
        }
    
        return $this->saveSupplier($supplier, $inputs);
    }
    
}