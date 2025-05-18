<?php

namespace App\Providers\Filament;

use App\Filament\Resources\AdminResource\Widgets\AdminImageWidget;
use App\Filament\Resources\AdminResource\Widgets\ClientImageWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Http\Middleware\LanguageMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\UserMenuItem;
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

class ClientPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('client')
            ->path('client')
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
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Client/Resources'), for: 'App\\Filament\\Client\\Resources')
            ->discoverPages(in: app_path('Filament/Client/Pages'), for: 'App\\Filament\\Client\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Client/Widgets'), for: 'App\\Filament\\Client\\Widgets')
            ->widgets([
                AdminImageWidget::class,
                ClientImageWidget::class,
                StatsOverviewWidget::class
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
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(__('nav.dashboard'))
                    ->items([
                        NavigationItem::make(__('nav.visits'))
                            ->url('/visits')
                            ->icon('heroicon-o-calendar'),
                        NavigationItem::make(__('nav.client_requests'))
                            ->url('/client_requests')
                            ->icon('heroicon-o-users')
                    ]),
            ])
            ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop();

    }




}
