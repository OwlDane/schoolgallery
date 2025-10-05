@extends('admin.layouts.app')

@section('title', 'Kelola Acara')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="bg-blue-600 text-white rounded-lg p-2 mr-3"><i class="fas fa-calendar-alt"></i></span>
                Kelola Acara
            </h1>
            <p class="text-gray-500 mt-1">Atur acara sekolah dengan tampilan modern yang konsisten</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg border border-blue-600 text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <a href="{{ route('admin.events.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-plus mr-2"></i>Tambah Acara
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 flex items-center bg-green-50 text-green-700 border border-green-200 rounded-lg p-3">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-auto text-green-600" onclick="this.parentElement.style.display='none'"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <!-- Toolbar -->
    <div class="bg-white border border-gray-100 rounded-xl p-4 mb-4 flex flex-col md:flex-row gap-3 items-center justify-between">
        <div class="relative w-full md:w-72">
            <input id="searchEvent" type="text" placeholder="Cari acara..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <select id="filterStatus" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Status</option>
                <option value="published">Dipublikasikan</option>
                <option value="draft">Draft</option>
            </select>
            <select id="filterMonth" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Bulan</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ sprintf('%02d',$m) }}">{{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acara</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="eventRows">
                @forelse($events as $event)
                <tr class="event-row" data-status="{{ $event->is_published ? 'published' : 'draft' }}" data-month="{{ $event->start_at->format('m') }}">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-800">{{ $event->title }}</div>
                        <div class="text-xs text-gray-500">{{ Str::limit($event->description, 80) }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $event->start_at->format('d M Y, H:i') }}
                        @if($event->end_at)
                            <span class="text-gray-400">—</span> {{ $event->end_at->format('d M Y, H:i') }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $event->location ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.events.toggle-publish', $event) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="px-3 py-1.5 rounded-full text-xs border transition {{ $event->is_published ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                <i class="fas {{ $event->is_published ? 'fa-bullhorn' : 'fa-file' }} mr-1"></i>
                                {{ $event->is_published ? 'Dipublikasikan' : 'Draft' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <a href="{{ route('admin.events.edit', $event) }}" class="inline-flex items-center px-3 py-1.5 bg-white text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition mr-2">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Hapus acara ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button class="inline-flex items-center px-3 py-1.5 bg-white text-red-600 border border-red-500 rounded-lg hover:bg-red-50 transition">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-50 text-blue-600 mb-3"><i class="fas fa-calendar"></i></div>
                        Belum ada acara. Klik <span class="font-medium">Tambah Acara</span> untuk membuat yang pertama.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $events->links() }}</div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const q = document.getElementById('searchEvent');
    const status = document.getElementById('filterStatus');
    const month = document.getElementById('filterMonth');
    const rows = document.querySelectorAll('#eventRows .event-row');

    function applyFilters(){
        const term = (q.value || '').toLowerCase();
        const st = status.value;
        const mo = month.value;
        rows.forEach(tr => {
            const text = tr.textContent.toLowerCase();
            const trStatus = tr.getAttribute('data-status');
            const trMonth = tr.getAttribute('data-month');
            const okText = term === '' || text.includes(term);
            const okStatus = st === '' || st === trStatus;
            const okMonth = mo === '' || mo === trMonth;
            tr.style.display = (okText && okStatus && okMonth) ? '' : 'none';
        });
    }
    q.addEventListener('input', applyFilters);
    status.addEventListener('change', applyFilters);
    month.addEventListener('change', applyFilters);
});
</script>
@endpush
@endsection


