<?php

use Illuminate\Database\Seeder;
use App\Salon\Supplier;

class SupplierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('suppliers')->delete();
        
        for ($i=0; $i<10; $i++) {
            Supplier::create([
                'supplier_manager_id' => 1,
                'account' => '1868333842'.$i,
                'mobile' => '1868333842'.$i,
                'password' => bcrypt('123456'),
                'name' => '中华高级理发店'.$i,
                'staff_count' => 100,
                'business_time' => serialize(['morning_time'=>'09:00', 'night_time'=>'22:00']),
                'phones' => serialize(['45531136', '18683338412'.$i]),
                'gallerys' => '',
                'legal_name' => '大法师'.$i,
                'id_num' => '36546987563256489'.$i,
                'id_back_photo' => '',
                'id_front_photo' => '',
                'license_photo' => '',
                'basic_discount' => '',
                'status' => 1,
            ]);
        }
    }
}
