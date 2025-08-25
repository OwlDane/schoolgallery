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
        // Statistik
        $stats = [
            'total_galleries'   => Gallery::count(),
            'published_galleries' => Gallery::where('is_published', true)->count(),
            'total_admins'      => Admin::count(),
            'active_admins'     => Admin::where('is_active', true)->count(),
        ];

        // Untuk cards di atas
        $totalBerita    = News::count();
        $totalGaleri    = Gallery::count();
        $totalPengunjung = DB::table('visits')->whereMonth('created_at', now()->month)->count() ?? 0;

        // Data terbaru
        $recentNews      = News::latest()->take(5)->get();
        $recentGalleries = Gallery::latest()->take(6)->get();
        $recentBerita    = News::latest()->take(3)->get(); // buat section "Berita Terbaru"
        
        // Dummy aktivitas (atau ambil dari tabel activity_log kalau ada)
        $recentActivities = [
            [
                'description' => 'Admin login',
                'icon' => 'sign-in-alt',
                'color' => 'blue',
                'time' => now()->diffForHumans(),
            ]
        ];

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
