<?php

namespace App\Salon\Contracts\Repositories;

/**
 * 
 * 
 * @desc 订单产品数据仓库接口
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月29日
 */
interface OrderProductRepositoryInterface
{
    /**
     * 批量更新
     *
     * @param array $where 批量更新的条件
     * @param array $inputs 批量更新的数据
     * @return
     */
    public function batchUpdate($where, $data);
    
}