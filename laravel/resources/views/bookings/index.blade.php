{{-- resources/views/bookings/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mijn Bookings')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="bi bi-ticket-perforated"></i>
                                Mijn Bookings
                            </h4>
                            <a href="{{ route('events.index') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-calendar-plus"></i> Nieuw Evenement
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i> {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                            </div>
                        @endif

                        <!-- Statistieken -->
                        <div class="row mb-4">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <h3 class="text-primary mb-1">{{ $stats['total'] }}</h3>
                                        <small class="text-muted">Totaal Bookings</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h3 class="text-warning mb-1">{{ $stats['pending'] }}</h3>
                                        <small class="text-muted">In Afwachting</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h3 class="text-success mb-1">{{ $stats['confirmed'] }}</h3>
                                        <small class="text-muted">Bevestigd</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <h3 class="text-info mb-1">€{{ number_format($stats['total_spent'], 2) }}</h3>
                                        <small class="text-muted">Totaal Besteed</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bookings Tabel -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                <tr>
                                    <th>Boeking #</th>
                                    <th>Evenement</th>
                                    <th>Ticket</th>
                                    <th>Aantal</th>
                                    <th>Totaal</th>
                                    <th>Status</th>
                                    <th>Datum</th>
                                    <th>Acties</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($bookings as $booking)
                                    <tr>
                                        <td>
                                            <strong>{{ $booking->booking_number }}</strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('events.show', $booking->event) }}" class="text-decoration-none">
                                                {{ Str::limit($booking->event->title, 30) }}
                                            </a>
                                        </td>
                                        <td>{{ $booking->ticket->type }}</td>
                                        <td>{{ $booking->quantity }}x</td>
                                        <td class="fw-bold">{{ $booking->formatted_total_price }}</td>
                                        <td>
                                            {!! $booking->status_badge !!}
                                            <br>
                                            <small>{!! $booking->payment_status_badge !!}</small>
                                        </td>
                                        <td>{{ $booking->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($booking->status === 'pending')
                                                <form action="{{ route('bookings.confirm', $booking) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Betaling bevestigen?')">
                                                        <i class="bi bi-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Boeking annuleren?')">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="bi bi-ticket-perforated display-5 text-muted mb-3"></i>
                                            <h5 class="text-muted">Geen boekingen gevonden</h5>
                                            <p class="text-muted mb-0">Je hebt nog geen tickets geboekt.</p>
                                            <a href="{{ route('events.index') }}" class="btn btn-primary mt-3">
                                                <i class="bi bi-calendar-event"></i> Bekijk evenementen
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginatie -->
                        @if($bookings->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $bookings->links() }}
                            </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                Toont {{ $bookings->count() }} van {{ $bookings->total() }} boekingen
                            </small>
                            <div>
                                <button class="btn btn-outline-secondary btn-sm me-2" onclick="window.print()">
                                    <i class="bi bi-printer"></i> Print
                                </button>
                                <a href="{{ route('events.index') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-calendar-plus"></i> Nieuwe Booking
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bevestig annulering
            document.querySelectorAll('form[action*="cancel"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Weet je zeker dat je deze boeking wilt annuleren?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection
