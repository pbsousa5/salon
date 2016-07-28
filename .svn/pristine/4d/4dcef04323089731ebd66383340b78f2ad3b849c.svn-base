<?php

use Illuminate\Database\Seeder;
use App\Salon\Notify;

class NotifyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notifys')->delete();
        
        Notify::create([
           'user_id' => 11,
           'user_type' => 'consumer',
           'title' => '新订单通知',
           'push_msg' => '有增加了一个订单，点击查看详情',
           'notify_type' => 2, 
        ]);
    }
}
