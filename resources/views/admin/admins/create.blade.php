@extends('admin.layouts.app')

@section('title', 'Tambah Admin')

@section('content')
<div class="container-fluid">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-user-plus text-red-500 mr-3"></i>
            Tambah Admin
        </h1>
        <p class="text-gray-600 mt-1">Buat akun administrator baru untuk mengelola sistem</p>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <div class="p-6">
            <form action="{{ route('admin.admins.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-id-card"></i></span>
                            <input id="name" name="name" type="text" required value="{{ old('name') }}" class="pl-10 block w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-gray-800" />
                            @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-envelope"></i></span>
                            <input id="email" name="email" type="email" required value="{{ old('email') }}" class="pl-10 block w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-gray-800" />
                            @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-lock"></i></span>
                            <input id="password" name="password" type="password" required class="pl-10 block w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-gray-800" />
                            @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-lock"></i></span>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="pl-10 block w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-gray-800" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-user-shield"></i></span>
                            <select id="role" name="role" required class="pl-10 block w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-gray-800">
                                <option value="">Pilih Role</option>
                                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center select-none">
                            <input class="rounded text-red-600 focus:ring-red-500" type="checkbox" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700"><i class="fas fa-toggle-on mr-1"></i>Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow transition-colors">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
