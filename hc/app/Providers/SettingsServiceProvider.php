<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share common settings with all views
        view()->composer('*', function ($view) {
            $view->with([
                'siteName' => Setting::getValue('site_name', 'My Website'),
                'siteTitle' => Setting::getValue('site_title', 'Welcome to My Website'),
                'siteLogo' => Setting::getValue('site_logo', '/images/logo.png'),
                'siteFavicon' => Setting::getValue('site_favicon', '/images/favicon.ico'),
            ]);
        });
        
        // Update app name from settings
        config([
            'app.name' => Setting::getValue('site_name', 'My Website'),
        ]);
    }
}