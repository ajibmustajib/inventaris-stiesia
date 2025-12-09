<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Pages\Auth\Login;
use Filament\Navigation\NavigationGroup;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Tables\Table;
use Filament\Support\Facades\FilamentView;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->maxContentWidth('full')
            ->login()
            ->sidebarCollapsibleOnDesktop()
            ->collapsedSidebarWidth('9rem')
            ->breadcrumbs(true)
            ->spa()
            ->font('Poppins')
            ->topNavigation()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                \App\Filament\Admin\Widgets\OverviewStats::class,
                \App\Filament\Admin\Widgets\AssetChart::class,
                
            ])
            ->navigationGroups([
            NavigationGroup::make()
                ->label('Master')
                ->icon('heroicon-o-cog-6-tooth')
            ])
            ->renderHook(
            'panels::page.end',
            fn () => '
                <div style="
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    width: 100%;
                    padding: 8px 0;
                    text-align: center;
                    font-size: 12px;
                    color: black;
                    background: rgba(255,255,255,0.8);
                    backdrop-filter: blur(6px);
                    z-index: 50;
                ">
                    © ' . date('Y') . ' LPDE. All rights reserved.
                </div>
            '
            )
            ->discoverWidgets(
                in: app_path('Filament/Admin/Widgets'),
                for: 'App\\Filament\\Admin\\Widgets'
            )
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // ->widgets([
            //     Widgets\AccountWidget::class,
            //     Widgets\FilamentInfoWidget::class,
            // ])
            ->default();
    }


    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'tables::table.start',
            fn () => '<div class="overflow-x-auto max-w-full">'
        );

        FilamentView::registerRenderHook(
            'tables::table.end',
            fn () => '</div>'
        );
    }

}
