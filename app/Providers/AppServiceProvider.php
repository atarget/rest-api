<?php

namespace App\Providers;




use App\Api\Service\Implementation\DefaultFindQueryBuilder;
use App\Api\Service\Implementation\LangService;
use App\Api\Service\Implementation\TranslationService;
use App\Api\Service\Implementation\UserService;
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
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        $this->app->bind("FindQueryBuilder", DefaultFindQueryBuilder::class);
        $this->app->bind("service.user", UserService::class);
        $this->app->bind("service.translation", TranslationService::class);
        $this->app->bind("service.lang", LangService::class);
    }
}
