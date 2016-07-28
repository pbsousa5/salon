<?php

use Illuminate\Database\Seeder;
use App\Salon\Address;
use App\Libary\Util\Geohash;

class AddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresss')->delete();
        $g = new Geohash();
        // 116.443363,39.909843
        for ($i=1; $i<=10; $i++) {
            $lat = bcadd(39.909843, $i, 6);
            $long = bcadd(116.443363, $i, 6);
            Address::create([
                'user_id' => $i,
                'user_type' => 'App\Salon\Supplier',
                'distance' => $g->encode($lat, $long),
                'longitude' => (string) $long,
                'latitude' => (string) $lat,
                'province' => '四川',
                'city' => '成都',
                'district' => '高新区',
                'detail' => '希顿国际广场C座120'.$i,
            ]);
        }
    }
}
