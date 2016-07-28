<?php

use Illuminate\Database\Seeder;
use App\Salon\ProductCategory;

class ProductCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_categorys')->delete();
        
        ProductCategory::create([
            'name' => '洗剪吹',
            'describe' => '洗剪吹'
        ]);
        
        ProductCategory::create([
            'name' => '烫发',
            'describe' => '烫发'
        ]);
        
        ProductCategory::create([
            'name' => '染发',
            'describe' => '染发'
        ]);

        ProductCategory::create([
            'name' => '护发',
            'describe' => '护发'
        ]);

        ProductCategory::create([
            'name' => '套餐',
            'describe' => '套餐'
        ]);
    }
}
