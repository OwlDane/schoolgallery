@extends('layouts.app')

@section('content')
    <!-- Hero with image/background -->
    <section class="relative text-white">
        <div class="absolute inset-0">
            @if($event->image)
                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
            @else
                <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?auto=format&fit=crop&w=1600&q=60" alt="{{ $event->title }}" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/70"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 py-24">
            <a href="{{ route('news') }}" class="inline-flex items-center text-blue-200 hover:text-white mb-4 transition-colors duration-300 bg-black/30 px-4 py-2 rounded-full backdrop-blur-sm"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
            <h1 class="text-3xl md:text-5xl font-bold mb-4 leading-tight text-shadow-lg">{{ $event->title }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-sm md:text-base text-blue-100 bg-black/30 inline-block px-4 py-2 rounded-lg backdrop-blur-sm">
                <span class="inline-flex items-center"><i class="far fa-clock mr-2 text-yellow-300"></i>{{ $event->start_at->format('d M Y H:i') }} @if($event->end_at) - {{ $event->end_at->format('d M Y H:i') }} @endif</span>
                @if($event->location)
                    <span class="inline-flex items-center"><i class="fas fa-map-marker-alt mr-2 text-yellow-300"></i>{{ $event->location }}</span>
                @endif
            </div>
        </div>
    </section>

    <!-- Content with sidebar -->
    <section class="py-12 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8">
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                    <div class="prose prose-lg max-w-none">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>
            </div>
            <aside class="lg:col-span-4">
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300 border border-gray-100 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center"><i class="fas fa-info-circle text-blue-600 mr-2"></i>Detail Event</h3>
                    <ul class="space-y-4 text-sm md:text-base text-gray-700">
                        <li class="flex items-start bg-blue-50 p-3 rounded-lg">
                            <i class="far fa-calendar-alt w-5 text-blue-600 mt-0.5"></i>
                            <span class="ml-2">
                                <span class="font-medium">{{ $event->start_at->format('l, d M Y H:i') }}</span>
                                @if($event->end_at)
                                    <br><span class="text-gray-500">sampai</span> <span class="font-medium">{{ $event->end_at->format('l, d M Y H:i') }}</span>
                                @endif
                            </span>
                        </li>
                        @if($event->location)
                        <li class="flex items-start bg-green-50 p-3 rounded-lg">
                            <i class="fas fa-map-marker-alt w-5 text-green-600 mt-0.5"></i>
                            <span class="ml-2 font-medium">{{ $event->location }}</span>
                        </li>
                        @endif
                        @if($event->image)
                        <li class="mt-4">
                            <div class="rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="rounded-lg w-full object-cover transform hover:scale-105 transition-transform duration-500">
                            </div>
                        </li>
                        @endif
                    </ul>
                    <div class="border-t mt-6 pt-4">
                        <p class="text-sm text-gray-500 mb-2 font-medium">Bagikan acara ini</p>
                        <div class="flex items-center gap-3">
                            <a href="#" class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors duration-300 shadow-md hover:shadow-lg"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="bg-blue-400 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors duration-300 shadow-md hover:shadow-lg"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="bg-green-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-green-700 transition-colors duration-300 shadow-md hover:shadow-lg"><i class="fab fa-whatsapp"></i></a>
                            <a href="#" class="bg-red-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-red-700 transition-colors duration-300 shadow-md hover:shadow-lg"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection


