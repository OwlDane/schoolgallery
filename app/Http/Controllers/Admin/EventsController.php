<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('start_at', 'desc')->paginate(12);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:200',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_published' => 'sometimes|boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Acara berhasil dibuat');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:200',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_published' => 'sometimes|boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Acara berhasil diperbarui');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Acara berhasil dihapus');
    }

    public function togglePublish(Event $event)
    {
        $event->is_published = !$event->is_published;
        $event->save();
        return back()->with('success', 'Status publikasi acara diperbarui');
    }
}


