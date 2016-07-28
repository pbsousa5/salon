<?php

use Illuminate\Database\Seeder;
use App\Salon\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->delete();
        
        for ($i=0; $i<10; $i++) {
            Product::create([
                'supplier_id' => 1,
                'category_id' => 1,
                'product_name' => '产品名称'.$i,
                'product_desc' => '产品描述'.$i,
                'sell_price' => 1000+$i,
                'original_price' => 2000+$i,
                'total_stock' => 0,
                'quota_num' => 0,
                'status' => 1,
                'sold_type' => 0,
                'start_sold_time' => 0,
                'rich_desc' => '<p>爱护你的身材，当你失去了，不然再找回来会是一个很痛苦的过程!</p><p>记住：借口和懒惰不会帮助你消耗卡路里。</p><p><img src="http://7xjwop.com2.z0.glb.qiniucdn.com/top_hd1.jpg" title="1433146484468278.jpg" alt="640.webp (1).jpg"></p><p>25岁前的相貌，是父母给的，25岁后的相貌，是自己修的。</p><p><img src="http://7xjwop.com2.z0.glb.qiniucdn.com/top_hd2.jpg" title="1433146495851726.jpg" alt="640.webp (2).jpg"></p><p>世界上没有垃圾，只有放错地方的宝藏。</p><p><img src="http://7xjwop.com2.z0.glb.qiniucdn.com/top_hd3.jpg" title="1433146526980430.jpg" alt="640.webp (3).jpg"></p>',
                'is_real' => 1,
            ]);
        }
    }
}
