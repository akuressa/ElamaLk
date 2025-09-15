<?php

namespace App\Providers;

use App\Providers\AppConfig;
use Illuminate\Support\ServiceProvider;

class AppConfigProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Providers\AppConfig', function ($app) {
            return new AppConfig();
          });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
