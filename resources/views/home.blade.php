<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $schoolProfile->school_name ?? 'Galeri Sekolah' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    @if($schoolProfile->school_logo)
                        <img src="{{ asset('storage/' . $schoolProfile->school_logo) }}" alt="Logo" class="h-10 w-10 mr-3">
                    @endif
                    <h1 class="text-xl font-bold text-gray-800">{{ $schoolProfile->school_name ?? 'Galeri Sekolah' }}</h1>
                </div>
                <div class="hidden md:flex space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Beranda</a>
                    <a href="{{ route('news') }}" class="text-gray-700 hover:text-blue-600">Berita</a>
                    <a href="{{ route('gallery') }}" class="text-gray-700 hover:text-blue-600">Galeri</a>
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-blue-600">Tentang</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-blue-600">Kontak</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-blue-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">Selamat Datang di {{ $schoolProfile->school_name ?? 'Sekolah Kami' }}</h2>
            @if($schoolProfile->description)
                <p class="text-xl mb-8">{{ $schoolProfile->description }}</p>
            @endif
            <div class="flex justify-center space-x-4">
                <a href="{{ route('gallery') }}" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">
                    Lihat Galeri
                </a>
                <a href="{{ route('news') }}" class="border border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600">
                    Baca Berita
                </a>
            </div>
        </div>
    </section>

    <!-- Latest News -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-12">Berita Terbaru</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($latestNews as $news)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        @if($news->image)
                            <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-6">
                            <h4 class="text-xl font-semibold mb-2">{{ $news->title }}</h4>
                            <p class="text-gray-600 mb-4">{{ Str::limit(strip_tags($news->content), 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">{{ $news->published_at->format('d M Y') }}</span>
                                <a href="{{ route('news.detail', $news->slug) }}" class="text-blue-600 hover:text-blue-800">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center">
                        <p class="text-gray-500">Belum ada berita tersedia</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Gallery Preview -->
    <section class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4">
            <h3 class="text-3xl font-bold text-center mb-12">Galeri Foto</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($featuredGalleries as $gallery)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" class="w-full h-48 object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-white text-center px-4">{{ $gallery->title }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center">
                        <p class="text-gray-500">Belum ada galeri tersedia</p>
                    </div>
                @endforelse
            </div>
            @if($featuredGalleries->count() > 0)
                <div class="text-center mt-8">
                    <a href="{{ route('gallery') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                        Lihat Semua Galeri
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-lg font-semibold mb-4">{{ $schoolProfile->school_name ?? 'Sekolah Kami' }}</h4>
                    @if($schoolProfile->address)
                        <p class="mb-2"><i class="fas fa-map-marker-alt mr-2"></i>{{ $schoolProfile->address }}</p>
                    @endif
                    @if($schoolProfile->phone)
                        <p class="mb-2"><i class="fas fa-phone mr-2"></i>{{ $schoolProfile->phone }}</p>
                    @endif
                    @if($schoolProfile->email)
                        <p class="mb-2"><i class="fas fa-envelope mr-2"></i>{{ $schoolProfile->email }}</p>
                    @endif
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Menu</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="hover:text-blue-400">Beranda</a></li>
                        <li><a href="{{ route('news') }}" class="hover:text-blue-400">Berita</a></li>
                        <li><a href="{{ route('gallery') }}" class="hover:text-blue-400">Galeri</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-blue-400">Tentang</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-blue-400">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Ikuti Kami</h4>
                    <div class="flex space-x-4">
                        @if($schoolProfile->facebook_url)
                            <a href="{{ $schoolProfile->facebook_url }}" class="text-blue-400 hover:text-blue-300">
                                <i class="fab fa-facebook text-2xl"></i>
                            </a>
                        @endif
                        @if($schoolProfile->instagram_url)
                            <a href="{{ $schoolProfile->instagram_url }}" class="text-pink-400 hover:text-pink-300">
                                <i class="fab fa-instagram text-2xl"></i>
                            </a>
                        @endif
                        @if($schoolProfile->youtube_url)
                            <a href="{{ $schoolProfile->youtube_url }}" class="text-red-400 hover:text-red-300">
                                <i class="fab fa-youtube text-2xl"></i>
                            </a>
                        @endif
                        @if($schoolProfile->twitter_url)
                            <a href="{{ $schoolProfile->twitter_url }}" class="text-blue-300 hover:text-blue-200">
                                <i class="fab fa-twitter text-2xl"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p>&copy; {{ date('Y') }} {{ $schoolProfile->school_name ?? 'Galeri Sekolah' }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>