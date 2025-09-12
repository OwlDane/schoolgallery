@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 py-8">
            @if(session('status'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm p-8">
                <div class="flex items-start space-x-6">
                    <!-- Profile Picture -->
                    <div class="relative">
                        <div class="w-24 h-24 bg-gray-200 rounded-full overflow-hidden flex items-center justify-center">
                            @if($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}" alt="Avatar" class="w-full h-full object-cover" />
                            @else
                                <i class="fas fa-user text-3xl text-gray-400"></i>
                            @endif
                        </div>
                        <label for="avatar" class="absolute bottom-0 right-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors cursor-pointer">
                            <i class="fas fa-camera text-sm"></i>
                        </label>
                    </div>

                    <!-- User Info -->
                    <div class="flex-1">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                           class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
                                    @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                           class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
                                    @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <input id="avatar" name="avatar" type="file" class="hidden" accept="image/*" />

                            <div class="border-t pt-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ubah Password (opsional)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                                        <input type="password" name="current_password" 
                                               class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                        @error('current_password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                        <input type="password" name="password" 
                                               class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                        @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                        <input type="password" name="password_confirmation" 
                                               class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-6 border-t">
                                <div class="flex space-x-4">
                                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center">
                                        <i class="fas fa-edit mr-2"></i> Simpan Perubahan
                                    </button>
                                </div>
                                
                                <!-- Logout Button -->
                                <form action="{{ route('guest.logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-6 py-2.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors flex items-center">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection


