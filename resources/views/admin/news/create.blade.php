@extends('admin.layouts.app')

@section('title', 'Tambah Berita Baru')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Tambah Berita Baru</h2>
            <a href="{{ route('admin.news.index') }}" class="text-sm text-gray-600 hover:text-gray-800 flex items-center">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Berita
            </a>
        </div>

        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Judul Berita -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Berita</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('title') border-red-500 @enderror"
                            placeholder="Masukkan judul berita" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konten Berita -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Isi Berita</label>
                        <textarea name="content" id="content" rows="10" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('content') border-red-500 @enderror"
                            placeholder="Tulis isi berita disini..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Gambar Utama -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Upload file</span>
                                        <input id="image" name="image" type="file" class="sr-only" onchange="previewImage(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF (Maks. 2MB)</p>
                            </div>
                        </div>
                        <div class="mt-2" id="imagePreview"></div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="kategori_id" id="kategori_id" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('kategori_id') border-red-500 @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penulis -->
                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                        <input type="text" name="author" id="author" value="{{ old('author', auth('admin')->user()->name) }}" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('author') border-red-500 @enderror"
                            placeholder="Nama penulis" required>
                        @error('author')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Publikasi -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_published" id="is_published" value="1" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            {{ old('is_published') ? 'checked' : '' }}>
                        <label for="is_published" class="ml-2 block text-sm text-gray-700">
                            Publikasikan sekarang
                        </label>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i> Simpan Berita
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Preview gambar sebelum upload
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'mt-2 w-full h-48 object-cover rounded-lg';
                preview.appendChild(img);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Inisialisasi text editor
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'mediaEmbed', '|', 'undo', 'redo'],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h2', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h3', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h4', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            },
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endpush

@endsection
