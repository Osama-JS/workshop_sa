<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (file_exists(app_path('helpers.php'))) {
            require_once app_path('helpers.php');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-ensure public storage directories exist with write permissions
        $publicDirs = [
            storage_path('app/public'),
            storage_path('app/public/settings'),
            storage_path('app/public/services'),
            storage_path('app/public/portfolios'),
            storage_path('app/public/about'),
            storage_path('app/public/hero_slides'),
            storage_path('app/public/orders'),
            storage_path('app/public/ai_ideas'),
            storage_path('app/public/ai_chat'),
        ];

        foreach ($publicDirs as $dir) {
            if (!file_exists($dir)) {
                @mkdir($dir, 0775, true);
            }
        }
    }
}
