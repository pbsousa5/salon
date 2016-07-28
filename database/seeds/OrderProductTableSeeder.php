<?php

use Illuminate\Database\Seeder;
use App\Salon\OrderProduct;
use App\Libary\Util\String;

class OrderProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_products')->delete();
        
        OrderProduct::create([
            'order_info_id' => 1,
            'product_id' => 1,
            'consumer_id' => 1,
            'supplier_id' => 1,
            'category_name' => '洗剪吹',
            'product_name' => '环球霸业',
            'product_desc' => '牛逼的不得了',
            'original_price' => 3000,
            'pay_price' => 1500,
            'good_number' => 1,
            'is_action' => 0,
            'is_real' => 1,
            'is_back' => 1,
            'consume_code' => String::randString(9, 1),
        ]);
    }
}
