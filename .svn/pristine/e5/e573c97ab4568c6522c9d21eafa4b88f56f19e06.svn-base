<?php

use Illuminate\Database\Seeder;
use App\Salon\Consumer;

class ConsumerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('consumers')->delete();
        
        for ($i=0; $i<10; $i++) {
            Consumer::create([
                'account' => '1868333841'.$i,
                'mobile' => '1868333841'.$i,
                'password' => bcrypt('123456'),
                'invitation_code' => '12345'.$i,
            ]);
        }
    }
}
