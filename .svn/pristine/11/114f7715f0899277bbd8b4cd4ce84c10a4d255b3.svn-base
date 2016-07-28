<?php

namespace App\Salon\Repositories;

use Illuminate\Support\Str;
use App\Salon\BarberSample;
use App\Salon\Contracts\Repositories\BarberSampleRepositoryInterface;

/**
 * 
 * 
 * @desc 理发师作品集数据仓库
 * @author helei <helei@bnersoft.com>
 * @date 2015年8月29日
 */
class BarberSampleRepository extends BaseRepository implements BarberSampleRepositoryInterface
{
    /**
     *
     * 创建一个理发师作品集数据仓库实例
     * @param Barber $barber
     * @return void
     */
    public function __construct(BarberSample $barberSample)
    {
        $this->model = $barberSample;
    }
    
    /**
     * 更新或创建理发师作品集
     * 
     * @param BarberSample $barberSample 理发师作品集model
     * @param array $inputs 创建的数据
     * @return boolean
     */
    protected function saveBarberSample(BarberSample $barberSample, array $inputs)
    {
        if (array_key_exists('barber_id', $inputs)) {
            $barberSample->barber_id = $inputs['barber_id'];
        }
        if (array_key_exists('opus_img', $inputs)) {
            $barberSample->opus_img = serialize($inputs['opus_img']);
        }
        if (array_key_exists('barber_id', $inputs)) {
            $barberSample->barber_id = $inputs['barber_id'];
        }
        if (array_key_exists('small_title', $inputs)) {
            $barberSample->small_title = $inputs['small_title'];
        }
        if (array_key_exists('describe', $inputs)) {
            $barberSample->describe = $inputs['describe'];
        }
        
        return $barberSample->save();
    }
    
    /**
     * 获取资源列表
     *
     * @param  int $barber_id 根据理发师id，获取列表数据
     * @param  array|string $extra 查询的额外数据
     * @param  string $size 分页大小（存在默认值）
     * @return Illuminate\Support\Collection
     */
    public function index($barber_id, $extra='', $size=10)
    {
        return $this->model
                        ->where('barber_id', $barber_id)
                        ->orderBy('updated_at', 'desc')
                        ->paginate($size);
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
        return $this->saveBarberSample($this->createModel(), $inputs);
    }
    
    /**
     * 删除特定id资源
     *
     * @param  int $id 资源id
     * @param  integer $barber_id 理发师id
     * @return void
     */
    public function destroy($id, $barber_id)
    {
        return $this->model->where('id', $id)->where('barber_id', $barber_id)->delete();
    }
}