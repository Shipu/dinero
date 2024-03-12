<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Tenancy\EditAccountProfile;
use App\Filament\Pages\Tenancy\RegisterAccount;
use App\Models\Account;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Http\Middleware\IdentifyTenant;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('hub')
            ->login(Login::class)
            ->colors([
                'primary' => Color::Sky,
            ])
            ->sidebarWidth('17rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
//                Widgets\AccountWidget::class,
//                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                IdentifyTenant::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->profile()
            ->sidebarCollapsibleOnDesktop()
            ->plugins(
                [
                    FilamentApexChartsPlugin::make(),
                    BreezyCore::make()
                        ->myProfile(hasAvatars: true)
                        ->enableTwoFactorAuthentication()
                ]
            )
            ->tenant(model: Account::class, slugAttribute: 'id', ownershipRelationship: 'owner')
            ->tenantRegistration(RegisterAccount::class)
            ->tenantProfile(EditAccountProfile::class)
            ->renderHook( 'panels::content.start', function () {
                if(config('app.demo')) {
                    return view('banner');
                }
                return null;
            })
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s');
    }
}
