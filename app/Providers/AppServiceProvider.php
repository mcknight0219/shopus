<?php

namespace App\Providers;

use App\Wechat\AssetService;
use App\Wechat\CustomMessageService;
use App\Wechat\UserService;
use App\Wechat\WechatHttpService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Api', function ($app) {
            return new WechatHttpService(new Client(['base_uri' => 'https://api.weixin.qq.com/cgi-bin/']));
        });

        // Store's api is inconveniently different
        $this->app->singleton('StoreApi', function ($app) {
            return new WechatHttpService(new Client(['base_uri' => 'https://api.weixin.qq.com/']));
        });

        $this->app->singleton('CustomMessageService', function ($app) {
            return new CustomMessageService($this->app->make('Api'));
        });

        $this->app->singleton('AssetService', function ($app) {
            return new AssetService($this->app->make('Api'));
        });

        $this->app->singleton('UserService', function ($app) {
           return new UserService($this->app->make('Api'));
        });
    }
}
