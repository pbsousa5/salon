<?php

use Illuminate\Database\Seeder;
use App\Salon\Feedback;

class FeedbackTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('feedbacks')->delete();
        
        Feedback::create([
            'mobile' => '18683338411',
            'feedback_txt' => '不断进步',
            'feedback_imgs' => '',
        ]);
    }
}
