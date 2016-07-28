<?php

use Illuminate\Database\Seeder;
use App\Salon\OrderAction;

class OrderActionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_actions')->delete();
        
        OrderAction::create([
            'order_product_id' => 1,
            'action_name' => 'miaosha',
            'configs' => '',
        ]);
    }
}
