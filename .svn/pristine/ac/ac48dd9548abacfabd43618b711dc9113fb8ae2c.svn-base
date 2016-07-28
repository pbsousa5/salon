<?php

use Illuminate\Database\Seeder;
use App\Salon\Device;

class DeviceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('devices')->delete();
        
        Device::create([
            'name' => 'Android',
            'key' => env('APP_KEY'),
            'status' => 1,
        ]);
        
        Device::create([
            'name' => 'IOS',
            'key' => env('APP_KEY'),
            'status' => 1,
        ]);
    }
}
