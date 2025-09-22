@extends('admin.layouts.app')

@section('title', 'Daftar Guru')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Guru</h2>
        <a href="{{ route('admin.teachers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Tambah Guru
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-700 border border-green-200 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($teachers->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-chalkboard-teacher text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">Belum ada data guru</p>
            <a href="{{ route('admin.teachers.create') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                <i class="fas fa-plus mr-1"></i> Tambah guru sekarang
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($teachers as $teacher)
            <div class="bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <div class="relative">
                    @if($teacher->image)
                        <img src="{{ asset('storage/' . $teacher->image) }}" alt="{{ $teacher->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-user text-5xl text-gray-300"></i>
                        </div>
                    @endif
                    <div class="absolute top-2 right-2">
                        <span class="text-xs px-2 py-1 rounded-full {{ $teacher->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $teacher->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800">{{ $teacher->name }}</h3>
                    <p class="text-blue-600 text-sm mb-2">{{ $teacher->position }}</p>
                    @if($teacher->description)
                        <p class="text-gray-600 text-sm line-clamp-2">{{ Str::limit($teacher->description, 100) }}</p>
                    @endif
                    <div class="flex justify-between items-center text-sm text-gray-500 mt-3">
                        <span class="inline-flex items-center"><i class="fas fa-sort-numeric-down mr-1"></i> Urutan: {{ $teacher->order }}</span>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $teachers->links() }}
        </div>
    @endif
</div>
@endsection
