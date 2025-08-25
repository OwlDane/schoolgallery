@extends('admin.layouts.app')

@section('title', 'Galeri Foto')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Galeri Foto</h2>
        <a href="{{ route('admin.galleries.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Tambah Foto
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if($galleries->isEmpty())
        <div class="text-center py-8">
            <i class="fas fa-images text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">Belum ada foto dalam galeri</p>
            <a href="{{ route('admin.galleries.create') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
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
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $gallery->description ?? 'Tidak ada deskripsi' }}</p>
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <span><i class="fas fa-user mr-1"></i> {{ $gallery->admin->name ?? 'Admin' }}</span>
                            <span><i class="fas fa-calendar mr-1"></i> {{ $gallery->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash mr-1"></i> Hapus
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