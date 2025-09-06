<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Admin;
use App\Models\SchoolProfile;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();
        
        // Statistik dasar
        $stats = [
            'total_galleries'   => Gallery::count(),
            'published_galleries' => Gallery::where('is_published', true)->count(),
        ];

        // Statistik khusus Super Admin
        if ($admin->role === 'super_admin') {
            $stats['total_admins'] = Admin::count();
            $stats['active_admins'] = Admin::where('is_active', true)->count();
        }

        // Untuk cards di atas
        $totalBerita    = News::count();
        $totalGaleri    = Gallery::count();
        $totalPengunjung = DB::table('visits')->whereMonth('created_at', now()->month)->count() ?? 0;

        // Data terbaru berdasarkan role
        if ($admin->role === 'super_admin') {
            // Super Admin melihat semua berita
            $recentBerita = News::latest()->take(3)->get();
            $recentActivities = [
                [
                    'description' => 'Super Admin login',
                    'icon' => 'crown',
                    'color' => 'red',
                    'time' => now()->diffForHumans(),
                ],
                [
                    'description' => 'Mengelola admin lain',
                    'icon' => 'users-cog',
                    'color' => 'purple',
                    'time' => now()->subMinutes(5)->diffForHumans(),
                ],
                [
                    'description' => 'Melihat statistik sistem',
                    'icon' => 'chart-bar',
                    'color' => 'blue',
                    'time' => now()->subMinutes(10)->diffForHumans(),
                ]
            ];
        } else {
            // Admin biasa hanya melihat berita yang mereka buat
            $recentBerita = News::where('admin_id', $admin->id)->latest()->take(3)->get();
            $recentActivities = [
                [
                    'description' => 'Admin login',
                    'icon' => 'sign-in-alt',
                    'color' => 'blue',
                    'time' => now()->diffForHumans(),
                ],
                [
                    'description' => 'Mengelola konten',
                    'icon' => 'edit',
                    'color' => 'green',
                    'time' => now()->subMinutes(5)->diffForHumans(),
                ],
                [
                    'description' => 'Update profil sekolah',
                    'icon' => 'school',
                    'color' => 'purple',
                    'time' => now()->subMinutes(15)->diffForHumans(),
                ]
            ];
        }

        // Data lainnya
        $recentNews      = News::latest()->take(5)->get();
        $recentGalleries = Gallery::latest()->take(6)->get();
        
        // Profil sekolah
        $schoolProfile = SchoolProfile::first();

        return view('admin.dashboard', compact(
            'stats',
            'totalBerita',
            'totalGaleri',
            'totalPengunjung',
            'recentNews',
            'recentGalleries',
            'recentBerita',
            'recentActivities',
            'schoolProfile'
        ));
    }
}
