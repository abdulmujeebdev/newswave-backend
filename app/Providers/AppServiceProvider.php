<?php

namespace App\Providers;

use App\Interfaces\ArticleInterface;
use App\Interfaces\AuthInterface;
use App\Interfaces\NewsInterface;
use App\Repository\ArticleRepository;
use App\Repository\AuthRepository;
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
        $this->app->bind(ArticleInterface::class, ArticleRepository::class);
        $this->app->bind(AuthInterface::class, AuthRepository::class);
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
