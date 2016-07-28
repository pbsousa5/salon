<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Salon\Product;
use App\Salon\BarberProduct;

/**
 * 
 * 
 * @desc 服务器定时任务的执行
 * @author helei <helei@bnersoft.com>
 * @date 2015年10月19日
 */
class CrontabController extends Controller
{
    /**
     * 文件名称
     * @var string
     */
    protected $filename;
    
    /**
     * 以追加方式打开文件
     * @var resource
     */
    protected $handle;
    
    /**
     * 需要更新的分类id
     * @var int
     */
    protected $category_id;
    
    public function __construct()
    {
        $this->category_id = config('appinit.active_category');
        if (0 == $this->category_id) {
            return ;
        }
        
        $this->filename = storage_path('logs/crontab_active.txt');
        $this->handle = fopen($this->filename, 'a');
    }
    
    /**
     * 
     * 更新活动商品库存
     */
    public function updateActiveProductStock()
    {
        $products = Product::where('category_id', $this->category_id)->get();
        if (! $products->isEmpty()) {
            foreach ($products as $product) {
                $product->total_stock = $product->const_stock;
                $product->save();
                
                $barberProduct = BarberProduct::where('product_id', $product->id)->first();
                if (! is_null($barberProduct)) {
                    $barberProduct->total_stock = $barberProduct->const_stock;
                    $barberProduct->save();
                }
            }
        }
        
        $this->log();
    }
    
    /**
     * 
     * 向文件写入日志
     */
    private function log()
    {
        date_default_timezone_set('PRC');
        
        $log = date('Y-m-d H:i:s') . '执行crontab自动更新';
        
        fwrite($this->handle, $log."\n");
        
        fclose($this->handle);
    }
}