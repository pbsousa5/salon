<?php
namespace App\Libary\Location;

use DB;

/**
 * 
 * 
 * @desc 获取mongodb的链接，使用单例模式
 * @author helei <helei@bnersoft.com>
 * @date 2015年11月10日
 */
class MongoClient
{
    private static $mongodb;
    
    private function __construct()
    {
        
    }
    
    /**
     * 获取mongodb的实例对象
     * 
     */
    public static function getInstance()
    {
        if (is_null(self::$mongodb)) {
            self::$mongodb = DB::connection('mongodb');
        }
        
        return self::$mongodb;
    }
    
    public function __clone()
    {
        die('clone is not allowed!');
    }
}