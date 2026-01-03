<?php
// app/Http/Middleware/EventOwner.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class EventOwner
{
    public function handle(Request $request, Closure $next)
    {
        // Get the event from route parameter (assuming route model binding)
        $event = $request->route('event');

        // If it's an ID instead of model instance
        if (!($event instanceof Event)) {
            $eventId = $event;
            $event = Event::find($eventId);
        }

        // Check if event exists
        if (!$event) {
            abort(404, 'Evenement niet gevonden.');
        }

        // Check permission
        if (!$event->canUserEdit(Auth::id())) {
            abort(403, 'Je hebt geen toestemming om dit evenement te bewerken.');
        }

        return $next($request);
    }
}
