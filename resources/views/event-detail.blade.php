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
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/60"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 py-16">
            <a href="{{ route('news') }}" class="inline-flex items-center text-blue-200 hover:text-white mb-4"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
            <h1 class="text-3xl md:text-4xl font-bold mb-3 leading-tight">{{ $event->title }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-sm text-blue-100">
                <span class="inline-flex items-center"><i class="far fa-clock mr-2"></i>{{ $event->start_at->format('d M Y H:i') }} @if($event->end_at) - {{ $event->end_at->format('d M Y H:i') }} @endif</span>
                @if($event->location)
                    <span class="inline-flex items-center"><i class="fas fa-map-marker-alt mr-2"></i>{{ $event->location }}</span>
                @endif
            </div>
        </div>
    </section>

    <!-- Content with sidebar -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="prose prose-lg max-w-none">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>
            </div>
            <aside class="lg:col-span-4">
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Event</h3>
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li class="flex items-start">
                            <i class="far fa-calendar-alt w-5 text-gray-400 mt-0.5"></i>
                            <span>
                                {{ $event->start_at->format('l, d M Y H:i') }}
                                @if($event->end_at)
                                    <br><span class="text-gray-500">sampai</span> {{ $event->end_at->format('l, d M Y H:i') }}
                                @endif
                            </span>
                        </li>
                        @if($event->location)
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt w-5 text-gray-400 mt-0.5"></i>
                            <span>{{ $event->location }}</span>
                        </li>
                        @endif
                        @if($event->image)
                        <li>
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="rounded-lg w-full object-cover">
                        </li>
                        @endif
                    </ul>
                    <div class="border-t mt-6 pt-4">
                        <p class="text-sm text-gray-500 mb-2">Bagikan acara ini</p>
                        <div class="flex items-center gap-2">
                            <a href="#" class="bg-blue-600 text-white w-9 h-9 rounded-full flex items-center justify-center hover:bg-blue-700"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="bg-blue-400 text-white w-9 h-9 rounded-full flex items-center justify-center hover:bg-blue-500"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="bg-green-600 text-white w-9 h-9 rounded-full flex items-center justify-center hover:bg-green-700"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection


