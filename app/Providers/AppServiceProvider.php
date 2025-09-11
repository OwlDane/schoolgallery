<?php

namespace App\Providers;

use App\Models\SchoolProfile;
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
        // Share schoolProfile to all views
        View::composer('*', function ($view) {
            $view->with('schoolProfile', SchoolProfile::getProfile());
        });
    }
}
