<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\News;
use App\Models\SchoolProfile;
use App\Models\Admin;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Data Statistik
        $totalBerita = News::count();
        $totalGaleri = Gallery::count();
        
        // Data Berita Terbaru
        $recentBerita = News::with('kategori')
            ->latest()
            ->take(5)
            ->get();
            
        // Data Aktivitas Terbaru (contoh statis, bisa diganti dengan model Activity jika ada)
        $recentActivities = [
            [
                'icon' => 'user-plus',
                'color' => 'blue',
                'description' => 'Admin baru ditambahkan',
                'time' => 'Beberapa menit yang lalu'
            ],
            [
                'icon' => 'newspaper',
                'color' => 'green',
                'description' => 'Berita "Penerimaan Siswa Baru 2023" dipublikasikan',
                'time' => '1 jam yang lalu'
            ],
            [
                'icon' => 'images',
                'color' => 'purple',
                'description' => 'Galeri "Kegiatan Pramuka" ditambahkan',
                'time' => '3 jam yang lalu'
            ],
            [
                'icon' => 'user-edit',
                'color' => 'yellow',
                'description' => 'Profil sekolah diperbarui',
                'time' => 'Kemarin'
            ]
        ];

        return view('admin.dashboard', [
            'totalBerita' => $totalBerita,
            'totalGaleri' => $totalGaleri,
            'totalPengunjung' => 1248, // Contoh data, bisa diganti dengan data asli
            'recentBerita' => $recentBerita,
            'recentActivities' => $recentActivities,
            'schoolProfile' => SchoolProfile::first(),
            'activeAdmins' => Admin::where('is_active', true)->count()
        ]);
    }
}