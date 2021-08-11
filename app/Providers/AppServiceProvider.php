<?php

namespace App\Providers;

use Adaojunior\PassportSocialGrant\SocialGrantUserProvider as SocialGrantUserProviderContract;
use App\Versions\V1\Services\Auth\OAuthManager;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('oauth', function (Application $app) {
            return new OAuthManager($app);
        });

        $this->app->bind(SocialGrantUserProviderContract::class, SocialGrantUserProvider::class);

        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
