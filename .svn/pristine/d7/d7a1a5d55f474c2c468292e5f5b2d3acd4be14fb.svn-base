<?php

use Illuminate\Database\Seeder;
use App\Salon\Barber;

class BarberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('barbers')->delete();

        Barber::create([
            'supplier_id' => 1,
            'account' => '18683333840',
            'mobile' => '18683333840',
            'password' => bcrypt('123456'),
            'nickname' => 'Jack',
            'realname' => '周力',
            'head_img' => 'IOS187800514751438671698.png',
            'gender' => 1,
            'age' => 23,
            'email' => 'zhouli@bnersoft.com',
            'work_life' => 2,
        ]);
    }
}