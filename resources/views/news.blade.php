@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                <span class="inline-block bg-blue-500/30 text-white text-sm font-semibold px-4 py-1 rounded-full mb-4 backdrop-blur-sm border border-blue-400/30">
                    <i class="fas fa-newspaper mr-2"></i> BERITA SEKOLAH
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-shadow leading-tight">
                    Berita & Informasi <span class="text-yellow-300 relative inline-block">
                        Terkini
                        <svg class="absolute -bottom-2 left-0 w-full" height="6" viewBox="0 0 200 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 3C50 0.5 150 0.5 200 3" stroke="#FBBF24" stroke-width="5" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>
                <p class="text-xl mb-8 text-blue-100 leading-relaxed max-w-3xl mx-auto">
                    Temukan informasi terbaru tentang kegiatan, prestasi, dan pengumuman penting dari {{ $schoolProfile->school_name ?? 'Sekolah Kami' }}
                </p>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="py-8 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4">
            <form action="{{ route('news') }}" method="GET" class="relative">
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari berita..." 
                        class="w-full px-5 py-4 pr-12 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 pl-14 shadow-sm hover:shadow-md"
                    >
                    <div class="absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <button 
                        type="submit" 
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-300 flex items-center"
                    >
                        <span class="hidden sm:inline">Cari</span>
                        <i class="fas fa-search sm:ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- News + Sidebar Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            @if(request('search'))
                <div class="mb-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-800">Hasil pencarian: "{{ request('search') }}"</h3>
                    <p class="text-gray-600">{{ $news->total() }} berita ditemukan</p>
                </div>
            @endif

            @if($news->isEmpty())
                <div class="text-center py-16">
                    <div class="text-6xl text-gray-300 mb-4"><i class="fas fa-newspaper"></i></div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Tidak ada berita ditemukan</h3>
                    <p class="text-gray-600">Silakan coba dengan kata kunci lain atau kembali lagi nanti.</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-8 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($news as $item)
                                <a href="{{ route('news.detail', $item->slug) }}" class="group block h-full">
                                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 h-full flex flex-col border border-gray-100">
                                        <div class="relative flex-shrink-0">
                                            @if($item->image)
                                                <img 
                                                    src="{{ asset('storage/' . $item->image) }}" 
                                                    alt="{{ $item->title }}" 
                                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105"
                                                    loading="lazy"
                                                >
                                            @else
                                                <div class="w-full h-48 bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center">
                                                    <i class="fas fa-newspaper text-white text-5xl"></i>
                                                </div>
                                            @endif
                                            <div class="absolute top-3 right-3">
                                                <span class="bg-blue-600/90 text-white text-xs px-3 py-1 rounded-full shadow-md backdrop-blur-sm">
                                                    <i class="far fa-calendar-alt mr-1"></i> {{ $item->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="p-5 flex-grow flex flex-col">
                                            @if($item->newsCategory)
                                                <div class="mb-2">
                                                    <span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-2.5 py-1 rounded-full font-medium">
                                                        {{ $item->newsCategory->name }}
                                                    </span>
                                                </div>
                                            @endif
                                            <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                                {{ $item->title }}
                                            </h3>
                                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                                {!! Str::limit(strip_tags($item->content), 120) !!}
                                            </p>
                                            <div class="mt-auto pt-3">
                                                <span class="inline-flex items-center text-blue-600 font-medium text-sm group-hover:underline">
                                                    Baca selengkapnya
                                                    <i class="fas fa-arrow-right ml-2 text-xs mt-0.5"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-12">
                            {{ $news->withQueryString()->links() }}
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <aside class="lg:col-span-4 space-y-6">
                        <!-- Upcoming Events Card -->
                        <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i> Acara Mendatang
                            </h3>
                            @if(isset($upcomingEvents) && $upcomingEvents->isNotEmpty())
                                <ul class="space-y-4">
                                    @foreach($upcomingEvents as $event)
                                        <li class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 border border-blue-100">
                                            <div class="flex items-start gap-3">
                                                @if($event->image)
                                                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-16 h-16 rounded-lg object-cover flex-shrink-0 shadow-md">
                                                @else
                                                    <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-blue-400 to-indigo-500 text-white flex items-center justify-center flex-shrink-0 shadow-md">
                                                        <i class="fas fa-calendar-day text-2xl"></i>
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <div class="bg-white px-2 py-1 rounded-full inline-block mb-2 shadow-sm border border-blue-100">
                                                        <p class="text-xs text-blue-700 font-medium">
                                                            <i class="far fa-clock mr-1"></i>
                                                            <span class="timeago" data-time="{{ $event->start_at->toIso8601String() }}">{{ $event->start_at->format('d M Y H:i') }}</span>
                                                        </p>
                                                    </div>
                                                    <p class="font-semibold text-gray-800">
                                                        <a href="{{ route('events.show', $event->slug) }}" class="hover:text-blue-700 line-clamp-2">{{ $event->title }}</a>
                                                    </p>
                                                    @if($event->location)
                                                        <p class="text-xs text-gray-600 mt-1 flex items-center">
                                                            <i class="fas fa-map-marker-alt mr-1 text-red-500"></i>
                                                            <span class="truncate">{{ $event->location }}</span>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-4 text-center">
                                    <a href="{{ route('events.index') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors duration-300 shadow-md hover:shadow-lg">
                                        <i class="fas fa-calendar-plus mr-1"></i> Lihat Semua Acara
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-8 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="text-4xl text-blue-300 mb-2"><i class="far fa-calendar-times"></i></div>
                                    <p class="text-gray-600 font-medium">Belum ada acara mendatang.</p>
                                    <p class="text-gray-500 text-sm mt-1">Kunjungi kembali halaman ini nanti.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Popular News Card -->
                        <div class="bg-white rounded-xl shadow-md overflow-hidden mt-6 sticky top-[400px]">
                            <div class="bg-gradient-to-r from-orange-500 to-amber-500 text-white px-6 py-4">
                                <h3 class="text-lg font-semibold flex items-center">
                                    <i class="fas fa-fire mr-2"></i> Berita Terpopuler
                                </h3>
                            </div>
                            <div class="p-5">
                                @if(isset($latestNews) && $latestNews->isNotEmpty())
                                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                                        @foreach($latestNews as $index => $ln)
                                            @if($index < 5)
                                            <div class="group">
                                                <a href="{{ route('news.detail', $ln->slug) }}" class="block">
                                                    <div class="flex items-start gap-3 p-3 rounded-lg transition-colors duration-200 hover:bg-orange-50">
                                                        <div class="relative flex-shrink-0">
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 text-white flex items-center justify-center font-bold text-sm shadow-md">
                                                                {{ $index + 1 }}
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <h4 class="text-sm font-medium text-gray-800 line-clamp-2 group-hover:text-orange-600 transition-colors">
                                                                {{ $ln->title }}
                                                            </h4>
                                                            <div class="flex items-center mt-1.5 text-xs text-gray-500">
                                                                <span class="flex items-center">
                                                                    <i class="far fa-eye mr-1"></i> {{ number_format($ln->views) }}
                                                                </span>
                                                                <span class="mx-2">•</span>
                                                                <span>{{ $ln->created_at->diffForHumans() }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <a href="{{ route('news') }}" class="text-sm font-medium text-orange-600 hover:text-orange-700 inline-flex items-center">
                                            Lihat semua berita
                                            <i class="fas fa-arrow-right ml-2 text-xs mt-0.5"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-6">
                                        <div class="text-orange-100 mb-2">
                                            <i class="fas fa-newspaper text-4xl"></i>
                                        </div>
                                        <p class="text-gray-500 text-sm">Belum ada berita terpopuler</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </aside>
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $news->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection