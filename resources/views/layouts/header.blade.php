<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('meta_title', $schoolProfile->school_name ?? 'Galeri Sekolah')</title>
    <link rel="icon" type="image/png" href="{{ asset('eduspot.png') }}">
    @include('layouts.seo')
    @vite(['resources/css/app.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
    @stack('head')
</head>
<body class="bg-gray-50">
    @php
        $authRoutes = [
            'guest.login',
            'guest.register',
            'guest.password.request',
            'guest.password.reset',
            // alias used by Laravel notifications
            'password.reset',
        ];
        $hideNavbar = in_array(optional(Route::current())->getName(), $authRoutes, true);
    @endphp
    <!-- Navigation -->
    @unless($hideNavbar)
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Left: School brand -->
                <div class="flex items-center flex-1">
                    <a href="{{ route('home') }}" class="flex items-center">
                        @if($schoolProfile->school_logo)
                            <picture class="mr-3">
                                <source srcset="{{ asset('storage/' . preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $schoolProfile->school_logo)) }}" type="image/webp">
                                <img data-src="{{ asset('storage/' . $schoolProfile->school_logo) }}" loading="lazy" alt="Logo {{ $schoolProfile->school_name }}" class="h-10 w-auto">
                            </picture>
                        @else
                            <div class="bg-blue-600 text-white p-2 rounded-lg mr-3">
                                <i class="fas fa-school text-xl"></i>
                            </div>
                        @endif
                        <div>
                            <h1 class="text-lg font-bold text-gray-800">{{ $schoolProfile->school_name ?? 'Galeri Sekolah' }}</h1>
                            <p class="text-xs text-gray-500">Pendidikan Berkualitas</p>
                        </div>
                    </a>
                </div>
                
                <!-- Center: Main nav -->
                <div class="hidden md:flex items-center space-x-4 justify-center flex-1">
                    <a href="{{ route('home') }}" class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2"><i class="fas fa-home text-blue-500"></i><span>Beranda</span></a>
                    <a href="{{ route('about') }}" class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2"><i class="fas fa-info-circle text-blue-500"></i><span>Tentang</span></a>

                    @php($newsCategories = \App\Models\NewsCategory::active()->ordered()->get())
                    <div class="relative group">
                        <button id="newsDropdownBtn" class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2">
                            <i class="fas fa-newspaper text-blue-500"></i><span>Berita</span>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        @if($newsCategories->isNotEmpty())
                        <div id="newsDropdownMenu" class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-opacity duration-200 absolute left-0 mt-2 w-56 bg-white border border-gray-100 rounded-lg shadow-lg py-2 z-50">
                            <a href="{{ route('news') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Berita Terkini</a>
                            <div class="my-2 border-t border-gray-100"></div>
                            @foreach($newsCategories as $cat)
                                <a href="{{ route('news', ['category' => $cat->slug]) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">{{ $cat->name }}</a>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('gallery') }}" class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2"><i class="fas fa-images text-blue-500"></i><span>Galeri</span></a>
                    
                    <a href="{{ route('contact') }}" class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2"><i class="fas fa-envelope text-blue-500"></i><span>Kontak</span></a>
                    
                </div>

                <!-- Right: Profile/Login -->
                <div class="hidden md:flex items-center justify-end flex-1">
                    @auth
                        <div class="relative group">
                            <button class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2">
                                <i class="fas fa-user text-blue-500"></i>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <a href="{{ route('profile.edit', ['tab' => 'akun']) }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-user mr-2"></i> Pengaturan Akun
                                </a>
                                <a href="{{ route('profile.edit', ['tab' => 'aktivitas']) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-history mr-2"></i> Aktivitas
                                </a>
                                <a href="{{ route('profile.edit', ['tab' => 'favorit']) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="far fa-bookmark mr-2"></i> Favorit Saya
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form action="{{ route('guest.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('guest.login') }}" class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2">
                            <i class="fas fa-sign-in-alt text-blue-500"></i><span>Login</span>
                        </a>
                    @endauth
                </div>
                
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-500 hover:text-blue-600 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-100 shadow-inner">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md font-medium transition-all"><i class="fas fa-home mr-2 text-blue-500"></i> Beranda</a>
                <a href="{{ route('about') }}" class="block py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md font-medium transition-all"><i class="fas fa-info-circle mr-2 text-blue-500"></i> Tentang</a>
                <div>
                    <button id="mobile-news-toggle" class="w-full flex items-center justify-between py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md font-medium transition-all">
                        <span><i class="fas fa-newspaper mr-2 text-blue-500"></i> Berita</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    @php($newsCategoriesMobile = \App\Models\NewsCategory::active()->ordered()->get())
                    <div id="mobile-news-submenu" class="hidden ml-8 mt-1 space-y-1">
                        <a href="{{ route('news') }}" class="block py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all">Semua Berita</a>
                        @foreach($newsCategoriesMobile as $cat)
                            <a href="{{ route('news', ['category' => $cat->slug]) }}" class="block py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('gallery') }}" class="block py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md font-medium transition-all"><i class="fas fa-images mr-2 text-blue-500"></i> Galeri</a>
                <a href="{{ route('contact') }}" class="block py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md font-medium transition-all"><i class="fas fa-envelope mr-2 text-blue-500"></i> Kontak</a>
                
                <!-- Guest Authentication Mobile -->
                @auth
                    <div class="border-t border-gray-200 pt-2 mt-2 space-y-1">
                        <div class="px-3 py-2 text-sm text-gray-500 font-medium">
                            <i class="fas fa-user mr-2"></i> {{ Auth::user()->name }}
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all">
                            <i class="fas fa-user-edit mr-2"></i> Profil Saya
                        </a>
                        
                        <form action="{{ route('guest.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left py-2 px-3 text-red-600 hover:bg-red-50 rounded-md transition-all">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <a href="{{ route('guest.login') }}" class="block py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md font-medium transition-all">
                            <i class="fas fa-sign-in-alt mr-2 text-blue-500"></i> Login
                        </a>
                    </div>
                @endauth
                </div>
            </div>
        </div>
    </nav>
@endunless