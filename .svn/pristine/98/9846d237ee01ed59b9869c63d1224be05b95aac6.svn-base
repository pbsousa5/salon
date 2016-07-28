<?php

use Illuminate\Database\Seeder;
use App\Salon\FundRecord;

class FundRecordTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fund_records')->delete();
        
        FundRecord::create([
            'balance_fee' => 10000,
            'square_fee' => 100,
            'user_id' => 1,
            'identity' =>1,
        ]);
    }
}
