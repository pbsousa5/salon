<?php

use Illuminate\Database\Seeder;
use App\Salon\Banner;

class BannerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banners')->delete();
        
        for ($i=1; $i<4; $i++) {
            Banner::create([
                'title' => '好文章',
                'extra' => json_encode(['author'=>'何磊', 'date'=>'2015-7-28']),
                'img_url' => "top_hd{$i}.jpg",
                'banner_info' => '<p>爱护你的身材，当你失去了，不然再找回来会是一个很痛苦的过程!</p><p>记住：借口和懒惰不会帮助你消耗卡路里。</p><p><img src="http://7xjwop.com2.z0.glb.qiniucdn.com/top_hd1.jpg" title="1433146484468278.jpg" alt="640.webp (1).jpg"></p><p>25岁前的相貌，是父母给的，25岁后的相貌，是自己修的。</p><p><img src="http://7xjwop.com2.z0.glb.qiniucdn.com/top_hd2.jpg" title="1433146495851726.jpg" alt="640.webp (2).jpg"></p><p>世界上没有垃圾，只有放错地方的宝藏。</p><p><img src="http://7xjwop.com2.z0.glb.qiniucdn.com/top_hd3.jpg" title="1433146526980430.jpg" alt="640.webp (3).jpg"></p>',
            ]);
        }
    }
}
