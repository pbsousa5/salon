<?php

use Illuminate\Database\Seeder;
use App\Salon\FundAccount;

class FundAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fund_accounts')->delete();
        
        for ($i=1; $i<=10; $i++) {
            FundAccount::create([
                'user_id' => $i,
                'identity' => 1,
                'user_name' => '习主席',
                'card_number' => '77778888',
                'mobile' => '18683338411',
                'pay_code' => 'WEIXIN_DEBIT',
            ]);
        }
    }
}
