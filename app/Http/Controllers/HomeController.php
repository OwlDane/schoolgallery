<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\News;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;

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
        $query = Gallery::published()->latest();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $galleries = $query->paginate(12);
        $schoolProfile = SchoolProfile::getProfile();

        return view('gallery', compact('galleries', 'schoolProfile'));
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
}