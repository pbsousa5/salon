<?php

use Illuminate\Database\Seeder;
use App\Salon\ConsumerCoupon;

class ConsumerCouponTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('consumer_coupons')->delete();
        
        ConsumerCoupon::create([
            'consumer_id' => 1,
            'coupon_id' => 1,
            'deadline' => time(),
            'created_at' => time(),
        ]);
    }
}
