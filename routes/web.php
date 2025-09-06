<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\SchoolProfileController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/gallery/category/{category}', [HomeController::class, 'galleryByCategory'])->name('gallery.category');
Route::get('/gallery/{id}', [HomeController::class, 'galleryDetail'])->name('gallery.detail');
Route::get('/gallery/download/{id}', [HomeController::class, 'download'])->name('gallery.download');
Route::get('/news', [HomeController::class, 'news'])->name('news');
Route::get('/news/{slug}', [HomeController::class, 'newsDetail'])->name('news.detail');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Alias login untuk default Laravel
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest (Belum login)
    Route::middleware('guest:admin')->group(function () {
        // Login
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
       
        // Forgot + Reset Password
        Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    });

    // Authenticated (Sudah login)
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Statistics Detail (Super Admin Only)
        Route::middleware('admin.role:super_admin')->group(function () {
            Route::get('/statistics/{type}', [DashboardController::class, 'statisticsDetail'])->name('statistics.detail');
        });

        // Gallery Management
        Route::get('galleries', [GalleryController::class, 'index'])->name('galleries.index');
        Route::get('galleries/kategori/{kategoriSlug?}', [GalleryController::class, 'index'])->name('galleries.kategori');
        Route::get('galleries/create', [GalleryController::class, 'create'])->name('galleries.create');
        Route::post('galleries', [GalleryController::class, 'store'])->name('galleries.store');
        Route::get('galleries/{gallery}/edit', [GalleryController::class, 'edit'])->name('galleries.edit');
        Route::put('galleries/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
        Route::delete('galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');
        Route::patch('galleries/{gallery}/toggle-publish', [GalleryController::class, 'togglePublish'])->name('galleries.toggle-publish');
        Route::delete('galleries/{gallery}/remove-image', [GalleryController::class, 'removeImage'])->name('galleries.remove-image');

        // News Management
        Route::resource('news', NewsController::class);
        Route::patch('news/{news}/toggle-publish', [NewsController::class, 'togglePublish'])->name('news.toggle-publish');
        Route::delete('news/{news}/remove-image', [NewsController::class, 'removeImage'])->name('news.remove-image');

        // School Profile Management (Super Admin Only)
        Route::middleware('admin.role:super_admin')->group(function () {
            Route::get('/school-profile', [SchoolProfileController::class, 'edit'])->name('school-profile.edit');
            Route::put('/school-profile', [SchoolProfileController::class, 'update'])->name('school-profile.update');
        });

        // Admin Management (Super Admin Only)
        Route::middleware('admin.role:super_admin')->group(function () {
            Route::resource('admins', AdminManagementController::class);
            Route::post('admins/{admin}/reset-password', [AdminManagementController::class, 'resetPassword'])->name('admins.reset-password');
        });
    });

});
