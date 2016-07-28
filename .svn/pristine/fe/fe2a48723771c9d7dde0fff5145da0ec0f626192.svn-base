<?php

use Illuminate\Database\Seeder;
use App\Salon\SupplierCache;

class SupplierCacheTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('supplier_caches')->delete();
        
        for ($i=1; $i<=10; $i++) {
            SupplierCache::create([
                'supplier_id' => $i,
                'reviews' => serialize(['s_score'=>5, 'k_score'=>5, 'e_score'=>6]),
                'avg_score' => 10+$i,
                'lower_price' => 1000,
                'hot_product_ids' => serialize([1, 2]),
                'busy_index' => 80+$i,
                'followers' => 20,
                'tags' => serialize(['给力啦'=>20, '把我弄得像小龙女'=>100]),
            ]);
        }
    }
}
