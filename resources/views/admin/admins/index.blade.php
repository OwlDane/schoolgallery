@extends('admin.layouts.app')

@section('title', 'Manajemen Admin')

@section('content')
<!-- Header dengan animasi dan gradien -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="animate-fade-in">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <span class="bg-gradient-to-r from-red-500 to-blue-600 p-2 rounded-lg text-white mr-3 shadow-lg">
                    <i class="fas fa-users-cog"></i>
                </span>
                Manajemen Admin
            </h1>
            <p class="text-gray-600 mt-2 flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                Kelola akun admin dan izin akses sistem
            </p>
        </div>
        <a href="{{ route('admin.admins.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-plus-circle mr-2"></i> Tambah Admin
        </a>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-64">
            <input type="text" id="searchAdmin" placeholder="Cari admin..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <select id="filterRole" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                <option value="">Semua Role</option>
                <option value="super_admin">Super Admin</option>
                <option value="admin">Admin</option>
            </select>
            <select id="filterStatus" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
            </select>
        </div>
    </div>
</div>

<!-- Alert Messages dengan animasi -->
@if(session('success'))
    @if(!is_array(session('success')))
        <!-- Regular Success -->
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm flex items-center animate-fade-in-down">
            <div class="bg-green-200 rounded-full p-2 mr-3">
                <i class="fas fa-check text-green-600"></i>
            </div>
            <div>
                <h4 class="font-medium">Berhasil!</h4>
                <p>{{ session('success') }}</p>
            </div>
            <button type="button" class="ml-auto text-green-500 hover:text-green-700" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
@endif

@if(session('error'))
    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm flex items-center animate-fade-in-down">
        <div class="bg-red-200 rounded-full p-2 mr-3">
            <i class="fas fa-exclamation text-red-600"></i>
        </div>
        <div>
            <h4 class="font-medium">Error!</h4>
            <p>{{ session('error') }}</p>
        </div>
        <button type="button" class="ml-auto text-red-500 hover:text-red-700" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

