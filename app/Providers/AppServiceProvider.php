<?php

namespace App\Providers;

use App\Interfaces\NewsInterface;
use App\Services\NewsApiService;
use App\Services\NewYorkTimesApiService;
use App\Services\TheGuardiansApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(NewsInterface::class, NewsApiService::class);
        $this->app->bind(NewsInterface::class, NewYorkTimesApiService::class);
        $this->app->bind(NewsInterface::class, TheGuardiansApiService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
