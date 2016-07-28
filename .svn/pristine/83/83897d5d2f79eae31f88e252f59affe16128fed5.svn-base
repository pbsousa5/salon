<?php

namespace App\Salon\Repositories\V2;

use App\Salon\JoinApply;
use App\Salon\Repositories\JoinApplyRepository as JoinApplyRep;
/**
 *
 *
 *
 * @desc 加入我们数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class JoinApplyRepository extends JoinApplyRep
{
    /**
     *
     * 创建一个加入我们仓库实例
     * @param App\Salon\JoinApply $joinApply
     * @return void
     */
    public function __construct(JoinApply $joinApply)
    {
        $this->model = $joinApply;
    }

    /**
     * 更新或创建加入我们
     * @param JoinApply $joinApply 加入我们model
     * @param array $inputs 更新的数据
     * @return JoinApply|null
     */
    protected function saveJoinApply(JoinApply $joinApply, array $inputs)
    {
        if (array_key_exists('mobile', $inputs)) {
            $joinApply->mobile = trim($inputs['mobile']);
        }
        if (array_key_exists('store_name', $inputs)) {
            $joinApply->store_name = e(trim($inputs['store_name']));
        }
        if (array_key_exists('legal_name', $inputs)) {
            $joinApply->legal_name = e(trim($inputs['legal_name']));
        }
        if (array_key_exists('status', $inputs)) {
            $joinApply->status = $inputs['status'];
        }
        if (array_key_exists('created_at', $inputs)) {
            $joinApply->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $joinApply->updated_at = $inputs['updated_at'];
        }
        
        if ($joinApply->save()) {
            return $joinApply;
        }
        
        return null;
    }    
}