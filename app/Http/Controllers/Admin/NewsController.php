<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with(['admin', 'kategori'])
            ->latest()
            ->paginate(10);
            
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.news.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'required|string|max:255',
            'is_published' => 'boolean',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'author' => $request->author,
            'is_published' => $request->boolean('is_published'),
            'kategori_id' => $request->kategori_id,
            'admin_id' => auth('admin')->id(),
        ];

        if ($request->boolean('is_published')) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        News::create($data);

        return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
    }

    public function show(News $news)
    {
        return view('admin.news.show', compact('news'));
    }

    public function edit(News $news)
    {
        $kategoris = Kategori::all();
        return view('admin.news.edit', compact('news', 'kategoris'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'author' => $request->author,
            'is_published' => $request->boolean('is_published'),
        ];

        if ($request->boolean('is_published') && !$news->is_published) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    public function destroy(News $news)
    {
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }
        
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }
}