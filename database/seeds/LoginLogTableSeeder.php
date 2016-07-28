<?php

use Illuminate\Database\Seeder;
use App\Salon\LoginLog;

class LoginLogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('login_logs')->delete();
        
        LoginLog::create([
           'mobile' => '18683338411',
           'user_id' => 1,
           'identity' => 1,
           'source' => 1,
           'login_ip' => '192.168.1.8',
           'logined_at' => date('Y-m-d H:i:s', time()), 
        ]);
    }
}
