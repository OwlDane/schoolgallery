@extends('admin.layouts.app')

@section('title', $gallery->title)

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Detail Foto</h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <div class="bg-gray-100 p-2 rounded-lg">
                <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" class="w-full h-auto rounded-lg">
            </div>
        </div>
        <div>
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ $gallery->title }}</h3>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">Status:</p>
                    @if($gallery->is_published)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Dipublikasikan
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i> Draft
                        </span>
                    @endif
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">Deskripsi:</p>
                    <p class="text-gray-700">{{ $gallery->description ?? 'Tidak ada deskripsi' }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Ditambahkan oleh:</p>
                        <p class="text-gray-700">{{ $gallery->admin->name ?? 'Admin' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Kategori:</p>
                        <p class="text-gray-700">{{ $gallery->kategori->name ?? 'Tidak ada kategori' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tanggal dibuat:</p>
                        <p class="text-gray-700">{{ $gallery->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Terakhir diperbarui:</p>
                        <p class="text-gray-700">{{ $gallery->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.galleries.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Galeri
        </a>
    </div>
</div>
@endsection