<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryLike;
use App\Models\GalleryComment;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsComment;
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
        $gallery = Gallery::published()->with(['likes', 'comments' => function($q){ 
            $q->where('is_approved', true)->mainComments()->with('replies'); 
        }])->findOrFail($id);
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
        // This method is now handled by InteractionController
        // Redirect to the new API endpoint
        return redirect()->route('gallery.detail', $id);
    }

    public function unlikeGallery(Request $request, $id)
    {
        // This method is now handled by InteractionController
        // Redirect to the new API endpoint
        return redirect()->route('gallery.detail', $id);
    }

    public function commentGallery(Request $request, $id)
    {
        // This method is now handled by InteractionController
        // Redirect to the new API endpoint
        return redirect()->route('gallery.detail', $id);
    }

    public function news(Request $request)
    {
        $query = News::with('newsCategory')->published()->latest();

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $category = NewsCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('news_category_id', $category->id);
            }
        }

        $news = $query->paginate(10);
        $schoolProfile = SchoolProfile::getProfile();
        $categories = NewsCategory::active()->ordered()->get();

        return view('news', compact('news', 'schoolProfile', 'categories'));
    }

    public function newsDetail($slug)
    {
        $news = News::where('slug', $slug)->with(['newsCategory', 'comments'])->published()->firstOrFail();
        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->latest()
            ->take(4)
            ->get();
        $schoolProfile = SchoolProfile::getProfile();

        return view('news-detail', compact('news', 'relatedNews', 'schoolProfile'));
    }

    public function commentNews(Request $request, $slug)
    {
        $news = News::where('slug', $slug)->published()->firstOrFail();

        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'content' => 'required|string|max:1000',
        ]);

        NewsComment::create([
            'news_id' => $news->id,
            'name' => $validated['name'] ?? 'Pengunjung',
            'content' => $validated['content'],
            'is_approved' => true,
        ]);

        return back()->with('success', 'Komentar terkirim.');
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

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:1000',
        ]);

        // Here you can add logic to send email or save to database
        // For now, just return success message
        
        return back()->with('success', 'Pesan Anda telah terkirim. Terima kasih telah menghubungi kami!');
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