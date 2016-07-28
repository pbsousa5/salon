<?php

namespace App\Salon\Repositories\V2;

use App\Salon\BarberSample;
use App\Salon\Repositories\BarberSampleRepository as BarberSampleRep;
/**
 *
 *
 *
 * @desc 理发师作品集数据仓库
 * @author fengdq <fengdq@bnersoft.com>
 * @date 2015年11月12日
 */
class BarberSampleRepository extends BarberSampleRep
{
    /**
     *
     * 创建一个理发师作品集数据仓库实例
     * @param App\Salon\BarberSample $barbersample
     * @return void
     */
    public function __construct(BarberSample $barberSample)
    {
        $this->model = $barberSample;
    }

    /**
     * 更新或创建理发师作品集
     * @param BarberSample $barberSample 理发师作品集model
     * @param array $inputs 更新的数据
     * @return BarberSample|null
     */
    protected function saveBarberSample(BarberSample $barberSample, array $inputs)
    {
        if (array_key_exists('barber_id', $inputs)) {
            $barberSample->barber_id = $inputs['barber_id'];
        }
        if (array_key_exists('opus_img', $inputs)) {
            $barberSample->opus_img = serialize($inputs['opus_img']);
        }
        if (array_key_exists('small_title', $inputs)) {
            $barberSample->small_title = e(trim($inputs['small_title']));
        }
        if (array_key_exists('describe', $inputs)) {
            $barberSample->describe = e(trim($inputs['describe']));
        }
        if (array_key_exists('created_at', $inputs)) {
            $barberSample->created_at = $inputs['created_at'];
        }
        if (array_key_exists('updated_at', $inputs)) {
            $barberSample->updated_at = $inputs['updated_at'];
        }
        
        if ($barberSample->save()) {
            return $barberSample;
        }
        
        return null;
    }
    
    /**
     * 删除相册，或者删除相册中的某张图
     * 
     * @param array $ids 要删除的相册id数组
     * @param array $extra 要删除的其他条件
     * 
     * @return boolean
     */
    public function destroy($ids, $extra = [])
    {
        $query = $this->createModel()->newQuery();
        if (1 == count($ids)) {
            $query->where('id', $ids[0]);
        } else {
            $query->whereIn('id', $ids);
        }
        
        if (array_key_exists('barber_id', $extra)) {
            $query->where('barber_id', $extra['barber_id']);
        }
        
        if (array_key_exists('img_name', $extra)) {
            $sample = $query->first();
            if (is_null($sample)) return null;
            
            $imgArr = unserialize($sample->opus_img);
            $imgNames = $extra['img_name'];
            
            $is_update = false;
            foreach ($imgNames as $key => $name) {
                if ($name != '' && in_array($name, $imgArr)) {
                    $imgArr = array_remove_value($imgArr, $name);
                    $is_update = true;
                }
            }
            
            if (! $is_update) {// 删除的图片不存在
                return false;
            }
            
            if (empty($imgArr)) {
                return $sample->delete();
            }
            
            return $this->saveBarberSample($sample, ['opus_img' => $imgArr]);
        } else {
            return $query->delete();
        }
    }
    
    /**
     * 更新给定条件的理发师作品集
     * 
     * @param array $where 指定条件
     * @param array $inputs 更新指定内容
     * @param string $extra 额外条件，默认为空
     * 
     * @reteurn boolean
     */
    public function update($where, $inputs, $extra = '')
    {
        $query = $this->createModel()->newQuery();
        
        foreach ($where as $field => $value) {
            $query->where($field, $value);
        }
        
        $barberSample = $query->first();
        if (is_null($barberSample)) return false;
        
        $imgArr = (array)unserialize($barberSample->opus_img);
        if ($extra == 'inside' && array_key_exists('opus_img', $inputs)) {
            $inputs['opus_img'] = array_merge($imgArr, $inputs['opus_img']);
        } elseif ($extra == 'cover' && array_key_exists('opus_img', $inputs)) {
            if (in_array($inputs['opus_img'][0], $imgArr)) {// 修改为封面的图片已经存在，则只需要交换位置
                $imgArr = array_remove_value($imgArr, $inputs['opus_img'][0]);
            }
            
            array_unshift($imgArr, $inputs['opus_img'][0]);
            $inputs['opus_img'] = $imgArr;
        }
        
        return $this->saveBarberSample($barberSample, $inputs);
    }
}