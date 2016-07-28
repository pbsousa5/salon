<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Salon\Services\QiniuTokenService;

/**
 * 
 * 
 * @desc 七牛的相关服务提供
 * @author helei <helei@bnersoft.com>
 * @date 2015年7月28日
 */
class QiniuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTokenManager();
    }
    
    /**
     * 
     * 注册token管理
     */
    protected function registerTokenManager()
    {
        $this->app->bind('qiniuToken', function($app){
            return new QiniuTokenService();
        });
    }
}
