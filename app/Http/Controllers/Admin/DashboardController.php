<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Admin;
use App\Models\SchoolProfile;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();
        
        // Statistik dasar dengan detail
        $stats = [
            'total_galleries'   => Gallery::count(),
            'published_galleries' => Gallery::where('is_published', true)->count(),
            'draft_galleries' => Gallery::where('is_published', false)->count(),
            'total_news' => News::count(),
            'published_news' => News::where('is_published', true)->count(),
            'draft_news' => News::where('is_published', false)->count(),
        ];

        // Statistik khusus Super Admin
        if ($admin->role === 'super_admin') {
            $stats['total_admins'] = Admin::count();
            $stats['active_admins'] = Admin::where('is_active', true)->count();
            $stats['inactive_admins'] = Admin::where('is_active', false)->count();
            $stats['super_admins'] = Admin::where('role', 'super_admin')->count();
            $stats['regular_admins'] = Admin::where('role', 'admin')->count();
        }

        // Statistik kunjungan dengan detail
        $totalPengunjung = DB::table('visits')->whereMonth('created_at', now()->month)->count() ?? 0;
        $totalPengunjungBulanLalu = DB::table('visits')->whereMonth('created_at', now()->subMonth()->month)->count() ?? 0;
        $persentaseKunjungan = $totalPengunjungBulanLalu > 0 ? 
            round((($totalPengunjung - $totalPengunjungBulanLalu) / $totalPengunjungBulanLalu) * 100, 1) : 0;

        // Statistik berita dengan detail
        $totalBerita = News::count();
        $totalBeritaBulanLalu = News::whereMonth('created_at', now()->subMonth()->month)->count();
        $persentaseBerita = $totalBeritaBulanLalu > 0 ? 
            round((($totalBerita - $totalBeritaBulanLalu) / $totalBeritaBulanLalu) * 100, 1) : 0;

        // Statistik galeri dengan detail
        $totalGaleri = Gallery::count();
        $totalGaleriBulanLalu = Gallery::whereMonth('created_at', now()->subMonth()->month)->count();
        $persentaseGaleri = $totalGaleriBulanLalu > 0 ? 
            round((($totalGaleri - $totalGaleriBulanLalu) / $totalGaleriBulanLalu) * 100, 1) : 0;

        // Data terbaru berdasarkan role
        if ($admin->role === 'super_admin') {
            // Super Admin melihat semua berita
            $recentBerita = News::with('admin', 'newsCategory')->latest()->take(3)->get();
            $recentActivities = $this->getRecentActivities();
        } else {
            $recentBerita = News::with('admin', 'newsCategory')->where('admin_id', $admin->id)->latest()->take(3)->get();
            $recentActivities = $this->getRecentActivities($admin->id);
        }

        // Data lainnya
        $recentNews = News::with('admin', 'newsCategory')->latest()->take(5)->get();
        $recentGalleries = Gallery::with('admin', 'kategori')->latest()->take(6)->get();
        
        // Profil sekolah
        $schoolProfile = SchoolProfile::first();

        // Statistik untuk chart
        $chartData = [
            'news' => [
                'published' => $stats['published_news'],
                'draft' => $stats['draft_news']
            ],
            'galleries' => [
                'published' => $stats['published_galleries'],
                'draft' => $stats['draft_galleries']
            ],
            'visitors' => [
                'this_month' => $totalPengunjung,
                'last_month' => $totalPengunjungBulanLalu
            ]
        ];

        return view('admin.dashboard', compact(
            'stats',
            'totalBerita',
            'totalGaleri',
            'totalPengunjung',
            'persentaseKunjungan',
            'persentaseBerita',
            'persentaseGaleri',
            'recentNews',
            'recentGalleries',
            'recentBerita',
            'recentActivities',
            'schoolProfile',
            'chartData'
        ));
    }

    private function getRecentActivities($adminId = null)
    {
        // Ambil aktivitas terbaru dari ActivityLog
        $query = ActivityLog::with('admin')->recent(7)->latest();
        
        if ($adminId) {
            // Aktivitas untuk admin tertentu
            $query->forAdmin($adminId);
        }
        
        $activities = $query->take(10)->get();
        
        // Jika tidak ada aktivitas, buat aktivitas default
        if ($activities->isEmpty()) {
            $currentAdmin = auth('admin')->user();
            
            if ($adminId) {
                // Aktivitas untuk admin tertentu
                $activities = collect([
                    [
                        'description' => 'Admin login ke sistem',
                        'icon' => 'sign-in-alt',
                        'color' => 'blue',
                        'time' => now()->diffForHumans(),
                        'type' => 'login',
                        'admin_name' => $currentAdmin->name
                    ],
                    [
                        'description' => 'Mengelola konten berita',
                        'icon' => 'edit',
                        'color' => 'green',
                        'time' => now()->subMinutes(5)->diffForHumans(),
                        'type' => 'content',
                        'admin_name' => $currentAdmin->name
                    ],
                    [
                        'description' => 'Update profil sekolah',
                        'icon' => 'school',
                        'color' => 'purple',
                        'time' => now()->subMinutes(15)->diffForHumans(),
                        'type' => 'profile',
                        'admin_name' => $currentAdmin->name
                    ]
                ]);
            } else {
                // Aktivitas untuk super admin
                $activities = collect([
                    [
                        'description' => 'Super Admin login ke sistem',
                        'icon' => 'crown',
                        'color' => 'red',
                        'time' => now()->diffForHumans(),
                        'type' => 'login',
                        'admin_name' => $currentAdmin->name
                    ],
                    [
                        'description' => 'Mengelola admin lain',
                        'icon' => 'users-cog',
                        'color' => 'purple',
                        'time' => now()->subMinutes(5)->diffForHumans(),
                        'type' => 'admin',
                        'admin_name' => $currentAdmin->name
                    ],
                    [
                        'description' => 'Melihat statistik sistem',
                        'icon' => 'chart-bar',
                        'color' => 'blue',
                        'time' => now()->subMinutes(10)->diffForHumans(),
                        'type' => 'statistics',
                        'admin_name' => $currentAdmin->name
                    ],
                    [
                        'description' => 'Reset password admin',
                        'icon' => 'key',
                        'color' => 'yellow',
                        'time' => now()->subMinutes(20)->diffForHumans(),
                        'type' => 'security',
                        'admin_name' => $currentAdmin->name
                    ]
                ]);
            }
        } else {
            // Format aktivitas dari database
            $activities = $activities->map(function ($activity) {
                return [
                    'description' => $activity->description,
                    'icon' => $activity->action_icon,
                    'color' => $activity->action_color,
                    'time' => $activity->time_ago,
                    'type' => $activity->action,
                    'admin_name' => $activity->admin->name ?? 'Unknown',
                    'ip_address' => $activity->ip_address,
                    'metadata' => $activity->metadata
                ];
            });
        }

        return $activities;
    }

    // Method untuk detail statistik
    public function statisticsDetail($type)
    {
        $admin = auth('admin')->user();
        
        if ($admin->role !== 'super_admin') {
            abort(403, 'Akses ditolak. Hanya Super Admin yang dapat melihat detail statistik.');
        }

        switch ($type) {
            case 'news':
                $data = [
                    'total' => News::count(),
                    'published' => News::where('is_published', true)->count(),
                    'draft' => News::where('is_published', false)->count(),
                    'this_month' => News::whereMonth('created_at', now()->month)->count(),
                    'last_month' => News::whereMonth('created_at', now()->subMonth()->month)->count(),
                    'by_category' => News::with('newsCategory')
                        ->selectRaw('news_category_id, count(*) as total')
                        ->groupBy('news_category_id')
                        ->get()
                ];
                break;
                
            case 'galleries':
                $data = [
                    'total' => Gallery::count(),
                    'published' => Gallery::where('is_published', true)->count(),
                    'draft' => Gallery::where('is_published', false)->count(),
                    'this_month' => Gallery::whereMonth('created_at', now()->month)->count(),
                    'last_month' => Gallery::whereMonth('created_at', now()->subMonth()->month)->count(),
                    'by_category' => Gallery::with('kategori')
                        ->selectRaw('kategori_id, count(*) as total')
                        ->groupBy('kategori_id')
                        ->get()
                ];
                break;
                
            case 'admins':
                $data = [
                    'total' => Admin::count(),
                    'active' => Admin::where('is_active', true)->count(),
                    'inactive' => Admin::where('is_active', false)->count(),
                    'super_admin' => Admin::where('role', 'super_admin')->count(),
                    'regular_admin' => Admin::where('role', 'admin')->count(),
                    'recent_logins' => Admin::where('updated_at', '>=', now()->subDays(7))->count()
                ];
                break;
                
            case 'visitors':
                $data = [
                    'this_month' => DB::table('visits')->whereMonth('created_at', now()->month)->count(),
                    'last_month' => DB::table('visits')->whereMonth('created_at', now()->subMonth()->month)->count(),
                    'this_week' => DB::table('visits')->where('created_at', '>=', now()->startOfWeek())->count(),
                    'last_week' => DB::table('visits')->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count(),
                    'daily' => DB::table('visits')
                        ->selectRaw('DATE(created_at) as date, count(*) as total')
                        ->where('created_at', '>=', now()->subDays(30))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get()
                ];
                break;
                
            default:
                abort(404, 'Statistik tidak ditemukan.');
        }

        return view('admin.statistics.detail', compact('data', 'type'));
    }
}