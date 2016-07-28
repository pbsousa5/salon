<?php

use Illuminate\Database\Seeder;
use App\Salon\ReviewTag;

class ReviewTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('review_tags')->delete();
        
        ReviewTag::create([
            'name' => '给力啦',
        ]);
        
        ReviewTag::create([
            'name' => '把我弄得像小龙女',
        ]);
        
        ReviewTag::create([
            'name' => '这是杀马特吗',
        ]);
        
        ReviewTag::create([
            'name' => '本来靠脸吃饭的',
        ]);
    }
}
