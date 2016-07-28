<?php

namespace App\Salon\Repositories;

use App\Salon\Address;
use App\Salon\Contracts\Repositories\AddressRepositoryInterface;

/**
 * 
 * 
 * @desc 店家地址集合
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
class AddressRepository extends BaseRepository implements AddressRepositoryInterface
{
    /**
     * 
     * 创建一个地址数据仓库实例
     * @param App\Salon\Address $address
     * @return void
     */
    public function __construct(Address $address)
    {
        $this->model = $address;
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
            if ($field == 'district' || $field == 'detail') {
                $query->where($field, 'like', "%{$value}%");
            } else {
                $query->where($field, $value);
            }
        }
        
        return $query->count();
    }
}