<!-- Admin Cards Grid dengan animasi hover -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="adminCards">
    @forelse($admins as $admin)
    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 admin-card" 
         data-role="{{ $admin->role }}" 
         data-status="{{ $admin->is_active ? 'active' : 'inactive' }}">
        <!-- Card Header dengan gradien yang lebih menarik -->
        <div class="bg-gradient-to-r {{ $admin->role === 'super_admin' ? 'from-purple-600 to-indigo-700' : 'from-blue-500 to-cyan-600' }} p-5 text-white relative overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-white opacity-10"></div>
            <div class="absolute -right-3 -bottom-6 w-16 h-16 rounded-full bg-white opacity-10"></div>
            
            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center">
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4 shadow-lg border-2 border-white border-opacity-30">
                        <i class="fas {{ $admin->role === 'super_admin' ? 'fa-crown' : 'fa-user-shield' }} text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl">{{ $admin->name }}</h3>
                        <p class="text-sm opacity-90 flex items-center">
                            <i class="fas fa-envelope mr-1"></i> {{ $admin->email }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $admin->is_active ? 'bg-green-500' : 'bg-gray-500' }} shadow">
                        <span class="w-2 h-2 rounded-full {{ $admin->is_active ? 'bg-white animate-pulse' : 'bg-gray-300' }} mr-1"></span>
                        {{ $admin->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Card Body dengan layout yang lebih baik -->
        <div class="p-6">
            <!-- Role Badge dengan desain yang lebih menarik -->
            <div class="mb-5">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $admin->role === 'super_admin' ? 'bg-gradient-to-r from-purple-100 to-indigo-100 text-indigo-800' : 'bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800' }} shadow-sm">
                    <i class="fas {{ $admin->role === 'super_admin' ? 'fa-crown' : 'fa-user-shield' }} mr-2"></i>
                    {{ $admin->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                </span>
            </div>

            <!-- Admin Info dengan ikon yang lebih menarik -->
            <div class="space-y-3 mb-5">
                <div class="flex items-center text-sm text-gray-600">
                    <div class="bg-gray-100 rounded-full p-1.5 mr-3">
                        <i class="fas fa-id-badge text-gray-500"></i>
                    </div>
                    <span>ID: <span class="font-medium text-gray-800">{{ $admin->id }}</span></span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <div class="bg-gray-100 rounded-full p-1.5 mr-3">
                        <i class="fas fa-calendar-plus text-gray-500"></i>
                    </div>
                    <span>Dibuat: <span class="font-medium text-gray-800">{{ $admin->created_at->format('d/m/Y H:i') }}</span></span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <div class="bg-gray-100 rounded-full p-1.5 mr-3">
                        <i class="fas fa-clock text-gray-500"></i>
                    </div>
                    <span>Update: <span class="font-medium text-gray-800">{{ $admin->updated_at->diffForHumans() }}</span></span>
                </div>
            </div>

            <!-- Action Buttons dengan desain yang lebih menarik -->
            <div class="flex flex-wrap gap-2">
                <!-- Edit Button -->
                <a href="{{ route('admin.admins.edit', $admin) }}" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white px-3 py-2.5 rounded-lg text-sm font-medium transition-all flex items-center justify-center group">
                    <i class="fas fa-edit mr-2 group-hover:animate-bounce"></i> Edit
                </a>

                <!-- Reset Password Link -->
                <a href="{{ route('admin.admins.edit', $admin) }}#reset" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2.5 rounded-lg text-sm font-medium transition-all flex items-center justify-center group">
                    <i class="fas fa-key mr-2 group-hover:animate-bounce"></i> Reset
                </a>

                <!-- Toggle Active Button -->
                @if($admin->id !== auth('admin')->id())
                <form action="{{ route('admin.admins.toggle-active', $admin) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full {{ $admin->is_active ? 'bg-gray-500 hover:bg-gray-600' : 'bg-green-600 hover:bg-green-700' }} text-white px-3 py-2.5 rounded-lg text-sm font-medium transition-all flex items-center justify-center group" onclick="return confirm('Ubah status aktif admin ini?')">
                        <i class="fas {{ $admin->is_active ? 'fa-user-slash' : 'fa-user-check' }} mr-2 group-hover:animate-bounce"></i> {{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
                @endif

                <!-- Delete Button (if not current user) -->
                @if($admin->id !== auth('admin')->id())
                <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-2.5 rounded-lg text-sm font-medium transition-all flex items-center justify-center group" onclick="return confirm('Hapus admin ini? Tindakan ini tidak dapat dibatalkan.')">
                        <i class="fas fa-trash mr-2 group-hover:animate-bounce"></i> Hapus
                    </button>
                </form>
                @else
                <div class="flex-1 bg-gray-200 text-gray-500 px-3 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center cursor-not-allowed">
                    <i class="fas fa-lock mr-2"></i> Diri Sendiri
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State dengan ilustrasi yang lebih menarik -->
    <div class="col-span-full">
        <div class="bg-white rounded-xl shadow-md p-12 text-center border border-dashed border-gray-300">
            <div class="bg-blue-50 rounded-full p-8 inline-block mb-6 animate-pulse">
                <i class="fas fa-users text-5xl text-blue-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Admin</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">Mulai dengan menambahkan admin pertama untuk mengelola sistem dan mengatur hak akses</p>
            <a href="{{ route('admin.admins.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-8 py-4 rounded-lg inline-flex items-center transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Admin Pertama
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Statistics Summary dengan desain yang lebih menarik -->
@if($admins->count() > 0)
<div class="mt-10 bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
            <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
            Ringkasan Admin
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 text-center transform transition-all hover:scale-105 shadow-sm hover:shadow-md border border-green-100">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 text-green-500 mb-4">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-green-600 mb-1">{{ $admins->where('is_active', true)->count() }}</div>
                <div class="text-sm font-medium text-green-800">Admin Aktif</div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-6 text-center transform transition-all hover:scale-105 shadow-sm hover:shadow-md border border-purple-100">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 text-purple-500 mb-4">
                    <i class="fas fa-crown text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-purple-600 mb-1">{{ $admins->where('role', 'super_admin')->count() }}</div>
                <div class="text-sm font-medium text-purple-800">Super Admin</div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-6 text-center transform transition-all hover:scale-105 shadow-sm hover:shadow-md border border-blue-100">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-500 mb-4">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-blue-600 mb-1">{{ $admins->where('role', 'admin')->count() }}</div>
                <div class="text-sm font-medium text-blue-800">Admin Biasa</div>
            </div>
            
            <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-xl p-6 text-center transform transition-all hover:scale-105 shadow-sm hover:shadow-md border border-red-100">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 text-red-500 mb-4">
                    <i class="fas fa-user-slash text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-red-600 mb-1">{{ $admins->where('is_active', false)->count() }}</div>
                <div class="text-sm font-medium text-red-800">Admin Nonaktif</div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- JavaScript untuk filter dan animasi -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 10 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
        alerts.forEach(function(alert) {
            if (!alert.querySelector('#newPassword')) {
                alert.style.opacity = '0';
                setTimeout(() => alert.style.display = 'none', 300);
            }
        });
    }, 10000);

    // Filter functionality
    const searchInput = document.getElementById('searchAdmin');
    const filterRole = document.getElementById('filterRole');
    const filterStatus = document.getElementById('filterStatus');
    const adminCards = document.querySelectorAll('.admin-card');

    function filterCards() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleFilter = filterRole.value;
        const statusFilter = filterStatus.value;

        adminCards.forEach(card => {
            const cardText = card.textContent.toLowerCase();
            const cardRole = card.dataset.role;
            const cardStatus = card.dataset.status;
            
            const matchesSearch = searchTerm === '' || cardText.includes(searchTerm);
            const matchesRole = roleFilter === '' || cardRole === roleFilter;
            const matchesStatus = statusFilter === '' || cardStatus === statusFilter;
            
            if (matchesSearch && matchesRole && matchesStatus) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterCards);
    filterRole.addEventListener('change', filterCards);
    filterStatus.addEventListener('change', filterCards);
});

// Function untuk copy password
function copyPassword() {
    const passwordElement = document.getElementById('newPassword');
    const password = passwordElement.textContent;
    
    navigator.clipboard.writeText(password).then(function() {
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-1"></i> Copied!';
        button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
        button.classList.add('bg-green-500');
        
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

<style>
/* Animasi untuk elemen-elemen UI */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fade-in-down {
    animation: fadeInDown 0.5s ease-out;
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}
</style>
@endsection