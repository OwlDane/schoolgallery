<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\GallerySubmission;
use App\Models\GallerySubmissionImage;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GallerySubmissionController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $categories = Kategori::orderBy('nama')->get();
        return view('guest.gallery-submit', compact('user', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'images' => 'required|array|max:2',
            'images.*' => 'file|mimes:jpeg,jpg,png,webp|max:3072', // 3MB
        ]);

        $user = Auth::user();
        $uuid = (string) Str::uuid();

        $submission = GallerySubmission::create([
            'user_id' => $user->id,
            'kategori_id' => $request->kategori_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        $basePath = "public/submissions/{$uuid}";
        foreach ($request->file('images', []) as $file) {
            $path = $file->store($basePath);
            GallerySubmissionImage::create([
                'submission_id' => $submission->id,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);
        }

        return redirect()->route('gallery')->with('success', 'Terima kasih! Pengajuan foto kamu sudah diterima dan menunggu persetujuan admin.');
    }
}
