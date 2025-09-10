@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                <a href="{{ route('gallery') }}" class="inline-flex items-center text-blue-200 hover:text-white mb-4">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Galeri
                </a>
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-shadow leading-tight">
                    {{ $gallery->title }}
                </h1>
                <p class="text-blue-100">
                    <i class="far fa-calendar-alt mr-1"></i> {{ $gallery->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Gallery Detail Section -->
    <section class="py-12 bg-white">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 mb-8">
                <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" class="w-full h-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $gallery->kategori->name ?? 'Umum' }}
                            </span>
                        </div>
                        <div class="flex space-x-4 items-center">
                            @php
                                $fp = request()->cookie('visitor_fingerprint') ?? request()->ip() . '|' . substr(hash('sha256', (string) request()->userAgent()), 0, 16);
                                $alreadyLiked = $gallery->likes->firstWhere('visitor_fingerprint', $fp);
                            @endphp
                            @if($alreadyLiked)
                                <form action="{{ route('gallery.unlike', $gallery->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center text-pink-600 hover:text-pink-700">
                                        <i class="fas fa-heart mr-1"></i>
                                        <span>Batalkan</span>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('gallery.like', $gallery->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center text-gray-500 hover:text-pink-700">
                                        <i class="far fa-heart mr-1"></i>
                                        <span>Suka</span>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ asset('storage/' . $gallery->image) }}" 
                               class="text-blue-600 hover:text-blue-800" download>
                                <i class="fas fa-download"></i> Unduh
                            </a>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $gallery->title }}</h2>
                    @if($gallery->description)
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($gallery->description)) !!}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Comments -->
            <div id="comments" class="mt-10">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Komentar</h3>

                <div class="bg-white rounded-xl shadow p-6 mb-6">
                    <form action="{{ route('gallery.comment', $gallery->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Komentar</label>
                            <textarea name="content" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Kirim Komentar</button>
                        </div>
                    </form>
                </div>

                <div class="space-y-4">
                    @forelse($gallery->comments as $comment)
                        <div class="bg-white rounded-xl shadow p-4">
                            <div class="flex items-center justify-between mb-1">
                                <p class="font-semibold text-gray-800">{{ $comment->name }}</p>
                                <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                            <p class="text-gray-700">{{ $comment->content }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">Belum ada komentar.</p>
                    @endforelse
                </div>
            </div>

            @if($relatedGalleries->isNotEmpty())
                <div class="mt-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Galeri Lainnya</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($relatedGalleries as $related)
                            <div class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                                <div class="aspect-w-4 aspect-h-3">
                                    <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                    <div>
                                        <h4 class="text-white font-semibold text-lg">{{ $related->title }}</h4>
                                        <p class="text-gray-200 text-sm">{{ $related->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('gallery.detail', $related->id) }}" class="absolute inset-0 z-10">
                                    <span class="sr-only">Lihat {{ $related->title }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Punya Foto Kegiatan Sekolah?</h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto">Bagikan momen berharga di sekolah dengan mengirimkan foto kegiatan untuk ditampilkan di galeri kami.</p>
            <a href="{{ route('contact') }}" class="btn-hover inline-block bg-white text-blue-700 px-8 py-3 rounded-lg font-semibold text-lg shadow-lg">
                <i class="fas fa-paper-plane mr-2"></i> Kirim Foto
            </a>
        </div>
    </section>
@endsection
