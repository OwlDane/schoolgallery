@extends('admin.layouts.app')

@section('title', 'Edit Admin')

@section('content')
<div class="container-fluid">
	<div class="mb-6">
		<h1 class="text-2xl font-bold text-gray-800 flex items-center">
			<i class="fas fa-user-cog text-red-500 mr-3"></i>
			Edit Admin
		</h1>
		<p class="text-gray-600 mt-1">Kelola informasi dan status akun administrator</p>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
		<!-- Form Card -->
		<div class="lg:col-span-2 bg-white rounded-xl shadow-md border border-gray-100">
			<div class="p-6">
				<form action="{{ route('admin.admins.update', $admin) }}" method="POST" class="space-y-6">
					@csrf
					@method('PUT')

					<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
						<div>
							<label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
							<div class="relative">
								<span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
									<i class="fas fa-id-card"></i>
								</span>
								<input id="name" name="name" type="text" required value="{{ old('name', $admin->name) }}" class="pl-10 block w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-gray-800" />
								@error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
							</div>
						</div>

						<div>
							<label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
							<div class="relative">
								<span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
									<i class="fas fa-envelope"></i>
								</span>
								<input id="email" name="email" type="email" required value="{{ old('email', $admin->email) }}" class="pl-10 block w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-gray-800" />
								@error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
							</div>
						</div>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
						<div>
							<label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
							<div class="relative">
								<span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
									<i class="fas fa-user-shield"></i>
								</span>
								<select id="role" name="role" required class="pl-10 block w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-gray-800">
									<option value="">Pilih Role</option>
									<option value="super_admin" {{ old('role', $admin->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
									<option value="admin" {{ old('role', $admin->role) === 'admin' ? 'selected' : '' }}>Admin</option>
								</select>
								@error('role')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
							</div>
						</div>

						<div class="flex items-end">
							<label class="inline-flex items-center select-none">
								<input type="checkbox" id="is_active" name="is_active" class="rounded text-red-600 focus:ring-red-500" {{ old('is_active', $admin->is_active) ? 'checked' : '' }}>
								<span class="ml-2 text-sm text-gray-700"><i class="fas fa-toggle-on mr-1"></i>Aktif</span>
							</label>
						</div>
					</div>

					<div class="flex items-center justify-between pt-2">
						<a href="{{ route('admin.admins.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors">
							<i class="fas fa-arrow-left mr-2"></i> Kembali
						</a>
						<button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow transition-colors">
							<i class="fas fa-save mr-2"></i> Simpan Perubahan
						</button>
					</div>
				</form>
			</div>
		</div>

		<!-- Side Card -->
		<div class="bg-white rounded-xl shadow-md border border-gray-100">
			<div class="p-6 space-y-6">
				<div>
					<h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center"><i class="fas fa-info-circle text-blue-500 mr-2"></i> Detail Akun</h3>
					<div class="space-y-2 text-sm text-gray-700">
						<p class="flex items-center"><i class="fas fa-hashtag w-4 mr-2 text-gray-400"></i> ID: <span class="ml-1 font-semibold">{{ $admin->id }}</span></p>
						<p class="flex items-center"><i class="fas fa-calendar-plus w-4 mr-2 text-gray-400"></i> Dibuat: <span class="ml-1 font-semibold">{{ $admin->created_at->format('d/m/Y H:i') }}</span></p>
						<p class="flex items-center"><i class="fas fa-clock w-4 mr-2 text-gray-400"></i> Update Terakhir: <span class="ml-1 font-semibold">{{ $admin->updated_at->diffForHumans() }}</span></p>
					</div>
				</div>

				<div class="grid grid-cols-1 gap-3">
					@if(session('new_password'))
					<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
						<p class="font-semibold mb-2"><i class="fas fa-key mr-2"></i>Password Baru:</p>
						<div class="flex items-center">
							<code id="newPassword" class="bg-white px-3 py-2 rounded font-mono text-lg font-bold text-gray-800">{{ session('new_password') }}</code>
							<button onclick="copyPassword()" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition-colors"><i class="fas fa-copy mr-1"></i> Copy</button>
						</div>
						<p class="text-xs mt-2">Simpan password ini dengan aman. Password tidak akan tampil lagi setelah refresh.</p>
					</div>
					@endif
					<form action="{{ route('admin.admins.toggle-active', $admin) }}" method="POST" onsubmit="return confirm('Ubah status aktif akun ini?');">
						@csrf
						@method('PATCH')
						<button class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg {{ $admin->is_active ? 'bg-gray-500 hover:bg-gray-600' : 'bg-green-600 hover:bg-green-700' }} text-white transition-colors">
							<i class="fas {{ $admin->is_active ? 'fa-user-slash' : 'fa-user-check' }} mr-2"></i> {{ $admin->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
						</button>
					</form>

					<form action="{{ route('admin.admins.reset-password', $admin) }}" method="POST" onsubmit="return confirm('Reset password akun ini?');">
						@csrf
						<button class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white transition-colors">
							<i class="fas fa-key mr-2"></i> Reset Password
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
function copyPassword(){
    const el = document.getElementById('newPassword');
    if(!el) return;
    const text = el.textContent;
    navigator.clipboard.writeText(text).catch(()=>{});
}
</script>
@endsection
