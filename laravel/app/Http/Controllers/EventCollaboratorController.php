<?php
// app/Http/Controllers/EventCollaboratorController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCollaborator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventCollaboratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Event $event)
    {
        if (!$event->canUserEdit(Auth::id())) {
            abort(403);
        }

        $collaborators = $event->collaborators()->with('user')->get();
        return view('events.collaborators', compact('event', 'collaborators'));
    }

    public function store(Request $request, Event $event)
    {
        if (!$event->canUserEdit(Auth::id())) {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if user is already a collaborator
        if ($event->collaborators()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Gebruiker is al een medewerker van dit evenement');
        }

        EventCollaborator::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'role' => 'co_host'
        ]);

        return back()->with('success', 'Co-host succesvol toegevoegd!');
    }

    public function destroy(Event $event, EventCollaborator $collaborator)
    {
        if (!$event->canUserEdit(Auth::id()) || $collaborator->event_id !== $event->id) {
            abort(403);
        }

        // Don't allow removing the owner
        if ($collaborator->isOwner()) {
            return back()->with('error', 'De eigenaar kan niet verwijderd worden');
        }

        $collaborator->delete();

        return back()->with('success', 'Co-host succesvol verwijderd!');
    }
}
