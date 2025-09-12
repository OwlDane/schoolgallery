@extends('admin.layouts.app')

@section('title', 'Kelola Acara')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Acara Mendatang</h2>
        <a href="{{ route('admin.events.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"><i class="fas fa-plus mr-2"></i>Tambah Acara</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-50 text-green-700">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($events as $event)
                <tr>
                    <td class="px-6 py-4 text-gray-800 font-medium">{{ $event->title }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $event->start_at->format('d M Y H:i') }} @if($event->end_at) - {{ $event->end_at->format('d M Y H:i') }} @endif</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $event->location ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.events.toggle-publish', $event) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="px-2 py-1 rounded text-xs {{ $event->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $event->is_published ? 'Dipublikasikan' : 'Draft' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.events.edit', $event) }}" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Hapus acara ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada acara.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $events->links() }}</div>
</div>
@endsection


