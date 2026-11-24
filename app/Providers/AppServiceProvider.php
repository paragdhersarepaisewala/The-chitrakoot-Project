<?php

namespace App\Providers;

use App\Models\NavigationMenuItem;
use App\Models\OrganizationSetting;
use App\Models\PageSeoSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        if (
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (request()->header('x-forwarded-proto') === 'https') ||
            str_contains(request()->getHost(), 'trycloudflare.com')
        ) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('organization_settings')) {
                    $settings = OrganizationSetting::getSettings();
                    $view->with('orgSettings', $settings);
                    $view->with('siteFavicon', ! empty($settings->favicon_path) ? (str_starts_with($settings->favicon_path, 'http') ? $settings->favicon_path : asset($settings->favicon_path)) : asset('favicon.ico'));
                    $view->with('siteLogoUrl', ! empty($settings->logo_path) ? (str_starts_with($settings->logo_path, 'http') ? $settings->logo_path : asset($settings->logo_path)) : asset('assets/uploads/temple-lotus-logo.png'));
                    $view->with('siteDefaultOgImage', ! empty($settings->default_og_image) ? (str_starts_with($settings->default_og_image, 'http') ? $settings->default_og_image : asset($settings->default_og_image)) : asset('assets/uploads/temple-lotus-logo.png'));
                    $view->with('siteMetaDescription', $settings->meta_description);
                    $view->with('siteTagline', $settings->site_tagline);
                    $view->with('ga4Id', $settings->ga4_measurement_id);
                    $view->with('ga4Script', $settings->ga4_custom_script);
                    $view->with('googleSiteVerification', $settings->google_site_verification ?: env('GOOGLE_SITE_VERIFICATION'));
                }

                if (Schema::hasTable('navigation_menu_items')) {
                    $view->with('headerNavItems', NavigationMenuItem::getTree('header'));
                }

                if (Schema::hasTable('page_seo_settings')) {
                    $routeName = request()->route() ? request()->route()->getName() : null;
                    $routeMap = [
                        'home' => 'home',
                        'about' => 'about',
                        'leadership' => 'leadership',
                        'contact' => 'contact',
                        'seva.index' => 'seva',
                        'chitrakoot.index' => 'chitrakoot',
                        'events.index' => 'events',
                        'blog.index' => 'blog',
                        'gallery.index' => 'gallery',
                        'donate' => 'donate',
                        'pooja.index' => 'pooja',
                        'volunteer' => 'volunteer',
                        'privacy' => 'privacy',
                    ];
                    $pageKey = $routeMap[$routeName] ?? null;
                    $currentSeo = $pageKey ? PageSeoSetting::getForPage($pageKey) : null;
                    $view->with('currentSeo', $currentSeo);
                }
            } catch (\Throwable $e) {
                // Graceful fallback during migration runs or test setups
            }
        });
    }
}
