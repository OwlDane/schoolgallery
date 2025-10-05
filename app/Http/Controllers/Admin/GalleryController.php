<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryComment;
use App\Models\Gallery;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request, $kategoriSlug = null)
    {
        $query = Gallery::with('admin', 'kategori');
        
        // Filter by category if kategoriSlug is provided
        if ($kategoriSlug) {
            $query->whereHas('kategori', function($q) use ($kategoriSlug) {
                $q->where('slug', $kategoriSlug);
            });
        }
        
        $galleries = $query->latest()->paginate(12);
        $kategoris = Kategori::where('is_active', true)->get();
        
        return view('admin.galleries.index', compact('galleries', 'kategoris', 'kategoriSlug'));
    }

    public function create(Request $request)
    {
        $kategoris = Kategori::where('is_active', true)->get();
        $selectedKategori = $request->query('kategori');
        
        return view('admin.galleries.create', compact('kategoris', 'selectedKategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'image'        => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_published' => 'boolean',
            'kategori_id'  => 'required|exists:kategoris,id',
        ]);

        $imagePath = $request->file('image')->store('galleries', 'public');

        $gallery = Gallery::create([
            'title'        => $request->title,
            'description'  => $request->description,
            'image'        => $imagePath,
            'is_published' => $request->boolean('is_published'),
            'admin_id'     => auth('admin')->id(),
            'kategori_id'  => $request->kategori_id,
        ]);

        // Redirect back to the category page if it came from there
        if ($request->has('kategori')) {
            return redirect()->route('admin.galleries.kategori', $request->kategori)
                ->with('success', 'Galeri berhasil ditambahkan.');
        }

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function show(Gallery $gallery)
    {
        $gallery->load(['likes.user', 'comments', 'kategori', 'admin']);
        return view('admin.galleries.show', compact('gallery'));
    }

    public function edit(Request $request, Gallery $gallery)
    {
        $kategoris = Kategori::where('is_active', true)->get();
        $selectedKategori = $request->query('kategori');
        
        return view('admin.galleries.edit', compact('gallery', 'kategoris', 'selectedKategori'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_published' => 'boolean',
            'kategori_id'  => 'required|exists:kategoris,id',
        ]);

        $data = $request->only('title', 'description', 'is_published', 'kategori_id');

        if ($request->hasFile('image')) {
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        $gallery->update($data);

        // Redirect back to the category page if it came from there
        if ($request->has('kategori')) {
            return redirect()->route('admin.galleries.kategori', $request->kategori)
                ->with('success', 'Galeri berhasil diperbarui.');
        }

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Request $request, Gallery $gallery)
    {
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }

        $kategoriSlug = $gallery->kategori->slug ?? null;
        $gallery->delete();

        // Redirect back to the category page if it came from there
        if ($kategoriSlug) {
            return redirect()->route('admin.galleries.kategori', $kategoriSlug)
                ->with('success', 'Galeri berhasil dihapus.');
        }

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Galeri berhasil dihapus.');
    }

    public function togglePublish(Gallery $gallery)
    {
        $gallery->update([
            'is_published' => !$gallery->is_published
        ]);

        $status = $gallery->is_published ? 'dipublikasikan' : 'disembunyikan';
        return redirect()->back()->with('success', "Galeri berhasil {$status}.");
    }

    public function removeImage(Gallery $gallery)
    {
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
            $gallery->update(['image' => null]);
            return redirect()->back()->with('success', 'Gambar berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Tidak ada gambar untuk dihapus.');
    }
}
