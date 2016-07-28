<?php

use Illuminate\Database\Seeder;
use App\Salon\SupplierManager;

class SupplierManagerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('supplier_managers')->delete();
        
        SupplierManager::create([
            'mobile' => '18683338411',
            'password' => bcrypt('123456'),
            'company_name' => '大成都理发店',
            'legal_name' => '习主席',
            'id_num' => '7899455142555655',
            'id_back_photo' => '',
            'id_front_photo' => '',
            'license_photo' => '',
        ]);
    }
}
