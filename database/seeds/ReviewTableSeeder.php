<?php

use Illuminate\Database\Seeder;
use App\Salon\Review;

class ReviewTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reviews')->delete();
        
        Review::create([
            'service_score' => 5,
            'skill_score' => 6,
            'env_score' => 4,
            'comment_txt' => '理发师明明可以靠脸吃饭，偏要靠技术',
            'comment_imgs' => '',
            'consumer_id' => 1,
            'product_id' => 1,
            'order_product_id' => 1,
            'supplier_id' => 1,
            'queue_time' => '30分钟内',
            'review_tag_ids' => '1,2,3',
            //'level_type' => 2,
            'created_at' => time(),
        ]);
    }
}
