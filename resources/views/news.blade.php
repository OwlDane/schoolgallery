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
        <div class="max-w-7xl mx-auto px-4">
            <form action="{{ route('news') }}" method="GET" class="flex flex-col md:flex-row gap-4 justify-center">
                <div class="relative flex-grow max-w-3xl">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..." 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 pl-12">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <button type="submit" class="btn-hover bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold shadow-lg flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
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
                    <div class="lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($news as $item)
                            <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 card-shine" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                <div class="relative">
                                @if($item->image)
                                    <img data-src="{{ asset('storage/' . $item->image) }}" loading="lazy" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                                @else
                                    <img data-src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" loading="lazy" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                                @endif
                                <div class="absolute top-4 right-4">
                                    <span class="bg-blue-600 text-white text-xs px-3 py-1 rounded-full">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ $item->created_at->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                            </div>
                            <div class="p-6">
                                @if($item->newsCategory)
                                    <div class="mb-2">
                                        <span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full font-semibold">{{ $item->newsCategory->name }}</span>
                                    </div>
                                @endif
                                <h3 class="text-xl font-bold mb-3 text-gray-800 line-clamp-2">{{ $item->title }}</h3>
                                <p class="text-gray-600 mb-4 line-clamp-3">{!! Str::limit(strip_tags($item->content), 150) !!}</p>
                                <a href="{{ route('news.detail', $item->slug) }}" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-800 transition-colors">
                                    Baca selengkapnya <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <aside class="lg:col-span-4">
                        <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6 mb-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center"><i class="fas fa-calendar-alt text-blue-600 mr-2"></i> Acara Mendatang</h3>
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
                                                    <p class="font-semibold text-gray-800"><a href="{{ route('events.show', $event->slug) }}" class="hover:text-blue-700 line-clamp-2">{{ $event->title }}</a></p>
                                                    @if($event->location)
                                                        <p class="text-xs text-gray-600 mt-1 flex items-center">
                                                            <i class="fas fa-map-marker-alt mr-1 text-red-500"></i>
                                                            <span>{{ $event->location }}</span>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-4 text-center">
                                    <a href="#" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors duration-300 shadow-md hover:shadow-lg">
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

                        <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center"><i class="fas fa-fire text-orange-500 mr-2"></i> Berita Terpopuler</h3>
                            @if(isset($latestNews) && $latestNews->isNotEmpty())
                                <ul class="space-y-4">
                                    @foreach($latestNews as $index => $ln)
                                        @if($index < 5)
                                        <li class="relative border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                            <div class="absolute top-0 left-0 bg-gradient-to-r from-blue-600 to-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-md">
                                                {{ $index + 1 }}
                                            </div>
                                            <a href="{{ route('news.detail', $ln->slug) }}" class="block pl-10">
                                                <p class="font-medium text-gray-800 line-clamp-2 hover:text-blue-600 transition-colors duration-300">{{ $ln->title }}</p>
                                                <div class="flex items-center text-xs text-gray-500 mt-1">
                                                    <span class="mr-3"><i class="far fa-calendar-alt mr-1"></i>{{ $ln->created_at->format('d M Y') }}</span>
                                                    <span><i class="far fa-eye mr-1"></i>{{ rand(100, 999) }} dilihat</span>
                                                </div>
                                            </a>
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500">Belum ada berita terpopuler.</p>
                            @endif
                            <div class="mt-4 text-right">
                                <a href="{{ route('news') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold flex items-center justify-end">
                                    <span>Lihat semua</span>
                                    <i class="fas fa-arrow-right ml-1 text-xs transition-transform duration-300 group-hover:translate-x-1"></i>
                                </a>
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