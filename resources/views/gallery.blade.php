@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                <span class="inline-block bg-blue-500/30 text-white text-sm font-semibold px-4 py-1 rounded-full mb-4 backdrop-blur-sm border border-blue-400/30">
                    <i class="fas fa-images mr-2"></i> GALERI SEKOLAH
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-shadow leading-tight">
                    Galeri <span class="text-yellow-300 relative inline-block">
                        Kegiatan
                        <svg class="absolute -bottom-2 left-0 w-full" height="6" viewBox="0 0 200 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 3C50 0.5 150 0.5 200 3" stroke="#FBBF24" stroke-width="5" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>
                <p class="text-xl mb-8 text-blue-100 leading-relaxed max-w-3xl mx-auto">
                    Dokumentasi visual dari berbagai kegiatan dan momen berharga di {{ $schoolProfile->school_name ?? 'Sekolah Kami' }}
                </p>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <form action="{{ route('gallery') }}" method="GET" class="flex flex-col md:flex-row gap-4 justify-center">
                <div class="relative flex-grow max-w-3xl">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari galeri..." 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 pl-12">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <button type="submit" class="btn-hover bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold shadow-lg flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
            </form>
        </div>
    </section>

    <!-- Gallery Grid Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            @if(request('search'))
                <div class="mb-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-800">Hasil pencarian: "{{ request('search') }}"</h3>
                    <p class="text-gray-600">{{ $galleries->total() }} galeri ditemukan</p>
                </div>
            @endif

            @if($galleries->isEmpty())
                <div class="text-center py-16">
                    <div class="text-6xl text-gray-300 mb-4"><i class="fas fa-images"></i></div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Tidak ada galeri ditemukan</h3>
                    <p class="text-gray-600">Silakan coba dengan kata kunci lain atau kembali lagi nanti.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($galleries as $gallery)
                        <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" 
                             data-aos="fade-up" data-aos-delay="{{ $loop->index % 4 * 100 }}">
                            <div class="aspect-w-4 aspect-h-3">
                                @if($gallery->image)
                                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" 
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                                        alt="{{ $gallery->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @endif
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                                <h3 class="text-white font-bold text-lg mb-1">{{ $gallery->title }}</h3>
                                <p class="text-gray-200 text-sm">{{ $gallery->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $galleries->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- Gallery Categories -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Kategori Galeri</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Jelajahi galeri berdasarkan kategori kegiatan</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <a href="#" class="bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300" data-aos="zoom-in" data-aos-delay="0">
                    <div class="text-3xl mb-3"><i class="fas fa-graduation-cap"></i></div>
                    <h3 class="font-bold">Akademik</h3>
                </a>
                <a href="#" class="bg-gradient-to-br from-green-500 to-green-700 text-white rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300" data-aos="zoom-in" data-aos-delay="100">
                    <div class="text-3xl mb-3"><i class="fas fa-trophy"></i></div>
                    <h3 class="font-bold">Prestasi</h3>
                </a>
                <a href="#" class="bg-gradient-to-br from-yellow-500 to-yellow-700 text-white rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300" data-aos="zoom-in" data-aos-delay="200">
                    <div class="text-3xl mb-3"><i class="fas fa-music"></i></div>
                    <h3 class="font-bold">Seni & Budaya</h3>
                </a>
                <a href="#" class="bg-gradient-to-br from-red-500 to-red-700 text-white rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300" data-aos="zoom-in" data-aos-delay="300">
                    <div class="text-3xl mb-3"><i class="fas fa-futbol"></i></div>
                    <h3 class="font-bold">Olahraga</h3>
                </a>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-2/3 mb-8 md:mb-0" data-aos="fade-right">
                    <h2 class="text-3xl font-bold mb-4">Punya Foto Kegiatan Sekolah?</h2>
                    <p class="text-blue-100">Bagikan momen berharga di sekolah dengan mengirimkan foto kegiatan untuk ditampilkan di galeri kami.</p>
                </div>
                <div class="md:w-1/3 text-center md:text-right" data-aos="fade-left">
                    <a href="{{ route('contact') }}" class="btn-hover inline-block bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Foto
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection