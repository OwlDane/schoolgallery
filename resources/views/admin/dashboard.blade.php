@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')
<!-- Header -->
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
        <p class="text-gray-600 mt-1">Ringkasan aktivitas dan statistik terbaru</p>
    </div>
    <div class="text-sm text-gray-500">
        {{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Berita -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-blue-50 text-blue-600 mr-4">
                <i class="fas fa-newspaper text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Berita</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalBerita ?? 0 }}</h3>
                <p class="text-xs text-green-600 flex items-center mt-1">
                    <i class="fas fa-arrow-up mr-1"></i> 12% dari bulan lalu
                </p>
            </div>
        </div>
    </div>

    <!-- Total Galeri -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-indigo-50 text-indigo-600 mr-4">
                <i class="fas fa-images text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Galeri</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalGaleri ?? 0 }}</h3>
                <p class="text-xs text-green-600 flex items-center mt-1">
                    <i class="fas fa-arrow-up mr-1"></i> 5% dari bulan lalu
                </p>
            </div>
        </div>
    </div>

    <!-- Total Kunjungan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-green-50 text-green-600 mr-4">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Pengunjung Bulan Ini</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalPengunjung ?? '1.2K' }}</h3>
                <p class="text-xs text-green-600 flex items-center mt-1">
                    <i class="fas fa-arrow-up mr-1"></i> 8% dari bulan lalu
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Berita Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Berita Terbaru</h3>
                <a href="{{ route('admin.news.index') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                    Lihat Semua <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentBerita as $berita)
            <a href="{{ route('admin.news.edit', $berita->id) }}" class="block hover:bg-gray-50 transition-colors duration-150 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-16 w-16 rounded-lg bg-gray-100 overflow-hidden">
                        @if($berita->gambar)
                            <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full bg-gray-200 flex items-center justify-center text-gray-400">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="text-sm font-medium text-gray-900 line-clamp-1">{{ $berita->judul }}</h4>
                        <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ Str::limit(strip_tags($berita->isi), 100) }}</p>
                        <div class="mt-2 flex items-center text-xs text-gray-400">
                            <span>{{ $berita->created_at->diffForHumans() }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $berita->kategori->nama ?? 'Tanpa Kategori' }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                <p>Belum ada berita</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentActivities as $activity)
            <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-{{ $activity['color'] }}-100 text-{{ $activity['color'] }}-600 flex items-center justify-center">
                            <i class="fas fa-{{ $activity['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm text-gray-900">{{ $activity['description'] }}</div>
                        <div class="mt-1 flex items-center text-xs text-gray-500">
                            <i class="far fa-clock mr-1"></i>
                            <span>{{ $activity['time'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-history text-3xl text-gray-300 mb-2"></i>
                <p>Belum ada aktivitas</p>
            </div>
            @endforelse
        </div>
@endsection
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.news.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                    Kelola Berita <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Galeri Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-green-50 text-green-600">
                    <i class="fas fa-images text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Galeri</h3>
                    <div class="mt-1 flex items-baseline">
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_galleries'] }}</p>
                        <span class="ml-2 text-sm font-medium text-green-600">
                            <span class="flex items-center">
                                {{ $stats['published_galleries'] }} dipublikasi
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.galleries.index') }}" class="text-sm font-medium text-green-600 hover:text-green-500">
                    Kelola Galeri <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Profil Sekolah Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-purple-50 text-purple-600">
                    <i class="fas fa-school text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Sekolah</h3>
                    <p class="text-xl font-bold text-purple-600">{{ $schoolProfile->school_name ?? 'Belum diatur' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Admin</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['total_admins'] }}</p>
                <p class="text-sm text-gray-500">{{ $stats['active_admins'] }} aktif</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent News -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-700">Berita Terbaru</h3>
        </div>
        <div class="p-6">
            @forelse($recentNews as $news)
                <div class="flex items-center py-3 border-b last:border-b-0">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-800">{{ $news->title }}</h4>
                        <p class="text-sm text-gray-500">{{ $news->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full {{ $news->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $news->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
            @empty
                <p class="text-gray-500">Belum ada berita</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Galleries -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-700">Galeri Terbaru</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-3 gap-4">
                @forelse($recentGalleries as $gallery)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" class="w-full h-20 object-cover rounded">
                        <div class="absolute inset-0 bg-black bg-opacity-50 rounded flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                            <span class="text-white text-xs text-center px-2">{{ $gallery->title }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3">
                        <p class="text-gray-500">Belum ada galeri</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection