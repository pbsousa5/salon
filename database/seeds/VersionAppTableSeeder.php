<?php

use Illuminate\Database\Seeder;
use App\Salon\VersionApp;

class VersionAppTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('version_apps')->delete();
        
        VersionApp::create([
            'device_id' => 1,
            'version_code' => 'v1.0.0',
            'version_id' => 1,
            'upgrade_point' => '升级加强版',
            'package_url' => 'http://www.baidu.com/',
        ]);
    }
}
