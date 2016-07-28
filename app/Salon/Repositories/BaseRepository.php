<?php

namespace App\Salon\Repositories;

/**
 * 
 * 
 * @desc 所有仓库继承的公共类
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月11日
 */
abstract class BaseRepository
{
    /**
     * The Model instance.
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model;
    
    /**
     * 创建一个model实例
     * 
     */
    public function createModel()
    {
        return new $this->model;
    }
    
    /**
     * 获取资源列表
     *
     * @param  array $where 必须传入与模型查询相关的数据
     * @param  string|array $extra 可选额外传入的参数
     * @param  int $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($where, $extra = '', $size = 20)
    {
        $query = $this->createModel()->newQuery();
        
        while (list($field, $value) = each($where)) {
            $query->where($field, $value);
        }
        
        $query->paginate($size);
    }
    
    /**
     * 获取指定资源
     *
     * @param array $where 资源查找条件
     * @param array|string $extra 可选额外传入的参数
     * @return App\Meifa\Supplier
     */
    public function show($where, $extra)
    {
        $query = $this->createModel()->newQuery();
    
        while (list($field, $value) = each($where)) {
            $query->where($field, $value);
        }
    
        return $query->first();
    }
    
    /**
     * IRepository接口destory方法
     * 请在子类中重写或重载具体的实现方法
     *
     * @param  int $ids
     * @param  string|array $extra
     * @return boolean
     */
    public function destroy($ids = [], $extra)
    {
        $len = count($ids);
        if ($len == 0) {
            return ;
        }
        
        $query = $this->createModel()->newQuery();
        if ($len == 1) {
            $query->where('id', $ids[0]);
        } else {
            $query->whereIn('id', $ids);
        }
        
        return $query->delete();
    }
    
    /**
     * Get Model by id.
     *
     * @param  int $id
     * @return App\Models\Model
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
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
            $query->where($field, $value);
        }
        
        return $query->count();
    }
    
    /**
     * 根据条件，统计数据
     *
     * @param array $where 修改的条件
     * @param array $inputs 修改的数据
     * @param mixed $extra 其他额外数据
     *
     * @return integer
     */
    public function update($where, $inputs, $extra)
    {
        return ;
    }
}