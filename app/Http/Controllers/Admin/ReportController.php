<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Admin;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display reports index page
     */
    public function index()
    {
        $admin = auth('admin')->user();
        
        // Basic stats for overview
        $stats = [
            'total_visitors' => DB::table('visits')->count(),
            'visitors_this_month' => DB::table('visits')->whereMonth('created_at', now()->month)->count(),
            'visitors_last_month' => DB::table('visits')->whereMonth('created_at', now()->subMonth()->month)->count(),
            'total_news' => News::count(),
            'total_galleries' => Gallery::count(),
            'total_admins' => Admin::count(),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    /**
     * Export visitor statistics report
     */
    public function exportVisitorStats(Request $request)
    {
        $admin = auth('admin')->user();
        
        // Validate date range
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel'
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Get visitor statistics
        $visitorStats = $this->getVisitorStatistics($startDate, $endDate);
        
        // Get school profile for branding
        $schoolProfile = \App\Models\SchoolProfile::first();

        if ($request->format === 'pdf') {
            return $this->exportVisitorStatsPDF($visitorStats, $schoolProfile, $startDate, $endDate);
        } else {
            return $this->exportVisitorStatsExcel($visitorStats, $schoolProfile, $startDate, $endDate);
        }
    }

    /**
     * Export content statistics report
     */
    public function exportContentStats(Request $request)
    {
        $admin = auth('admin')->user();
        
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel'
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Get content statistics
        $contentStats = $this->getContentStatistics($startDate, $endDate);
        $schoolProfile = \App\Models\SchoolProfile::first();

        if ($request->format === 'pdf') {
            return $this->exportContentStatsPDF($contentStats, $schoolProfile, $startDate, $endDate);
        } else {
            return $this->exportContentStatsExcel($contentStats, $schoolProfile, $startDate, $endDate);
        }
    }

    /**
     * Export admin activity report
     */
    public function exportAdminActivity(Request $request)
    {
        $admin = auth('admin')->user();
        
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel'
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Get admin activity statistics
        $activityStats = $this->getAdminActivityStatistics($startDate, $endDate);
        $schoolProfile = \App\Models\SchoolProfile::first();

        if ($request->format === 'pdf') {
            return $this->exportAdminActivityPDF($activityStats, $schoolProfile, $startDate, $endDate);
        } else {
            return $this->exportAdminActivityExcel($activityStats, $schoolProfile, $startDate, $endDate);
        }
    }

    /**
     * Get visitor statistics data
     */
    private function getVisitorStatistics($startDate, $endDate)
    {
        // Daily visitors
        $dailyVisitors = DB::table('visits')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly comparison
        $currentMonth = DB::table('visits')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonth = DB::table('visits')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        // Weekly breakdown
        $weeklyVisitors = DB::table('visits')
            ->selectRaw('WEEK(created_at) as week, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        // Hourly distribution
        $hourlyVisitors = DB::table('visits')
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return [
            'daily' => $dailyVisitors,
            'monthly_comparison' => [
                'current' => $currentMonth,
                'last' => $lastMonth,
                'percentage_change' => $lastMonth > 0 ? round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2) : 0
            ],
            'weekly' => $weeklyVisitors,
            'hourly' => $hourlyVisitors,
            'total_visitors' => $dailyVisitors->sum('total'),
            'average_daily' => $dailyVisitors->count() > 0 ? round($dailyVisitors->sum('total') / $dailyVisitors->count(), 2) : 0
        ];
    }

    /**
     * Get content statistics data
     */
    private function getContentStatistics($startDate, $endDate)
    {
        // News statistics
        $newsStats = [
            'total' => News::whereBetween('created_at', [$startDate, $endDate])->count(),
            'published' => News::whereBetween('created_at', [$startDate, $endDate])->where('is_published', true)->count(),
            'draft' => News::whereBetween('created_at', [$startDate, $endDate])->where('is_published', false)->count(),
            'by_category' => News::with('newsCategory')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('news_category_id, count(*) as total')
                ->groupBy('news_category_id')
                ->get()
        ];

        // Gallery statistics
        $galleryStats = [
            'total' => Gallery::whereBetween('created_at', [$startDate, $endDate])->count(),
            'published' => Gallery::whereBetween('created_at', [$startDate, $endDate])->where('is_published', true)->count(),
            'draft' => Gallery::whereBetween('created_at', [$startDate, $endDate])->where('is_published', false)->count(),
            'by_category' => Gallery::with('kategori')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('kategori_id, count(*) as total')
                ->groupBy('kategori_id')
                ->get()
        ];

        // Admin activity for content
        $adminContentActivity = DB::table('activity_logs')
            ->join('admins', 'activity_logs.admin_id', '=', 'admins.id')
            ->whereBetween('activity_logs.created_at', [$startDate, $endDate])
            ->whereIn('activity_logs.action', ['created', 'updated', 'deleted'])
            ->selectRaw('admins.name, activity_logs.action, count(*) as total')
            ->groupBy('admins.name', 'activity_logs.action')
            ->get();

        return [
            'news' => $newsStats,
            'galleries' => $galleryStats,
            'admin_activity' => $adminContentActivity,
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ];
    }

    /**
     * Get admin activity statistics
     */
    private function getAdminActivityStatistics($startDate, $endDate)
    {
        // Login statistics
        $loginStats = DB::table('activity_logs')
            ->join('admins', 'activity_logs.admin_id', '=', 'admins.id')
            ->whereBetween('activity_logs.created_at', [$startDate, $endDate])
            ->where('activity_logs.action', 'login')
            ->selectRaw('admins.name, count(*) as total')
            ->groupBy('admins.name')
            ->get();

        // Activity breakdown
        $activityBreakdown = DB::table('activity_logs')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('action, count(*) as total')
            ->groupBy('action')
            ->get();

        // Daily activity
        $dailyActivity = DB::table('activity_logs')
            ->selectRaw('DATE(created_at) as date, count(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Admin performance
        $adminPerformance = DB::table('activity_logs')
            ->join('admins', 'activity_logs.admin_id', '=', 'admins.id')
            ->whereBetween('activity_logs.created_at', [$startDate, $endDate])
            ->selectRaw('admins.name, count(*) as total_activities')
            ->groupBy('admins.name')
            ->orderBy('total_activities', 'desc')
            ->get();

        return [
            'login_stats' => $loginStats,
            'activity_breakdown' => $activityBreakdown,
            'daily_activity' => $dailyActivity,
            'admin_performance' => $adminPerformance,
            'total_activities' => $dailyActivity->sum('total'),
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ];
    }

    /**
     * Export visitor statistics to PDF
     */
    private function exportVisitorStatsPDF($data, $schoolProfile, $startDate, $endDate)
    {
        $pdf = Pdf::loadView('admin.reports.visitor-stats-pdf', [
            'data' => $data,
            'schoolProfile' => $schoolProfile,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now()
        ]);

        $filename = 'laporan-statistik-kunjungan-' . $startDate . '-to-' . $endDate . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export visitor statistics to Excel
     */
    private function exportVisitorStatsExcel($data, $schoolProfile, $startDate, $endDate)
    {
        // This would require Laravel Excel package
        // For now, return a simple CSV
        $csvData = [];
        $csvData[] = ['Tanggal', 'Jumlah Pengunjung'];
        
        foreach ($data['daily'] as $day) {
            $csvData[] = [$day->date, $day->total];
        }

        $filename = 'laporan-statistik-kunjungan-' . $startDate . '-to-' . $endDate . '.csv';
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export content statistics to PDF
     */
    private function exportContentStatsPDF($data, $schoolProfile, $startDate, $endDate)
    {
        $pdf = Pdf::loadView('admin.reports.content-stats-pdf', [
            'data' => $data,
            'schoolProfile' => $schoolProfile,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now()
        ]);

        $filename = 'laporan-statistik-konten-' . $startDate . '-to-' . $endDate . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export content statistics to Excel
     */
    private function exportContentStatsExcel($data, $schoolProfile, $startDate, $endDate)
    {
        $csvData = [];
        $csvData[] = ['Jenis Konten', 'Total', 'Dipublikasikan', 'Draft'];
        $csvData[] = ['Berita', $data['news']['total'], $data['news']['published'], $data['news']['draft']];
        $csvData[] = ['Galeri', $data['galleries']['total'], $data['galleries']['published'], $data['galleries']['draft']];

        $filename = 'laporan-statistik-konten-' . $startDate . '-to-' . $endDate . '.csv';
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export admin activity to PDF
     */
    private function exportAdminActivityPDF($data, $schoolProfile, $startDate, $endDate)
    {
        $pdf = Pdf::loadView('admin.reports.admin-activity-pdf', [
            'data' => $data,
            'schoolProfile' => $schoolProfile,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now()
        ]);

        $filename = 'laporan-aktivitas-admin-' . $startDate . '-to-' . $endDate . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export admin activity to Excel
     */
    private function exportAdminActivityExcel($data, $schoolProfile, $startDate, $endDate)
    {
        $csvData = [];
        $csvData[] = ['Admin', 'Total Aktivitas'];
        
        foreach ($data['admin_performance'] as $admin) {
            $csvData[] = [$admin->name, $admin->total_activities];
        }

        $filename = 'laporan-aktivitas-admin-' . $startDate . '-to-' . $endDate . '.csv';
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
