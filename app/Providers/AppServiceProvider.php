<?php

namespace App\Providers;

use Bavix\Wallet\WalletConfigure;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        if($this->app->environment('prod')) {
            URL::forceScheme('https');
        }
        Model::unguard();
        WalletConfigure::ignoreMigrations();
    }
}
