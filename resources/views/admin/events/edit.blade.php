@extends('admin.layouts.app')

@section('title', 'Edit Acara')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <a href="{{ route('admin.events.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4 transition-colors duration-300"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center"><i class="far fa-calendar-check text-blue-600 mr-3"></i>Edit Acara</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700 border-l-4 border-red-500 shadow-sm">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                <span class="font-semibold">Terdapat kesalahan:</span>
            </div>
            <ul class="list-disc ml-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg p-6 space-y-5 border border-gray-100">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Acara</label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="6" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300">{{ old('description', $event->description) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                        </div>
                        <input type="text" name="location" value="{{ old('location', $event->location) }}" class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Acara</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors duration-300">
                        <input type="file" name="image" accept="image/*" class="hidden" id="image-upload">
                        <label for="image-upload" class="cursor-pointer flex flex-col items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                            <span class="text-sm text-gray-500">Klik untuk upload gambar atau seret file ke sini</span>
                            <span class="mt-2 text-xs text-blue-600">JPG, PNG, atau GIF (Maks. 2MB)</span>
                        </label>
                    </div>
                    @if($event->image)
                        <div class="mt-3 relative">
                            <p class="text-xs text-gray-500 mb-1">Gambar saat ini:</p>
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="rounded-lg w-full max-w-sm border border-gray-200">
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                                    <button type="button" class="bg-red-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-red-700 transition-colors duration-300">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus Gambar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Waktu Mulai</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="far fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input type="datetime-local" name="start_at" value="{{ old('start_at', $event->start_at->format('Y-m-d\\TH:i')) }}" class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Waktu Selesai (opsional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="far fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input type="datetime-local" name="end_at" value="{{ old('end_at', optional($event->end_at)->format('Y-m-d\\TH:i')) }}" class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-300">
                        </div>
                    </div>
                </div>
                <div class="flex items-center bg-blue-50 p-4 rounded-lg">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" id="is_published" name="is_published" value="1" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ old('is_published', $event->is_published) ? 'checked' : '' }}>
                    <label for="is_published" class="ml-2 text-sm text-gray-700">Publikasikan acara ini</label>
                </div>
                <div class="flex justify-between items-center pt-4">
                    <a href="{{ route('admin.events.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-300"><i class="fas fa-times mr-2"></i>Batal</a>
                    <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 shadow-md hover:shadow-lg"><i class="fas fa-save mr-2"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center"><i class="fas fa-info-circle text-blue-600 mr-2"></i>Informasi Acara</h3>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <div class="bg-blue-100 p-2 rounded-full text-blue-600 mr-3">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">ID Acara</span>
                            <p class="font-medium">{{ $event->id }}</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="bg-green-100 p-2 rounded-full text-green-600 mr-3">
                            <i class="far fa-calendar-plus"></i>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Dibuat pada</span>
                            <p class="font-medium">{{ $event->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="bg-purple-100 p-2 rounded-full text-purple-600 mr-3">
                            <i class="far fa-edit"></i>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Terakhir diperbarui</span>
                            <p class="font-medium">{{ $event->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="bg-yellow-100 p-2 rounded-full text-yellow-600 mr-3">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Status</span>
                            <p class="font-medium">
                                @if($event->is_published)
                                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Dipublikasikan</span>
                                @else
                                    <span class="text-red-600"><i class="fas fa-times-circle mr-1"></i>Draft</span>
                                @endif
                            </p>
                        </div>
                    </li>
                </ul>
                <div class="mt-6 pt-4 border-t border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Tindakan Cepat</h4>
                    <div class="space-y-2">
                        <a href="{{ route('event-detail', $event->id) }}" target="_blank" class="flex items-center text-sm text-blue-600 hover:text-blue-800 transition-colors duration-300">
                            <i class="fas fa-external-link-alt mr-2"></i> Lihat di Halaman Publik
                        </a>
                        <button type="button" class="flex items-center text-sm text-red-600 hover:text-red-800 transition-colors duration-300">
                            <i class="fas fa-trash-alt mr-2"></i> Hapus Acara
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


