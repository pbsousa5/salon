<?php

namespace App\Libary\Encryption;

use Illuminate\Support\ServiceProvider;
use App\Libary\Encryption\AesCrypt;


class AesServiceProvider extends ServiceProvider
{
    /**
     * (non-PHPdoc)
     * @see \Illuminate\Encryption\EncryptionServiceProvider::register()
     */
    public function register()
    {
        $this->app->singleton('App\Libary\Contracts\Encryption\EncrypterInterface', function($app){
            $config = $app->make('config')->get('app');
        
            $key = $config['key'];
            
            return new AesCrypt($key);
        });
    }
}