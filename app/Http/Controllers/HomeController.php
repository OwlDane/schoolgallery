<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryLike;
use App\Models\GalleryComment;
use App\Models\News;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function index()
    {
        $schoolProfile = SchoolProfile::getProfile();
        $latestNews = News::published()->latest()->take(6)->get();
        $featuredGalleries = Gallery::published()->latest()->take(8)->get();

        return view('home', compact('schoolProfile', 'latestNews', 'featuredGalleries'));
    }

    public function gallery(Request $request)
    {
        $query = Gallery::published()->withCount(['likes', 'comments' => function($q){ $q->where('is_approved', true); }])->latest();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $galleries = $query->paginate(12);
        $schoolProfile = SchoolProfile::getProfile();

        return view('gallery', compact('galleries', 'schoolProfile'));
    }

    public function galleryByCategory($slug)
    {
        // Cari kategori berdasarkan slug
        $kategori = \App\Models\Kategori::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        // Jika kategori tidak ditemukan, redirect ke halaman galeri
        if (!$kategori) {
            return redirect()->route('gallery');
        }

        // Ambil galeri yang terkait dengan kategori tersebut
        $galleries = Gallery::with('kategori')
            ->where('kategori_id', $kategori->id)
            ->published()
            ->withCount(['likes', 'comments' => function($q){ $q->where('is_approved', true); }])
            ->latest()
            ->paginate(12);

        $schoolProfile = SchoolProfile::getProfile();

        return view('gallery', [
            'galleries' => $galleries,
            'schoolProfile' => $schoolProfile,
            'title' => $kategori->nama,
            'activeCategory' => $slug
        ]);
    }

    public function galleryDetail($id)
    {
        $gallery = Gallery::published()->with(['likes', 'comments' => function($q){ $q->where('is_approved', true); }])->findOrFail($id);
        $relatedGalleries = Gallery::published()
            ->where('id', '!=', $gallery->id)
            ->latest()
            ->take(4)
            ->get();
        $schoolProfile = SchoolProfile::getProfile();

        return view('gallery-detail', compact('gallery', 'relatedGalleries', 'schoolProfile'));
    }

    public function likeGallery(Request $request, $id)
    {
        $gallery = Gallery::published()->findOrFail($id);
        $fingerprint = $request->cookie('visitor_fingerprint') ?? $request->ip() . '|' . substr(hash('sha256', (string) $request->userAgent()), 0, 16);

        $existing = GalleryLike::where('gallery_id', $gallery->id)
            ->where('visitor_fingerprint', $fingerprint)
            ->first();

        if (!$existing) {
            GalleryLike::create([
                'gallery_id' => $gallery->id,
                'visitor_fingerprint' => $fingerprint,
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
            ]);
        }

        return redirect()->route('gallery.detail', $gallery->id)->with('liked', true);
    }

    public function unlikeGallery(Request $request, $id)
    {
        $gallery = Gallery::published()->findOrFail($id);
        $fingerprint = $request->cookie('visitor_fingerprint') ?? $request->ip() . '|' . substr(hash('sha256', (string) $request->userAgent()), 0, 16);

        GalleryLike::where('gallery_id', $gallery->id)
            ->where('visitor_fingerprint', $fingerprint)
            ->delete();

        return redirect()->route('gallery.detail', $gallery->id)->with('unliked', true);
    }

    public function commentGallery(Request $request, $id)
    {
        $gallery = Gallery::published()->findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        GalleryComment::create([
            'gallery_id' => $gallery->id,
            'name' => 'Guest',
            'email' => null,
            'content' => $validated['content'],
            'is_approved' => true,
        ]);

        return redirect()->route('gallery.detail', $gallery->id)->with('commented', true);
    }

    public function news(Request $request)
    {
        $query = News::published()->latest();

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $news = $query->paginate(10);
        $schoolProfile = SchoolProfile::getProfile();

        return view('news', compact('news', 'schoolProfile'));
    }

    public function newsDetail($slug)
    {
        $news = News::where('slug', $slug)->published()->firstOrFail();
        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->latest()
            ->take(4)
            ->get();
        $schoolProfile = SchoolProfile::getProfile();

        return view('news-detail', compact('news', 'relatedNews', 'schoolProfile'));
    }

    public function about()
    {
        $schoolProfile = SchoolProfile::getProfile();
        return view('about', compact('schoolProfile'));
    }

    public function contact()
    {
        $schoolProfile = SchoolProfile::getProfile();
        return view('contact', compact('schoolProfile'));
    }

    /**
     * Download a gallery image
     *
     * @param int $id
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($id)
    {
        $gallery = Gallery::findOrFail($id);
        
        // Check if the image path exists
        if (empty($gallery->image) || !Storage::disk('public')->exists($gallery->image)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        // Get the file path
        $filePath = storage_path('app/public/' . ltrim($gallery->image, '/'));
        
        // Generate a safe filename for download
        $fileName = 'galeri-' . $gallery->id . '-' . Str::slug($gallery->title) . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
        
        // For debugging
        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan di: ' . $filePath);
        }
        
        // Return the file as a download response
        return response()->download($filePath, $fileName, [
            'Content-Type' => mime_content_type($filePath),
            'Content-Length' => filesize($filePath),
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}