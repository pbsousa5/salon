<?php

use Illuminate\Database\Seeder;
use App\Salon\Coupon;

class CouponTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('coupons')->delete();
        
        for ($i=0; $i<10; $i++) {
            Coupon::create([
                'face_fee' => 5+($i*10),
                'valid_term' => time(),
                'full_cat' => 10+($i*10),
                'configs' => '',
            ]);
        }
    }
}
