<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Dropbox\Provider as DropboxProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Socialite::extend('dropbox', function ($app) {
            $config = $app['config']['services.dropbox'];
            return Socialite::buildProvider(DropboxProvider::class, $config);
        });
    }
}
