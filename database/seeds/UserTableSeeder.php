<?php

use Illuminate\Database\Seeder;
use App\Activity\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        
         for ($i=1; $i < 10; $i++) {
          User::create([
            'nick_name'   => 'NickName '.$i,
            'mobile'    => '1872849503'.$i,
            'price'    => '998 '.$i,
          ]);
        }
    }
}
