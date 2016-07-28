<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Libary\Http\JsonResponse;
use App\Libary\Push\Sms\PusherSMS;
use App\Libary\Push\Sms\RestSDK;
use App\Libary\Push\Sms\CCPRestSDK;
use App\Salon\Services\ConsumerService;
use Validator;
use App\Salon\Extensions\SalonValidator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*注册自定义验证类*/
        /* Validator::resolver(function($translator, $data, $rules, $messages){
            return new SalonValidator($translator, $data, $rules, $messages);
        }); */
        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages){
            return new SalonValidator($translator, $data, $rules, $messages);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAppResponse();
        $this->registerPushSms();
    }
    
    /**
     * 
     * 注册一个app格式化数据的响应者
     */
    protected function registerAppResponse()
    {
        $this->app->bind('App\Libary\Contracts\Http\ResponseInterface', 'App\Libary\Http\JsonResponse');
        $this->app->bind('appResp', function($app){
            return new JsonResponse();
        });
    }
    
    /**
     * 
     * 注册短信推送
     */
    protected function registerPushSms()
    {
        $this->app->bind('App\Libary\Contracts\Push\PusherInterface', function($app){
            $config = $app['config']['sms'];
            $rest = new CCPRestSDK($config['serverIP'], $config['serverPort'], $config['softVersion']);
            //$rest = new RestSDK($config['serverIP'], $config['serverPort'], $config['softVersion']);
            $rest->setAccount($config['accountSid'], $config['accountToken']);
            $rest->setAppId($config['appId']);
            $rest->setEnabeLog($config['enabeLog']);
            $rest->setTempId(config('tempId'));
            
            return new PusherSMS($rest);
        });
    }
    
    /**
     *
     * 注册消费者服务
     */
    protected function registerConsumer()
    {
        $this->app->bind('consumer', function($app){
            return new ConsumerService();
        });
    }
}
