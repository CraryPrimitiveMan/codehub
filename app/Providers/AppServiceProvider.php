<?php

namespace App\Providers;

use TalvBansal\MediaManager\Services\MediaManager as BaseMediaManager;
use App\Extensions\MediaManager\Services\MediaManager;
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
//        $this->app->singleton(BaseMediaManager::class, function ($app) {
//            return $this->app->make(MediaManager::class);
//        });
    }
}
