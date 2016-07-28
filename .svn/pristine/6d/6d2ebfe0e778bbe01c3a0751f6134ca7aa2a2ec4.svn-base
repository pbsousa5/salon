<?php

use Illuminate\Database\Seeder;
use App\Salon\OrderInfo;
use App\Libary\Util\String;

class OrderInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_infos')->delete();
        
        OrderInfo::create([
            'trade_no' => String::buildTimeString(time(), 'E'),
            'consumer_id' => 1,
            'postscript' => '大王要来巡山',
            'consumer_coupon_id' => 1,
            'coupon_face_fee' => '10',
            'bean_amount' => '12',
            'bean_fee' => '12',
            'original_fee' => 300,
            'pay_fee' => '200',
            'advance_time' => time(),
            'pay_name' => '微信支付',
            'pay_code' => 'WEIXIN_DEBIT',
            'consumer_name' => '习主席',
            'consumer_mobile' => '18683338411',
            'consumer_head' => '',
        ]);
    }
}
