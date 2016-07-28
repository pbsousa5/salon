<?php

use Illuminate\Database\Seeder;
use App\Salon\PaymentType;

class PaymentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_types')->delete();
        
        PaymentType::create([
            'pay_code' => 'WEIXIN_DEBIT',
            'pay_name' => '微信支付',
            'pay_desc' => '服务由深圳腾讯提供',
            'is_enable' => 1,
            'pay_configs' => '',
        ]);
        
        PaymentType::create([
            'pay_code' => 'ALiPAY_DEBIT',
            'pay_name' => '支付宝支付',
            'pay_desc' => '服务由阿里巴巴提供',
            'is_enable' => 1,
            'pay_configs' => '',
        ]);
        
        PaymentType::create([
            'pay_code' => 'FREE_DEBIT',
            'pay_name' => '免费订单',
            'pay_desc' => '无需付款的订单',
            'is_enable' => 1,
            'pay_configs' => '',
        ]);
        
        PaymentType::create([
            'pay_code' => 'DEBUG_DEBIT',
            'pay_name' => '测试订单异步通知',
            'pay_desc' => '平台自我测试时使用',
            'is_enable' => 1,
            'pay_configs' => '',
        ]);
        
        // 银行代码，参考：https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=4_2
        /* PaymentType::create([
            'pay_code' => 'ICBC_DEBIT',
            'pay_name' => '工商银行',
            'pay_desc' => '借记卡',
            'is_enable' => 0,
            'pay_configs' => '',
        ]);
        
        PaymentType::create([
            'pay_code' => 'ICBC_CREDIT',
            'pay_name' => '工商银行',
            'pay_desc' => '信用卡',
            'is_enable' => 0,
            'pay_configs' => '',
        ]); */
    }
}
