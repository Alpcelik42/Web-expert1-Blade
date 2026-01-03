<?php
// app/Http/Controllers/TicketController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create($eventId)
    {
        $event = Event::findOrFail($eventId);

        if (!$event->canUserEdit(Auth::id())) {
            abort(403);
        }

        return view('tickets.create', compact('event'));
    }

    public function store(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        if (!$event->canUserEdit(Auth::id())) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity_available' => 'required|integer|min:1',
            'max_per_user' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ]);

        $event->tickets()->create($validated);

        return redirect()->route('events.show', ['event' => $event->id])
            ->with('success', 'Ticket type succesvol toegevoegd!');
    }

    public function edit($eventId, $ticketId)
    {
        $event = Event::findOrFail($eventId);
        $ticket = Ticket::findOrFail($ticketId);

        if (!$event->canUserEdit(Auth::id()) || $ticket->event_id !== $event->id) {
            abort(403);
        }

        return view('tickets.edit', compact('event', 'ticket'));
    }

    public function update(Request $request, $eventId, $ticketId)
    {
        $event = Event::findOrFail($eventId);
        $ticket = Ticket::findOrFail($ticketId);

        if (!$event->canUserEdit(Auth::id()) || $ticket->event_id !== $event->id) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity_available' => 'required|integer|min:1',
            'max_per_user' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ]);

        $ticket->update($validated);

        return redirect()->route('events.show', ['event' => $event->id])
            ->with('success', 'Ticket type succesvol bijgewerkt!');
    }

    public function destroy($eventId, $ticketId)
    {
        $event = Event::findOrFail($eventId);
        $ticket = Ticket::findOrFail($ticketId);

        if (!$event->canUserEdit(Auth::id()) || $ticket->event_id !== $event->id) {
            abort(403);
        }

        $ticket->delete();

        return redirect()->route('events.show', ['event' => $event->id])
            ->with('success', 'Ticket type succesvol verwijderd!');
    }

    public function book(Request $request, $eventId, $ticketId)
    {
        $event = Event::findOrFail($eventId);
        $ticket = Ticket::findOrFail($ticketId);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $ticket->max_per_user
        ]);

        // Check if ticket sale has started
        if ($event->ticket_sale_start && $event->ticket_sale_start->isFuture()) {
            return back()->with('error', 'Ticketverkoop start pas op ' . $event->ticket_sale_start->format('d-m-Y H:i'));
        }

        // Check availability
        if (!$ticket->canBook($validated['quantity'])) {
            return back()->with('error', 'Niet genoeg tickets beschikbaar');
        }

        // Create booking
        $booking = TicketBooking::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticket->id,
            'event_id' => $event->id,
            'quantity' => $validated['quantity'],
            'total_price' => $ticket->price * $validated['quantity'],
            'booking_reference' => 'TKT-' . Str::random(10),
            'status' => 'pending'
        ]);

        // Update ticket sales
        $ticket->increment('quantity_sold', $validated['quantity']);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Ticket(s) succesvol gereserveerd!');
    }

    public function confirmBooking($bookingId)
    {
        $booking = TicketBooking::findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->update(['status' => 'confirmed']);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Betaling gesimuleerd en ticket(s) bevestigd!');
    }
}
