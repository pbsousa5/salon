<?php

use Illuminate\Database\Seeder;
use App\Salon\BackProduct;

class BackProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('back_products')->delete();
        
        BackProduct::create([
            'back_order_id' => 1,
            'product_id' => 1,
            'supplier_id' => 1,
            'category_name' => '洗剪吹',
            'product_name' => '霸王水上漂',
            'back_fee' => 1000,
            'back_number' => 1,
        ]);
    }
}
