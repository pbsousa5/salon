<?php

use Illuminate\Database\Seeder;
use App\Salon\BackOrder;
use App\Libary\Util\String;

class BackOrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('back_orders')->delete();
        
        BackOrder::create([
            'order_info_id' => 1,
            'order_product_id' => 1,
            'trade_no' => String::buildTimeString(time(), 'B'),
            'consumer_id' => 1,
            'postscript' => '很不错',
            'back_fee' => 1000,
            'consumer_coupon_id' => 1,
            'consumer_name' => '煎饼侠',
            'consumer_mobile' => '18683338411',
            'consumer_head' => 'dkdfjsdfldfjdlsfldd.jpg'
        ]);
    }
}
