@extends('layouts.app')

@section('content')
    <!-- News Detail Hero -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                <a href="{{ route('news') }}" class="inline-flex items-center text-blue-200 hover:text-white mb-4 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Berita
                </a>
                <h1 class="text-3xl md:text-4xl font-bold mb-4 text-shadow leading-tight max-w-4xl mx-auto">
                    {{ $news->title }}
                </h1>
                <div class="flex items-center justify-center gap-4 text-sm text-blue-200">
                    <span><i class="far fa-calendar-alt mr-1"></i> {{ $news->created_at->format('d M Y') }}</span>
                    <span><i class="far fa-user mr-1"></i> Admin</span>
                </div>
            </div>
        </div>
    </section>

    <!-- News Content -->
    <section class="py-12 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-xl overflow-hidden shadow-lg mb-8" data-aos="fade-up">
                @if($news->image)
                    <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="w-full h-auto object-cover">
                @else
                    <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" alt="{{ $news->title }}" class="w-full h-auto object-cover">
                @endif
                
                <div class="p-8">
                    @if($news->newsCategory)
                        <div class="mb-4">
                            <span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-3 py-1 rounded-full font-semibold">
                                {{ $news->newsCategory->name }}
                            </span>
                        </div>
                    @endif
                    <div class="prose prose-lg max-w-none">
                        {!! $news->content !!}
                    </div>
                </div>
            </div>

            <!-- Comments -->
            <div id="comments" class="mt-10">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Komentar</h3>
                <div class="bg-white rounded-xl shadow p-6 mb-6">
                    <form action="{{ route('news.comment', $news->slug) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Nama (opsional)</label>
                            <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        </div>
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
                    @forelse($news->comments as $comment)
                        <div class="bg-white rounded-xl shadow p-4">
                            <div class="flex items-center justify-between mb-1">
                                <p class="font-semibold text-gray-800">{{ $comment->name ?? 'Pengunjung' }}</p>
                                <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                            <p class="text-gray-700">{{ $comment->content }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">Belum ada komentar.</p>
                    @endforelse
                </div>
            </div>

            <!-- Share Buttons -->
            <div class="flex items-center justify-center gap-4 my-8" data-aos="fade-up">
                <span class="text-gray-700 font-medium">Bagikan:</span>
                <a href="#" class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="bg-blue-400 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="bg-green-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-green-700 transition-colors">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a href="#" class="bg-blue-800 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-900 transition-colors">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Related News -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Berita Terkait</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Baca juga berita lainnya dari {{ $schoolProfile->school_name ?? 'Sekolah Kami' }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($relatedNews as $item)
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="relative">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-40 object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="{{ $item->title }}" class="w-full h-40 object-cover">
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                    {{ $item->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold mb-2 text-gray-800 line-clamp-2">{{ $item->title }}</h3>
                            <a href="{{ route('news.detail', $item->slug) }}" class="inline-flex items-center text-blue-600 font-medium hover:text-blue-800 transition-colors text-sm">
                                Baca selengkapnya <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-8">
                        <p class="text-gray-600">Tidak ada berita terkait.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('news') }}" class="btn-hover inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold shadow-lg">
                    <i class="fas fa-newspaper mr-2"></i> Lihat Semua Berita
                </a>
            </div>
        </div>
    </section>
@endsection