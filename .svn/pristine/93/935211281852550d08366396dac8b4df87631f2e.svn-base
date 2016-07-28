<?php

use Illuminate\Database\Seeder;
use App\Salon\ConsumerWatch;

class ConsumerWatchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('consumer_watchs');
        
        ConsumerWatch::create([
            'consumer_id' => 1,
            'supplier_id' => 1,
            'created_at' => time(),
        ]);
    }
}
