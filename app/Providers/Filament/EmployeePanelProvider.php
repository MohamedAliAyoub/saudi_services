<?php

namespace App\Providers\Filament;

use App\Filament\Employee\Widgets\EmployeeVisitsWidget;
use App\Filament\Resources\AdminResource\Widgets\AdminImageWidget;
use App\Filament\Resources\AdminResource\Widgets\ClientImageWidget;
use App\Http\Middleware\LanguageMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\UserMenuItem;
use Filament\Notifications\Livewire\DatabaseNotifications;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class EmployeePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('employee')
            ->path('employee')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->login()
            ->userMenuItems([
                UserMenuItem::make()
                    ->label(__('English'))
                    ->url(fn() => route('change-language', ['lang' => 'en']))
                    ->icon('heroicon-o-language'),
                UserMenuItem::make()
                    ->label(__('اللغة العربية'))
                    ->url(fn() => route('change-language', ['lang' => 'ar']))
                    ->icon('heroicon-o-globe-asia-australia'),
            ])
            ->discoverResources(in: app_path('Filament/Employee/Resources'), for: 'App\\Filament\\Employee\\Resources')
            ->discoverPages(in: app_path('Filament/Employee/Pages'), for: 'App\\Filament\\Employee\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Employee/Widgets'), for: 'App\\Filament\\Employee\\Widgets')
            ->widgets([
                AdminImageWidget::class,
                ClientImageWidget::class,
                EmployeeVisitsWidget::class,

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
                LanguageMiddleware::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop();
        DatabaseNotifications::trigger('filament.notifications.database-notifications-trigger');
    }
}
