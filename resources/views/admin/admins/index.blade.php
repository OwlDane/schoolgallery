@extends('admin.layouts.app')

@section('title', 'Manajemen Admin')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-users-cog text-red-500 mr-3"></i>
                Manajemen Admin
            </h1>
            <p class="text-gray-600 mt-1">Kelola akun admin dan izin akses sistem</p>
        </div>
        <a href="{{ route('admin.admins.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg flex items-center transition-colors shadow-lg">
            <i class="fas fa-plus mr-2"></i> Tambah Admin
        </a>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
    @if(is_array(session('success')))
        <!-- Password Reset Success -->
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-check-circle mr-3 mt-1 text-green-500"></i>
                <div class="flex-1">
                    <h4 class="font-semibold text-lg mb-2">{{ session('success')['title'] }}</h4>
                    <p class="mb-3">{{ session('success')['message'] }}</p>
                    
                    <!-- Password Display -->
                    <div class="bg-white border border-green-300 rounded-lg p-4 mb-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Password Baru:</p>
                                <div class="flex items-center">
                                    <code id="newPassword" class="bg-gray-100 px-3 py-2 rounded font-mono text-lg font-bold text-gray-800">{{ session('success')['new_password'] }}</code>
                                    <button onclick="copyPassword()" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors">
                                        <i class="fas fa-copy mr-1"></i> Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Admin Info -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <p class="text-sm"><strong>Admin:</strong> {{ session('success')['admin_name'] }}</p>
                        <p class="text-sm"><strong>Email:</strong> {{ session('success')['admin_email'] }}</p>
                    </div>
                    
                    <div class="mt-3 text-sm text-green-600">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <strong>Penting:</strong> Simpan password ini dengan aman. Password tidak akan ditampilkan lagi.
                    </div>
                </div>
                <button type="button" class="ml-4 text-green-500 hover:text-green-700" onclick="this.parentElement.parentElement.style.display='none'">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
    @else
        <!-- Regular Success -->
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="ml-auto text-green-500 hover:text-green-700" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
@endif

@if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        {{ session('error') }}
        <button type="button" class="ml-auto text-red-500 hover:text-red-700" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

<!-- Admin Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($admins as $admin)
    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
        <!-- Card Header -->
        <div class="bg-gradient-to-r {{ $admin->role === 'super_admin' ? 'from-red-500 to-red-600' : 'from-blue-500 to-blue-600' }} p-4 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                        <i class="fas {{ $admin->role === 'super_admin' ? 'fa-crown' : 'fa-user' }} text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">{{ $admin->name }}</h3>
                        <p class="text-sm opacity-90">{{ $admin->email }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $admin->is_active ? 'bg-green-500' : 'bg-gray-500' }}">
                        {{ $admin->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <!-- Role Badge -->
            <div class="mb-4">
                <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold {{ $admin->role === 'super_admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                    <i class="fas {{ $admin->role === 'super_admin' ? 'fa-crown' : 'fa-user' }} mr-1"></i>
                    {{ $admin->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                </span>
            </div>

            <!-- Admin Info -->
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-id-badge w-4 mr-2"></i>
                    <span>ID: {{ $admin->id }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-calendar-plus w-4 mr-2"></i>
                    <span>Dibuat: {{ $admin->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-clock w-4 mr-2"></i>
                    <span>Terakhir update: {{ $admin->updated_at->diffForHumans() }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2">
                <!-- Edit Button -->
                <a href="{{ route('admin.admins.edit', $admin) }}" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>

                <!-- Reset Password Button -->
                <form action="{{ route('admin.admins.reset-password', $admin) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center" onclick="return confirm('Reset password admin ini?')">
                        <i class="fas fa-key mr-1"></i> Reset
                    </button>
                </form>

                <!-- Delete Button (if not current user) -->
                @if($admin->id !== auth('admin')->id())
                <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center" onclick="return confirm('Hapus admin ini? Tindakan ini tidak dapat dibatalkan.')">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </form>
                @else
                <div class="flex-1 bg-gray-300 text-gray-500 px-3 py-2 rounded-lg text-sm font-medium flex items-center justify-center cursor-not-allowed">
                    <i class="fas fa-lock mr-1"></i> Diri Sendiri
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="col-span-full">
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <div class="bg-gray-100 rounded-full p-6 inline-block mb-4">
                <i class="fas fa-users text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Admin</h3>
            <p class="text-gray-600 mb-6">Mulai dengan menambahkan admin pertama untuk mengelola sistem</p>
            <a href="{{ route('admin.admins.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg inline-flex items-center transition-colors">
                <i class="fas fa-plus mr-2"></i> Tambah Admin Pertama
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Statistics Summary -->
@if($admins->count() > 0)
<div class="mt-8 bg-white rounded-xl shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
        Ringkasan Admin
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-blue-50 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $admins->count() }}</div>
            <div class="text-sm text-blue-800">Total Admin</div>
        </div>
        <div class="bg-green-50 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $admins->where('is_active', true)->count() }}</div>
            <div class="text-sm text-green-800">Admin Aktif</div>
        </div>
        <div class="bg-red-50 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-red-600">{{ $admins->where('role', 'super_admin')->count() }}</div>
            <div class="text-sm text-red-800">Super Admin</div>
        </div>
        <div class="bg-purple-50 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $admins->where('role', 'admin')->count() }}</div>
            <div class="text-sm text-purple-800">Admin Biasa</div>
        </div>
    </div>
</div>
@endif

<!-- JavaScript untuk konfirmasi -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 10 seconds (kecuali password reset)
    setTimeout(function() {
        const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
        alerts.forEach(function(alert) {
            // Jangan auto-hide jika ada password reset
            if (!alert.querySelector('#newPassword')) {
                alert.style.display = 'none';
            }
        });
    }, 10000);
});

// Function untuk copy password
function copyPassword() {
    const passwordElement = document.getElementById('newPassword');
    const password = passwordElement.textContent;
    
    // Copy ke clipboard
    navigator.clipboard.writeText(password).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-1"></i> Copied!';
        button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
        button.classList.add('bg-green-500');
        
        // Reset button after 2 seconds
        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-500');
            button.classList.add('bg-blue-500', 'hover:bg-blue-600');
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Gagal menyalin password. Silakan copy manual: ' + password);
    });
}
</script>
@endsection