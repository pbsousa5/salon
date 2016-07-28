<?php

use Illuminate\Database\Seeder;
use App\Salon\WithdrawCashLog;

class WithdrawCashLogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('withdraw_cash_logs')->delete();
        
        WithdrawCashLog::create([
            'supplier_id' => 1,
            'fund_account_id' => 1,
            'cash_fee' => 1000,
            'user_name' => '习主席',
            'card_number' => '77778888',
            'pay_code' => 'ALiPAY_DEBIT',
            'created_at' => date('Y-m-d H:i:s',time()),
        ]);
    }
}
