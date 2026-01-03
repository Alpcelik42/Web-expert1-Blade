<?php
// app/Http/Controllers/BookingController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['event', 'ticket'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => Booking::where('user_id', Auth::id())->count(),
            'pending' => Booking::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'confirmed' => Booking::where('user_id', Auth::id())->where('status', 'confirmed')->count(),
            'total_spent' => Booking::where('user_id', Auth::id())->sum('total_price')
        ];

        return view('bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking)
    {
        // Controleer of de gebruiker toegang heeft tot deze booking
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Je hebt geen toegang tot deze boeking');
        }

        $booking->load(['event', 'ticket', 'event.images', 'event.user']);

        return view('bookings.show', compact('booking'));
    }

    public function bookTicket(Request $request, Event $event, Ticket $ticket)
    {
        $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:' . min($ticket->max_per_user, $ticket->available_quantity)
            ]
        ]);

        // Controleer of ticket beschikbaar is
        if (!$ticket->is_available) {
            return back()->with('error', 'Dit ticket is niet meer beschikbaar.');
        }

        if ($ticket->available_quantity < $request->quantity) {
            return back()->with('error', 'Niet genoeg tickets beschikbaar.');
        }

        try {
            DB::beginTransaction();

            // Maak de booking aan
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'ticket_id' => $ticket->id,
                'quantity' => $request->quantity,
                'unit_price' => $ticket->price,
                'total_price' => $ticket->price * $request->quantity,
                'booking_number' => Booking::generateBookingNumber(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $request->notes ?? null
            ]);

            // Update ticket verkoop
            $ticket->sellTickets($request->quantity);

            DB::commit();

            // Redirect naar booking details
            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Boeking succesvol aangemaakt! Controleer de details hieronder.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Er is een fout opgetreden bij het boeken: ' . $e->getMessage());
        }
    }

    public function confirm(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Je hebt geen toegang tot deze boeking');
        }

        // Simuleer betaling (in een echte app zou hier betalingsintegratie komen)
        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'notes' => 'Betaling bevestigd op ' . now()->format('d-m-Y H:i')
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Betaling succesvol bevestigd! Je tickets zijn nu geldig.');
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'Deze boeking kan niet meer geannuleerd worden.');
        }

        try {
            DB::beginTransaction();

            // Geef tickets terug
            $booking->ticket->returnTickets($booking->quantity);

            // Update booking status
            $booking->update([
                'status' => 'cancelled',
                'notes' => ($booking->notes ?? '') . "\nGeannuleerd op: " . now()->format('d-m-Y H:i')
            ]);

            DB::commit();

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Boeking succesvol geannuleerd. Tickets zijn weer beschikbaar.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Er is een fout opgetreden bij het annuleren.');
        }
    }

    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Alleen geannuleerde bookings kunnen verwijderd worden
        if ($booking->status !== 'cancelled') {
            return back()->with('error', 'Alleen geannuleerde boekingen kunnen verwijderd worden.');
        }

        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Boeking permanent verwijderd.');
    }
}
