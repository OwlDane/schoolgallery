<?php

namespace App\Providers;

use App\Models\SchoolProfile;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; 

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
        // Share schoolProfile to all views with comprehensive error handling
        View::composer('*', function ($view) {
            try {
                $schoolProfile = SchoolProfile::getProfile();
                $view->with('schoolProfile', $schoolProfile);
            } catch (\Throwable $e) {
                // Fallback to empty model if any error occurs
                $view->with('schoolProfile', new SchoolProfile());
            }
        });

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
