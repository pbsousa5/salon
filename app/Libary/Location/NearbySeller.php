<?php
namespace App\Libary\Location;

use App\Libary\Util\Location;
/**
 * 
 * 
 * @desc 根据mongodb获取附近的门店信息
 *       mongodb的索引需要支持2dsphere
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月10日
 */
class NearbySeller
{
    private $longitude;#经度
    private $latitude;#纬度
    private $mongodb;
    
    /**
     * 初始化
     * 
     * @param string $collection 需要操作的集合
     * @param float $longitude 经度
     * @param float $latitude 纬度
     */
    public function __construct($collection, $longitude, $latitude)
    {
        $this->longitude = (float)$longitude;
        $this->latitude = (float)$latitude;
        
        $this->mongodb = MongoClient::getInstance()->collection($collection);
    }
    
    /**
     * 搜索全部数据 按照由近到远排序
     * @param integer $perPage 获取多少数据，每页
     * @param integer $page 获取的第几页，
     * 
     * @return 返回获取到的地址距离
     */
    public function getFromNearToFar($perPage, $page)
    {
        $where = [
                'loc' => [
                        '$nearSphere' => [$this->longitude, $this->latitude]
                ],
                //'status' => 1,
        ];
        
        $list = $this->mongodb->whereRaw($where)->skip(($page-1) * $perPage)->take($perPage)->get();
        
        $this->handleDistance($list);
        return $list;
    }
    
    
    /**
     * 搜索多少km内的信息，由近到远的顺序返回
     * @param integer $kilometer 获取多少km内的信息
     * @param integer $perPage 获取多少数据，每页
     * @param integer $page 获取的第几页，
     * 
     * @return 返回获取到的地址距离
     */
    public function getRangeBySort($kilometer, $perPage, $page)
    {
        $where = [
                'loc' => [
                        '$nearSphere' => [
                                '$geometry' => [
                                        'type' => 'Point',
                                        'coordinates' => [$this->longitude, $this->latitude]
                                ],
                                '$maxDistance' => $kilometer*1000
                        ]
                ],
                //'status' => 1,
        ];
        
        $list = $this->mongodb->whereRaw($where)->skip(($page-1) * $perPage)->take($perPage)->get();
        
        $this->handleDistance($list);
        return $list;
    }
    
    /**
     * 搜索多少km内的信息，由近到远的顺序返回
     * @param integer $kilometer 获取多少km内的信息
     * 
     * @return 返回获取到的地址距离
     */
    public function getRadiusBydisorder($kilometer)
    {
        $where = [
                'loc' => [
                        '$geoWithin' => [
                                '$centerSphere' => [
                                        [
                                                $this->longitude,
                                                $this->latitude
                                        ],
                                        $kilometer/6371
                                ]
                        ]
                ],
                //'status' => 1,
        ];
        
        $list = $this->mongodb->whereRaw($where)->get();
        
        $this->handleDistance($list);
        return $list;
    }
    
    /**
     * 处理所有的数据，计算出距离
     * 
     */
    private function handleDistance(&$list)
    {
        foreach ($list as $key => $val) {
            $srcLongLat = "{$this->longitude},{$this->latitude}";
            $destLongLat = $val['loc']['longitude'] . ',' . $val['loc']['latitude'];
            $list[$key]['dis'] = Location::getP2PDistance($srcLongLat, $destLongLat);
        }
        
        return $list;
    }
}