<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\SchoolProfileController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\EventController as PublicEventController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Guest\AuthController as GuestAuthController;
use App\Http\Controllers\Guest\InteractionController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\SitemapController;

// Public Routes (tracked visits)
Route::middleware('track.visits')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
    Route::get('/gallery/category/{category}', [HomeController::class, 'galleryByCategory'])->name('gallery.category');
    Route::get('/gallery/{id}', [HomeController::class, 'galleryDetail'])->name('gallery.detail');
    Route::get('/gallery/download/{id}', [HomeController::class, 'download'])->name('gallery.download');
    Route::get('/news', [HomeController::class, 'news'])->name('news');
    Route::get('/news/{slug}', [HomeController::class, 'newsDetail'])->name('news.detail');
    Route::get('/events', [PublicEventController::class, 'index'])->name('events.index'); // Tambahkan route ini
    Route::get('/events/{slug}', [PublicEventController::class, 'show'])->name('events.show');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/teachers', [HomeController::class, 'teachers'])->name('teachers');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
});

// Guest Interaction Routes (no login required)
Route::middleware('track.visits')->group(function () {
    // Gallery interactions - comments can be viewed by everyone
    Route::get('/gallery/{id}/comments', [InteractionController::class, 'getComments'])->name('gallery.comments');
    
    // News interactions - comments can be viewed by everyone
    Route::get('/news/{id}/comments', [InteractionController::class, 'getNewsComments'])->name('news.comments');
    
    // Contact form for guests
    Route::post('/contact', [InteractionController::class, 'sendContact'])->name('contact.submit');
    
    // Photo submission for guests
    Route::post('/submit-photo', [InteractionController::class, 'submitPhoto'])->name('photo.submit');
});

// Authenticated User Interaction Routes (login required)
Route::middleware(['auth', 'track.visits'])->group(function () {
    // Gallery interactions for authenticated users
    Route::post('/gallery/{id}/like', [InteractionController::class, 'toggleLike'])->name('gallery.like');
    Route::post('/gallery/{id}/comment', [InteractionController::class, 'addComment'])->name('gallery.comment');
    Route::get('/gallery/{id}/like-status', [InteractionController::class, 'checkLikeStatus'])->name('gallery.like-status');
    
    // News interactions for authenticated users
    Route::post('/news/{id}/comment', [InteractionController::class, 'addNewsComment'])->name('news.comment');
    // Bookmark endpoints removed per request
});

// Protected Routes (require login)
Route::middleware(['auth', 'track.visits'])->group(function () {
    // News interactions
    Route::post('/news/{slug}/comment', [HomeController::class, 'commentNews'])->name('news.comment');
    
    // Contact form submission
    Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');

    // User profile
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
});

// Guest Authentication Routes
Route::prefix('guest')->name('guest.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [GuestAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [GuestAuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [GuestAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [GuestAuthController::class, 'register'])->name('register.submit');
    });
    
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [GuestAuthController::class, 'logout'])->name('logout');
    });
});

// Alias login untuk default Laravel (redirect ke guest login)
Route::get('/login', function () {
    return redirect()->route('guest.login');
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

        // Gallery Comments moderation
        Route::delete('galleries/comments/{comment}', function(App\Models\GalleryComment $comment){
            $comment->delete();
            return back()->with('success', 'Komentar dihapus');
        })->name('galleries.comments.destroy');

        // News Management
        Route::resource('news', NewsController::class);
        Route::patch('news/{news}/toggle-publish', [NewsController::class, 'togglePublish'])->name('news.toggle-publish');
        Route::delete('news/{news}/remove-image', [NewsController::class, 'removeImage'])->name('news.remove-image');

        // Events Management
        Route::resource('events', EventsController::class)->except(['show']);
        Route::patch('events/{event}/toggle-publish', [EventsController::class, 'togglePublish'])->name('events.toggle-publish');

        // School Profile Management (Super Admin Only)
        Route::middleware('admin.role:super_admin')->group(function () {
            Route::get('/school-profile', [SchoolProfileController::class, 'edit'])->name('school-profile.edit');
            Route::put('/school-profile', [SchoolProfileController::class, 'update'])->name('school-profile.update');
        });

        // Admin Management (Super Admin Only)
        Route::middleware('admin.role:super_admin')->group(function () {
            Route::resource('admins', AdminManagementController::class);
            Route::post('admins/{admin}/reset-password', [AdminManagementController::class, 'resetPassword'])->name('admins.reset-password');
            Route::patch('admins/{admin}/toggle-active', [AdminManagementController::class, 'toggleActive'])->name('admins.toggle-active');
        });

        // Teacher Management
        Route::resource('teachers', TeacherController::class);

        // Reports & Export
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('reports/export-visitor-stats', [ReportController::class, 'exportVisitorStats'])->name('reports.export-visitor-stats');
        Route::get('reports/export-visitor-stats', [ReportController::class, 'exportVisitorStats'])->name('reports.export-visitor-stats.quick');
        Route::post('reports/export-content-stats', [ReportController::class, 'exportContentStats'])->name('reports.export-content-stats');
        Route::get('reports/export-content-stats', [ReportController::class, 'exportContentStats'])->name('reports.export-content-stats.quick');
        Route::post('reports/export-admin-activity', [ReportController::class, 'exportAdminActivity'])->name('reports.export-admin-activity');
        Route::get('reports/export-admin-activity', [ReportController::class, 'exportAdminActivity'])->name('reports.export-admin-activity.quick');

        // Activity logs (read-only)
        Route::get('activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity.index');
    });

});

// Chatbot endpoint
Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
