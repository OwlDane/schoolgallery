@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" />
<style>
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        padding: 15px 25px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateX(120%);
        transition: transform 0.3s ease-in-out;
    }
    .toast.show {
        transform: translateX(0);
    }
    .toast.success {
        background-color: #10B981;
    }
    .toast.error {
        background-color: #EF4444;
    }
    
    /* Styling untuk card galeri */
    .gallery-item {
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .gallery-item .image-container {
        height: 200px; /* Tinggi tetap untuk semua gambar */
        overflow: hidden;
        position: relative;
    }
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .gallery-item:hover img {
        transform: scale(1.05);
    }
    .gallery-item .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .gallery-item .card-footer {
        background: transparent;
        border-top: 0;
        padding-top: 0;
        margin-top: auto;
    }
</style>
@endpush

@section('content')
    @if(session('error'))
        <div class="toast error show">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                <span class="inline-block bg-blue-500/30 text-white text-sm font-semibold px-4 py-1 rounded-full mb-4 backdrop-blur-sm border border-blue-400/30">
                    <i class="fas fa-images mr-2"></i> GALERI SEKOLAH
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-shadow leading-tight">
                    Galeri <span class="text-yellow-300 relative inline-block">
                        {{ $title ?? 'Sekolah' }}
                        <svg class="absolute -bottom-2 left-0 w-full" height="6" viewBox="0 0 200 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 3C50 0.5 150 0.5 200 3" stroke="#FBBF24" stroke-width="5" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>
                <p class="text-xl mb-8 text-blue-100 leading-relaxed max-w-3xl mx-auto">
                    @if(isset($title))
                        Koleksi galeri {{ strtolower($title) }} {{ $schoolProfile->school_name ?? 'Sekolah Kami' }}
                    @else
                        Dokumentasi visual dari berbagai kegiatan dan momen berharga di {{ $schoolProfile->school_name ?? 'Sekolah Kami' }}
                    @endif
                </p>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            @php
                $kategoris = \App\Models\Kategori::where('is_active', true)->get();
                
                // Tentukan warna dan ikon untuk setiap kategori
                $categoryStyles = [
                    'kegiatan-sekolah' => [
                        'from' => 'from-blue-500',
                        'to' => 'to-blue-700',
                        'text' => 'text-blue-100',
                        'icon' => 'fa-school',
                        'delay' => '0'
                    ],
                    'fasilitas-sekolah' => [
                        'from' => 'from-green-500',
                        'to' => 'to-green-700',
                        'text' => 'text-green-100',
                        'icon' => 'fa-archway',
                        'delay' => '100'
                    ],
                    'prestasi' => [
                        'from' => 'from-purple-500',
                        'to' => 'to-purple-700',
                        'text' => 'text-purple-100',
                        'icon' => 'fa-trophy',
                        'delay' => '200'
                    ],
                    'default' => [
                        'from' => 'from-indigo-500',
                        'to' => 'to-indigo-700',
                        'text' => 'text-indigo-100',
                        'icon' => 'fa-image',
                        'delay' => '0'
                    ]
                ];
            @endphp
            
            <div class="mb-8">
                <form action="{{ route('gallery') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
                    <div class="relative w-full md:w-1/2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari galeri..." 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 pl-12">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button type="submit" class="w-full md:w-auto btn-hover bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold shadow-lg flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i> Cari
                    </button>
                </form>
            </div>
            
            @if(!$kategoris->isEmpty())
                <div class="flex flex-wrap gap-3 justify-center mb-8">
                    <a href="{{ route('gallery') }}" 
                       class="px-4 py-2 rounded-full {{ !isset($activeCategory) ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} font-medium transition-all duration-200 shadow-sm">
                        Semua Galeri
                    </a>
                    @foreach($kategoris as $kategori)
                        @php
                            $style = $categoryStyles[$kategori->slug] ?? $categoryStyles['default'];
                            $isActive = isset($activeCategory) && $activeCategory === $kategori->slug;
                        @endphp
                        <a href="{{ route('gallery.category', $kategori->slug) }}" 
                           class="px-4 py-2 rounded-full {{ $isActive ? 'bg-' . explode('-', $style['from'])[1] . '-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} font-medium transition-all duration-200 shadow-sm">
                            {{ $kategori->nama }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Gallery Grid Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            @if($galleries->isEmpty())
                <div class="text-center py-16">
                    <div class="text-6xl text-gray-300 mb-4"><i class="fas fa-images"></i></div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Tidak ada galeri ditemukan</h3>
                    <p class="text-gray-600">Silakan coba dengan kata kunci lain atau kembali lagi nanti.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($galleries as $gallery)
                        <div class="gallery-item bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-100"
                             data-aos="fade-up" data-aos-delay="{{ $loop->index % 4 * 100 }}">
                            <div class="image-container">
                                @if($gallery->image)
                                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                                         alt="{{ $gallery->title }}">
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $gallery->title }}</h3>
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                    <span><i class="far fa-calendar-alt mr-1"></i> {{ $gallery->created_at->format('d M Y') }}</span>
                                    @if($gallery->kategori)
                                        <span class="px-2 py-1 rounded-full text-xs" 
                                              style="background-color: rgba(99, 102, 241, 0.1); color: #4f46e5;">
                                            {{ $gallery->kategori->nama }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex justify-between items-center">
                                    <a href="{{ asset('storage/' . $gallery->image) }}" 
                                       data-lightbox="gallery" 
                                       data-title="{{ $gallery->title }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        <i class="far fa-eye mr-1"></i> Lihat
                                    </a>
                                    <a href="{{ route('gallery.download', $gallery->id) }}" 
                                       class="text-gray-600 hover:text-gray-800 text-sm font-medium" 
                                       title="Download Gambar">
                                        <i class="fas fa-download mr-1"></i> Unduh
                                    </a>
                                </div>
                                <div class="mt-3 flex items-center justify-between text-sm text-gray-600">
                                    @php
                                        $fp = request()->cookie('visitor_fingerprint') ?? request()->ip() . '|' . substr(hash('sha256', (string) request()->userAgent()), 0, 16);
                                        $alreadyLiked = isset($gallery->likes) ? $gallery->likes->firstWhere('visitor_fingerprint', $fp) : null;
                                    @endphp
                                    @if($alreadyLiked)
                                        <form action="{{ route('gallery.unlike', $gallery->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center text-pink-600 hover:text-pink-700">
                                                <i class="fas fa-heart mr-1"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('gallery.like', $gallery->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center text-gray-500 hover:text-pink-700">
                                                <i class="far fa-heart mr-1"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('gallery.detail', $gallery->id) }}#comments" class="inline-flex items-center">
                                        <i class="far fa-comment mr-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($galleries->hasPages())
                    <div class="mt-8">
                        {{ $galleries->withQueryString()->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>


     <!-- Team Section -->
     <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Tim Pengajar</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Tenaga pendidik profesional yang berdedikasi untuk mengembangkan potensi siswa</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="0">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                         alt="Kepala Sekolah" class="w-full h-64 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold mb-1 text-gray-800">Dr. Siti Rahayu</h3>
                        <p class="text-blue-600 font-medium mb-3">Kepala Sekolah</p>
                        <p class="text-gray-600 text-sm">Berpengalaman lebih dari 20 tahun di bidang pendidikan dengan gelar Doktor Manajemen Pendidikan</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                         alt="Wakil Kepala Sekolah" class="w-full h-64 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold mb-1 text-gray-800">Budi Santoso, M.Pd</h3>
                        <p class="text-blue-600 font-medium mb-3">Wakil Kepala Sekolah</p>
                        <p class="text-gray-600 text-sm">Spesialis kurikulum dengan pengalaman mengajar selama 15 tahun di berbagai jenjang pendidikan</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <img src="https://images.unsplash.com/photo-1580894732444-8ecded7900cd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                         alt="Guru Matematika" class="w-full h-64 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold mb-1 text-gray-800">Ani Wijaya, S.Pd</h3>
                        <p class="text-blue-600 font-medium mb-3">Guru Matematika</p>
                        <p class="text-gray-600 text-sm">Lulusan terbaik dari Universitas Pendidikan Indonesia dengan berbagai penghargaan di bidang pengajaran</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                    <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
                         alt="Guru Bahasa Inggris" class="w-full h-64 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold mb-1 text-gray-800">Ahmad Fauzi, M.A.</h3>
                        <p class="text-blue-600 font-medium mb-3">Guru Bahasa Inggris</p>
                        <p class="text-gray-600 text-sm">Berpengalaman mengajar di luar negeri dengan sertifikasi internasional dalam pengajaran bahasa</p>
                    </div>
                </div>
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
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'showImageNumberLabel': true,
        'alwaysShowNavOnTouchDevices': true
    });

    // Auto-hide toast messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.querySelector('.toast');
        if (toast) {
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    });
</script>
@endpush

@endsection