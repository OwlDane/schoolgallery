@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-12 w-12 bg-green-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user-plus text-white text-xl"></i>
            </div>
            <h2 class="mt-6 text-3xl font-bold text-gray-900">Buat Akun Baru</h2>
            <p class="mt-2 text-sm text-gray-600">
                Sudah punya akun? 
                <a href="{{ route('guest.login') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                    Masuk di sini
                </a>
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('guest.register') }}" method="POST">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-blue-500"></i>Nama Lengkap
                    </label>
                    <input 
                        id="name" 
                        name="name" 
                        type="text" 
                        autocomplete="name" 
                        required 
                        value="{{ old('name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap Anda"
                    >
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-blue-500"></i>Email
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                        placeholder="Masukkan email Anda"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-blue-500"></i>Password
                    </label>
                    <div class="relative">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                            placeholder="Minimal 8 karakter"
                        >
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Password harus minimal 8 karakter</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-blue-500"></i>Konfirmasi Password
                    </label>
                    <div class="relative">
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Ulangi password Anda"
                        >
                        <button type="button" onclick="togglePasswordConfirmation()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password-confirmation-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input 
                        id="terms" 
                        name="terms" 
                        type="checkbox" 
                        required
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        Saya menyetujui 
                        <a href="#" class="text-blue-600 hover:text-blue-500">syarat dan ketentuan</a>
                    </label>
                </div>

                <div>
                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                    >
                        <i class="fas fa-user-plus mr-2"></i>
                        Daftar
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    Dengan mendaftar, Anda menyetujui untuk menerima informasi terkait sekolah melalui email.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('password-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
}

function togglePasswordConfirmation() {
    const passwordInput = document.getElementById('password_confirmation');
    const passwordIcon = document.getElementById('password-confirmation-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
}
</script>
@endsection
