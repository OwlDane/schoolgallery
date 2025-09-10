@extends('admin.layouts.app')

@section('title', 'Galeri Foto')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
            @if($kategoriSlug)
                Galeri {{ $kategoris->where('slug', $kategoriSlug)->first()->nama ?? '' }}
            @else
                Daftar Galeri Foto
            @endif
        </h2>
        <a href="{{ route('admin.galleries.create', $kategoriSlug ? ['kategori' => $kategoriSlug] : []) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Tambah Foto
        </a>
    </div>

    <!-- Kategori Navigasi -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.galleries.index') }}" 
               class="px-4 py-2 rounded-full text-sm font-medium {{ !$kategoriSlug ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua Kategori
            </a>
            @foreach($kategoris as $kategori)
                <a href="{{ route('admin.galleries.kategori', $kategori->slug) }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium {{ $kategoriSlug == $kategori->slug ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $kategori->nama }}
                </a>
            @endforeach
        </div>
    </div>


    @if($galleries->isEmpty())
        <div class="text-center py-8">
            <i class="fas fa-images text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">Belum ada foto dalam galeri</p>
            <a href="{{ route('admin.galleries.create', $kategoriSlug ? ['kategori' => $kategoriSlug] : []) }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                <i class="fas fa-plus mr-1"></i> Tambah foto sekarang
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($galleries as $gallery)
                <div class="bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="relative aspect-w-16 aspect-h-9">
                        <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" class="w-full h-48 object-cover">
                        @if(!$gallery->is_published)
                            <div class="absolute top-2 right-2">
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Draft</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">{{ $gallery->title }}</h3>
                        @if($gallery->kategori)
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-2">
                                {{ $gallery->kategori->nama }}
                            </span>
                        @endif
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $gallery->description ?? 'Tidak ada deskripsi' }}</p>
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <span><i class="fas fa-user mr-1"></i> {{ $gallery->admin->name ?? 'Admin' }}</span>
                            <span class="inline-flex items-center gap-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-pink-100 text-pink-700"><i class="fas fa-heart mr-1"></i>{{ $gallery->likes()->count() }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-700"><i class="far fa-comment mr-1"></i>{{ $gallery->comments()->count() }}</span>
                                <span><i class="fas fa-calendar ml-2 mr-1"></i> {{ $gallery->created_at->format('d M Y') }}</span>
                            </span>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.galleries.edit', array_merge([$gallery], $kategoriSlug ? ['kategori' => $kategoriSlug] : [])) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.galleries.toggle-publish', $gallery) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-{{ $gallery->is_published ? 'yellow' : 'green' }}-600 hover:text-{{ $gallery->is_published ? 'yellow' : 'green' }}-800" title="{{ $gallery->is_published ? 'Unpublish' : 'Publish' }}">
                                        <i class="fas fa-{{ $gallery->is_published ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>
                            </div>
                            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $galleries->links() }}
        </div>
    @endif
</div>
@endsection