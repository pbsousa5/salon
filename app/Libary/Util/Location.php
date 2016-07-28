<?php

namespace App\Libary\Util;

/**
 * 
 * 
 * @desc 地理位置信息
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月28日
 */
class Location
{
    // 地球的求半径，单位还是m
    const EARTH_RADIUS = 6378137;
    
    /**
     * 将传入的百度经纬度、转化为gps坐标
     * @param string $lnglat,如：121.437518,31.224665
     * @return string|multitype:
     */
    public static function FromBaiduToGpsXY($lnglat)
    {
        // 经度,纬度
        $lnglat = explode(',',$lnglat);
        list($x,$y) = $lnglat;
        $Baidu_Server = "http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x={$x}&y={$y}";
        $result = @file_get_contents($Baidu_Server);
        $json = json_decode($result);
        if($json->error == 0){
            $bx = base64_decode($json->x);
            $by = base64_decode($json->y);
            $GPS_x = 2 * $x - $bx;
            $GPS_y = 2 * $y - $by;
            return $GPS_x.','.$GPS_y;//经度,纬度
        }else{
            return  $lnglat;
        }
    }
    
    /**
     * 经纬度转化为幅度
     * @param string $d
     * @return number
     */
    private static function fnRad($d)
    {
        return $d * pi() / 180.0;
    }
    
    /**
     * 计算两点之间的距离，单位m
     * latitude(-90,90)
     * longitude(-180,180)
     * @param string $lnglat1
     * @param string $lnglat2
     */
    public static function getP2PDistance($srcLongLat, $destLongLat)
    {
        if (empty($srcLongLat) || empty($destLongLat)) {
            return 0;
        }
        
        $srcLongLat = explode(',', $srcLongLat);
        $destLongLat = explode(',', $destLongLat);
        list($lng1, $lat1) = $srcLongLat;
        list($lng2, $lat2) = $destLongLat;
        
        //return self::googleDistance($lat1, $lng1, $lat2, $lng2);
        //return self::selfDistance($lat1, $lng1, $lat2, $lng2);
        // 百度专属算法
        return self::baiduDistance($lat1, $lng1, $lat2, $lng2);
    }
    
    /**
     * 针对百度经纬度的算法，单位m
     */
    private static function baiduDistance($lat1, $lng1, $lat2, $lng2)
    {
        $lng1 = self::fD($lng1, -180, 180);
        $lat1 = self::jD($lat1, -74, 74);
        $lng2 = self::fD($lng2, -180, 180);
        $lat2 = self::jD($lat2, -74, 74);
        
        return self::cE(self::yk($lng1), self::yk($lng2), self::yk($lat1), self::yk($lat2));
    }
    
    private static function fD($a, $b, $c)
    {
        for (; $a>$c; ) {
            $a -= $c - $b;
        }
        for (; $a<$b; ) {
            $a += $c - $b;
        }
        
        return $a;
    }
    private static function jD($a, $b, $c)
    {
        $b != null && ($a = max($a, $b));
        $c != null && ($a = min($a, $c));
        
        return $a;
    }
    private static function yk($a)
    {
        return M_PI * $a / 180;
    }
    private static function cE($a, $b, $c, $d)
    {
        $dO = 6370996.81;
        return $dO * acos(sin($c) * sin($d) + cos($c)*cos($d)*cos($b - $a));
    }
    
    /**
     * 自定义算法
     * 效率更高
     */
    private static function selfDistance($lat1, $lng1, $lat2, $lng2)
    {
        //将角度转为狐度
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        //结果
        $s = acos(cos($radLat1)*cos($radLat2)*cos($radLng1-$radLng2)+sin($radLat1)*sin($radLat2))*self::EARTH_RADIUS;
        //精度
        $s = round($s* 10000)/10000;
        
        return  round($s);
    }
    
    /**
     * google的算法
     * 效率稍微差一点
     */
    private static function googleDistance($lat1, $lng1, $lat2, $lng2)
    {
        // 通过纬度取得对应的幅度
        $srcRadLat = self::fnRad($lat1);
        $destRadLat = self::fnRad($lat2);
        
        // 获取两点纬度弧度差
        $a = $srcRadLat - $destRadLat;
        // 获取两点经度的弧度差
        $b = self::fnRad($lng1) - self::fnRad($lng2);
        
        // 计算球体上该弧度对应的距离
        $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($srcRadLat)*cos($destRadLat)*pow(sin($b/2),2))) * self::EARTH_RADIUS;
        // 取得距离的km数
        $s = round($s * 10000) / 10000;
        
        return round($s);
    }
}