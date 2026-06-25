<?php

namespace App\Providers\Filament;

use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->spa()
            ->login()
            ->passwordReset()
            ->brandName('Portal Aspirasi Mangihut Sinaga')
            ->profile(\App\Filament\Pages\Auth\EditProfile::class, isSimple: false)
            ->defaultThemeMode(ThemeMode::Light)
            ->font('Montserrat')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn () => Blade::render('
                    <div class="mb-4 text-center">
                        <a href="/" class="text-sm font-semibold text-amber-600 hover:text-amber-700">
                            ← Kembali ke Beranda
                        </a>
                    </div>
                ')
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn () => Blade::render('
                    <div class="mt-6 text-center text-xs text-gray-500">
                        <p>Akses terbatas untuk staf, tenaga ahli, dan anggota dewan.</p>
                        <p class="mt-1 font-semibold">Powered by SIMALEX v1.0</p>
                        <p class="mt-1">Sistem Informasi Manajemen Aspirasi Legislatif</p>
                    </div>
                ')
            )
            ->maxContentWidth(MaxWidth::SevenExtraLarge)
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(
                in: app_path('Filament/Admin/Resources'),
                for: 'App\\Filament\\Admin\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Admin/Pages'),
                for: 'App\\Filament\\Admin\\Pages'
            )
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverClusters(
                in: app_path('Filament/Admin/Clusters'),
                for: 'App\\Filament\\Admin\\Clusters'
            )
            ->discoverWidgets(
                in: app_path('Filament/Admin/Widgets'),
                for: 'App\\Filament\\Admin\\Widgets'
            )
            ->widgets([
                // \Awcodes\Overlook\Widgets\OverlookWidget::class,
                \App\Filament\Admin\Widgets\StatistikAspirasi::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                   ->label('Operasional'),
                NavigationGroup::make()
                    ->label('Administration'),
                NavigationGroup::make()
                    ->label('Manajemen Website'),
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn () => auth()->user()->name)
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle'),
            ])
            ->plugins([
                \Hasnayeen\Themes\ThemesPlugin::make(),
                \Njxqlus\FilamentProgressbar\FilamentProgressbarPlugin::make()->color('#f59e0b'),

                \Awcodes\Overlook\OverlookPlugin::make()
                    ->includes([
                        \App\Filament\Admin\Resources\UserResource::class,
                    ]),

                \Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->setTitle('My Profile')
                    ->shouldRegisterNavigation(false)
                    ->shouldShowDeleteAccountForm(false)
                    ->shouldShowSanctumTokens(false)
                    ->shouldShowBrowserSessionsForm()
                    ->shouldShowAvatarForm(),
            ])
            ->resources([
                config('filament-logger.activity_resource'),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
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
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}