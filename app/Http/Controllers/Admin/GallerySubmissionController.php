<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GallerySubmission;
use App\Models\GallerySubmissionImage;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GallerySubmissionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = GallerySubmission::with(['user','kategori','images'])->latest();
        if (in_array($status, ['pending','approved','rejected'])) {
            $query->where('status', $status);
        }

        $submissions = $query->paginate(12)->withQueryString();
        return view('admin.gallery_submissions.index', compact('submissions','status'));
    }

    public function publishImage(Request $request, GallerySubmission $submission, GallerySubmissionImage $image)
    {
        abort_unless($submission->id === $image->submission_id, 403);
        $submission->load('kategori');
        
        DB::transaction(function () use ($submission, $image) {
            // copy one image (read source; it may live on default or public disk)
            $raw = null;
            if (Storage::disk('public')->exists($image->path)) {
                $raw = Storage::disk('public')->get($image->path);
            } elseif (Storage::exists($image->path)) {
                $raw = Storage::get($image->path);
            }
            if ($raw === null) {
                abort(404, 'File gambar pengajuan tidak ditemukan.');
            }
            $kategoriSlug = $submission->kategori->slug ?? 'lainnya';
            $targetPath = 'galleries/'.$kategoriSlug;
            $fileName = uniqid().'_'.basename($image->path);
            Storage::disk('public')->put($targetPath.'/'.$fileName, $raw);

            Gallery::create([
                'title' => $submission->title,
                'description' => $submission->description,
                'image' => $targetPath.'/'.$fileName,
                'is_published' => true,
                'admin_id' => auth('admin')->id(),
                'kategori_id' => $submission->kategori_id,
                'submission_id' => $submission->id,
                'submission_image_id' => $image->id,
            ]);

            // ensure status approved
            $submission->update([
                'status' => 'approved',
                'reviewed_by' => auth('admin')->id(),
                'reviewed_at' => now(),
            ]);
        });

        return back()->with('success', 'Gambar dipublikasikan.');
    }

    public function show(GallerySubmission $submission)
    {
        $submission->load(['user','kategori','images']);
        $published = Gallery::where('submission_id', $submission->id)->get();
        $publishedByImage = $published->keyBy('submission_image_id');
        $maybe = collect();
        if ($published->isEmpty() && $submission->status === 'approved') {
            $maybe = Gallery::where('kategori_id', $submission->kategori_id)
                ->where('title', $submission->title)
                ->latest()->take(6)->get();
        }
        return view('admin.gallery_submissions.show', compact('submission','published','publishedByImage','maybe'));
    }

    public function approve(Request $request, GallerySubmission $submission)
    {
        abort_unless($submission->status === 'pending', 403);
        $submission->load(['images','kategori']);

        DB::transaction(function () use ($submission) {
            foreach ($submission->images as $img) {
                // copy to final galleries folder by category
                $kategori = $submission->kategori; // assumes exists
                $targetPath = 'galleries/'.($kategori?->slug ?? 'umum');
                $fileName = basename($img->path);

                // read source (it may be on default disk or already on public disk)
                $raw = null;
                if (Storage::disk('public')->exists($img->path)) {
                    $raw = Storage::disk('public')->get($img->path);
                } elseif (Storage::exists($img->path)) {
                    $raw = Storage::get($img->path);
                }
                if ($raw === null) {
                    abort(404, 'File gambar pengajuan tidak ditemukan.');
                }

                // write to public disk
                Storage::disk('public')->put($targetPath.'/'.$fileName, $raw);

                // create Gallery item per image
                Gallery::create([
                    'title' => $submission->title,
                    'description' => $submission->description,
                    'image' => $targetPath.'/'.$fileName,
                    'is_published' => true,
                    'admin_id' => auth('admin')->id(),
                    'kategori_id' => $submission->kategori_id,
                    'submission_id' => $submission->id,
                    'submission_image_id' => $img->id,
                ]);
            }

            $submission->update([
                'status' => 'approved',
                'reviewed_by' => auth('admin')->id(),
                'reviewed_at' => now(),
            ]);
        });

        return redirect()->route('admin.gallery-submissions.index', ['status' => 'pending'])
            ->with('success', 'Pengajuan disetujui dan dipublikasikan ke galeri.');
    }

    public function reject(Request $request, GallerySubmission $submission)
    {
        abort_unless($submission->status === 'pending', 403);
        $data = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $submission->update([
            'status' => 'rejected',
            'reject_reason' => $data['reason'],
            'reviewed_by' => auth('admin')->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.gallery-submissions.index', ['status' => 'pending'])
            ->with('success', 'Pengajuan ditolak.');
    }
}
