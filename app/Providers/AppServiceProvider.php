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
        // Share schoolProfile to all views with error handling
        View::composer('*', function ($view) {
            try {
                $view->with('schoolProfile', SchoolProfile::getProfile());
            } catch (\Exception $e) {
                // Fallback to empty model if database query fails
                $view->with('schoolProfile', new SchoolProfile());
            }
        });

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
