<?php

namespace App\Salon\Repositories;

use Illuminate\Support\Str;
use App\Salon\BarberProduct;
use DB;
use App\Salon\Contracts\Repositories\BarberProductRepositoryInterface;

/**
 * 
 * 
 * @desc 理发师产品数据仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月27日
 */
class BarberProductRepository extends BaseRepository implements BarberProductRepositoryInterface
{
    /**
     *
     * 创建一个理发师产品数据仓库实例
     * @param Barber $barber
     * @return void
     */
    public function __construct(BarberProduct $barberProduct)
    {
        $this->model = $barberProduct;
    }
    
    /**
     * 根据给定的条件，统计有多少条记录
     *
     * @param array $where 统计的条件
     * @param string $extra 额外条件
     * @return integer
     */
    public function countByWhere($where, $extra='')
    {
        $query = $this->createModel()->newQuery();
        
        while (list($field, $value) = each($where)) {
            $query->where($field, $value);
        }
        
        return $query->count();
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $data 必须传入与模型查询相关的数据
     * @param  array $order 排序字段
     * @param  string $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($data, $order=['updated_at'=>'desc'], $size=10)
    {
        $query = $this->createModel()->newQuery();
        foreach ($order as $key=>$val) {
            $sort_by = $key;
            $order_by = $val;
        }
        
        $query->with('category');
        if (array_key_exists('status', $data)) {
            $query->whereIn('status', $data['status']);
        }
        
        foreach ($data as $field=>$value) {
            if (!Str::equals('status', $field)) {
                $query->where($field, $value);
            }
        }
        
        $list = $query->orderBy(DB::raw("(SELECT sort_num FROM product_categorys WHERE barber_products.category_id = product_categorys.id),GREATEST(`sell_price`,`sign_price`)"), 'asc')->get();
        
        return $list;
    }
    
    /**
     * 获取指定资源
     *
     * @param array $where 查询条件
     * @param array $extra 可选额外传入的参数
     * @return Illuminate\Database\Eloquent\Model
     */
    public function show($where, $extra=[])
    {
        $query = $this->createModel()->newQuery();
        
        $query->with('category', 'supplier');
        foreach ($where as $field => $value) {
            $query->where($field, $value);
        }
        $query->where('is_delete', 0)->where('status', 1);
        
        return $query->first();
    }
    
}