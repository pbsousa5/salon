<?php

use Illuminate\Database\Seeder;
use App\Salon\ConsumeLog;

class ConsumeLogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('consume_logs')->delete();
        
        ConsumeLog::create([
            'order_info_id' => 1,
            'order_product_id' => 1,
            'consumer_id' => 1,
            'supplier_id' => 1,
            'created_at' => time(),
        ]);
    }
}
