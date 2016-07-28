<?php

namespace App\Salon\Repositories;

use App\Salon\Contracts\Repositories\SupplierRepositoryInterface;
use App\Salon\Supplier;
use Illuminate\Support\Str;
use DB;

/**
 * 
 * 
 * @desc 门店数据仓库实现
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class SupplierRepository extends BaseRepository implements SupplierRepositoryInterface
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
        if (array_key_exists('mobile', $inputs)) {
            $supplier->account = $inputs['mobile'];
            $supplier->mobile = $inputs['mobile'];
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
            $supplier->id_num = $inputs['id_num'];
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
        
        return $supplier->save();
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $data 必须传入与模型查询相关的数据
     * @param  integer $scope 查询距离的范围:1-11取值
     * @param  integer $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($data, $scope=1, $size=10)
    {
        $order_by = isset($data['order']) ? $data['order'] : 'desc';
        $sort_by = 'suppliers.updated_at';
        if (array_key_exists('sortby', $data)) {
            if (Str::equals('avg_score', $data['sortby'])) {
                $sort_by = 'supplier_caches.avg_score';
            } elseif (Str::equals('lower_price', $data['sortby'])) {
                $sort_by = 'supplier_caches.lower_price';
            }  else {
                //$sort_by = 'suppliers.updated_at';
                // 目前默认的排序规则与之上相同
                $sort_by = 'supplier_caches.avg_score';
            }
        }
        
        $query = $this->createModel()->newQuery();
        if (array_key_exists('name', $data)) {
            $query->where('suppliers.name', 'like', "%{$data['name']}%");
        } elseif (array_key_exists('district', $data)) {
            $query->where('addresss.district', 'like', "%{$data['district']}%");
        } elseif (array_key_exists('detail', $data)) {
            $query->where('addresss.detail', 'like', "%{$data['detail']}%");
        } elseif (array_key_exists('ids', $data)) {
            $query->whereIn('suppliers.id', $data['ids']);
        } elseif (array_key_exists('q', $data)) {
            $query->where('suppliers.name', 'like', "%{$data['q']}%")
                    ->orWhere('addresss.district', 'like', "%{$data['q']}%")
                    ->orWhere('addresss.detail', 'like', "%{$data['q']}%");
        }
        
        $query->where('suppliers.status', 1);// 获取状态不为0(未关闭)的门店
        $query->where('addresss.user_type', 'supplier');// 只选择门店的地址
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
                            'addresss.distance',
                            'addresss.longitude',
                            'addresss.latitude',
                            'addresss.province',
                            'addresss.city',
                            'addresss.district',
                            'addresss.detail',
                            'supplier_caches.reviews',
                            'supplier_caches.count',
                            'supplier_caches.avg_score',
                            'supplier_caches.lower_price',
                            'supplier_caches.hot_product_ids',
                            'supplier_caches.busy_index',
                            'supplier_caches.followers',
                            'supplier_caches.tags'
                    );
        
        if (Str::equals('distance', $data['sortby']) && array_key_exists('neighbors', $data)) {
            $query->whereRaw("LEFT(`distance`, {$scope}) IN ({$data['neighbors']})");
        } else {
            $query->orderBy($sort_by, $order_by);
        }
        
        return $query->paginate($size);           
    }
    
    /**
     * 获取指定资源
     *
     * @param array $condition 查询条件 唯一索引，可以是主键id，也可以是手机号码
     * @param array|string $extra 额外的其他数据
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($condition, $extra='')
    {
        if (array_key_exists('id', $condition)) {
            $column = 'suppliers.id';
            $vaule = $condition['id'];
        } elseif (array_key_exists('mobile', $condition)) {
            $column = 'suppliers.mobile';
            $vaule = $condition['mobile'];
        } elseif (array_key_exists('account', $condition)) {
            $column = 'suppliers.account';
            $vaule = $condition['account'];
        } else {
            return null;
        }
        
        $supplier = $this->model
                            ->where($column, $vaule)
                            ->where('addresss.user_type', 'supplier')
                            ->leftJoin('addresss', 'suppliers.id', '=', 'addresss.user_id')
                            ->leftJoin('supplier_caches', 'suppliers.id', '=', 'supplier_caches.supplier_id')
                            ->select(
                                    'suppliers.id',
                                    'suppliers.supplier_manager_id',
									'suppliers.account',
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
                                    'suppliers.is_first',
                                    'addresss.distance',
                                    'addresss.longitude',
                                    'addresss.latitude',
                                    'addresss.province',
                                    'addresss.city',
                                    'addresss.district',
                                    'addresss.detail',
                                    'supplier_caches.reviews',
                                    'supplier_caches.avg_score',
                                    'supplier_caches.lower_price',
                                    'supplier_caches.hot_product_ids',
                                    'supplier_caches.busy_index',
                                    'supplier_caches.followers',
                                    'supplier_caches.tags',
                                    'supplier_caches.count'
                            )
                            ->first();
        
        return $supplier;
    }
    
    /**
     * 更新特定id资源
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
        
        if ($supplier->is_first == 1) {
            $inputs['is_first'] = 0;
        }
        
        return $this->saveSupplier($supplier, $inputs);
    }
    
    /**
     * 根据条件，统计数据
     *
     * @param array $where 统计的条件
     * @param array $extra 额外的条件
     *
     * @return integer
     */
    public function count($where, $extra = [])
    {
        $query = $this->createModel()->newQuery();
    
        while (list($field, $value) = each($where)) {
            if ($field == 'name') {
                $query->where($field, 'like', "%{$value}%");
            } else {
                $query->where($field, $value);
            }
        }
    
        return $query->count();
    }
}