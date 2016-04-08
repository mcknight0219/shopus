<?php

namespace App\Providers;

use GuzzleHttp\Client;
use App\Wechat\QrService;
use App\Wechat\UserService;
use App\Wechat\AssetService;
use App\Wechat\WechatHttpService;
use App\Wechat\CustomMessageService;
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
        $this->app->singleton('Api', function () {
            return new WechatHttpService(new Client(['base_uri' => 'https://api.weixin.qq.com/cgi-bin/']));
        });

        // Store's api is inconveniently different
        $this->app->singleton('StoreApi', function () {
            return new WechatHttpService(new Client(['base_uri' => 'https://api.weixin.qq.com/']));
        });

        $this->app->singleton('MessageService', function () {
            return new CustomMessageService($this->app->make('Api'));
        });

        $this->app->singleton('AssetService', function () {
            return new AssetService($this->app->make('Api'));
        });

        $this->app->singleton('UserService', function () {
           return new UserService($this->app->make('Api'));
        });

        $this->app->singleton('QrService', function () {
            return new QrService($this->app->make('Api'));
        });
    }
}
