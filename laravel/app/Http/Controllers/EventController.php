<?php
// app/Http/Controllers/EventController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventImage;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $viewType = $request->get('view', 'grid');

        // Gebruik alleen 'status' kolom, geen 'is_public'
        $events = Event::where('status', 'active')
            ->with(['images', 'user'])
            ->withCount('favorites')
            ->orderBy('start_date')
            ->paginate(12);

        return view('events.index', compact('events', 'viewType'));
    }

    public function calendar()
    {
        $events = Event::where('status', 'active')
            ->select('id', 'title', 'start_date', 'end_date', 'location')
            ->orderBy('start_date')
            ->get();

        return view('events.calendar', compact('events'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $category = $request->get('category');
        $location = $request->get('location');

        $events = Event::query()
            ->where('status', 'active')
            ->when($query, function ($q) use ($query) {
                return $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%");
            })
            ->when($category, function ($q) use ($category) {
                return $q->where('category', $category);
            })
            ->when($location, function ($q) use ($location) {
                return $q->where('location', 'like', "%{$location}%");
            })
            ->with(['images', 'user'])
            ->withCount('favorites')
            ->orderBy('start_date')
            ->paginate(12);

        return view('events.index', [
            'events' => $events,
            'viewType' => 'grid',
            'searchQuery' => $query,
            'searchCategory' => $category,
            'searchLocation' => $location
        ]);
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'category' => 'required|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
            'available_tickets' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_online' => 'boolean',
            'online_link' => 'nullable|url',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $event = Event::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'short_description' => $validated['short_description'] ?? null,
            'location' => $validated['location'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'category' => $validated['category'],
            'price' => $validated['price'] ?? 0,
            'capacity' => $validated['capacity'] ?? null,
            'available_tickets' => $validated['available_tickets'] ?? $validated['capacity'],
            'status' => 'active',
            'is_featured' => $validated['is_featured'] ?? false,
            'is_online' => $validated['is_online'] ?? false,
            'online_link' => $validated['online_link'] ?? null,
        ]);

        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('event-images', 'public');

                EventImage::create([
                    'event_id' => $event->id,
                    'image_path' => $path,
                    'is_primary' => false
                ]);
            }
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Evenement succesvol aangemaakt!');
    }

    public function show(Event $event)
    {
        // Controleer of het evenement actief is OF de gebruiker de eigenaar is
        if ($event->status !== 'active' && (!Auth::check() || Auth::id() !== $event->user_id)) {
            abort(404);
        }

        $event->load(['images', 'user', 'tickets', 'collaborators.user']);
        $isFavorite = Auth::check() ? $event->favorites()->where('user_id', Auth::id())->exists() : false;

        // BEREKEN of de huidige gebruiker het evenement mag bewerken
        $canEdit = false;
        if (Auth::check()) {
            // Controleer of gebruiker de eigenaar is OF een co-host/owner collaborator
            $canEdit = Auth::id() === $event->user_id ||
                $event->collaborators()
                    ->where('user_id', Auth::id())
                    ->whereIn('role', ['owner', 'co_host'])
                    ->exists();
        }

        return view('events.show', compact('event', 'isFavorite', 'canEdit'));
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'category' => 'required|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
            'available_tickets' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_online' => 'boolean',
            'online_link' => 'nullable|url',
            'status' => 'required|in:active,inactive,cancelled,completed',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'short_description' => $validated['short_description'] ?? null,
            'location' => $validated['location'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'category' => $validated['category'],
            'price' => $validated['price'] ?? 0,
            'capacity' => $validated['capacity'] ?? null,
            'available_tickets' => $validated['available_tickets'] ?? $event->capacity,
            'is_featured' => $validated['is_featured'] ?? false,
            'is_online' => $validated['is_online'] ?? false,
            'online_link' => $validated['online_link'] ?? null,
            'status' => $validated['status'],
        ]);

        // Upload nieuwe images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('event-images', 'public');

                EventImage::create([
                    'event_id' => $event->id,
                    'image_path' => $path,
                    'is_primary' => false
                ]);
            }
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Evenement succesvol bijgewerkt!');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        // Soft delete
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Evenement succesvol verwijderd!');
    }

    public function toggleFavorite(Event $event)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $favorite = $event->favorites()->where('user_id', $user->id)->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Evenement verwijderd uit favorieten';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'event_id' => $event->id
            ]);
            $message = 'Evenement toegevoegd aan favorieten';
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_favorite' => !$favorite
            ]);
        }

        return back()->with('success', $message);
    }

    public function deleteImage(EventImage $image)
    {
        $this->authorize('delete', $image->event);

        // Verwijder het bestand van opslag
        Storage::disk('public')->delete($image->image_path);

        // Verwijder de database record
        $image->delete();

        return back()->with('success', 'Afbeelding succesvol verwijderd!');
    }

    public function setPrimaryImage(EventImage $image)
    {
        $this->authorize('update', $image->event);

        // Zet alle images van dit event op niet-primary
        EventImage::where('event_id', $image->event_id)
            ->update(['is_primary' => false]);

        // Zet deze image op primary
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Hoofdafbeelding ingesteld!');
    }
}
