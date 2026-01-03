{{-- resources/views/bookings/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Boeking Details: ' . $booking->booking_number)

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="bi bi-ticket-perforated"></i>
                                Boeking Details: {{ $booking->booking_number }}
                            </h4>
                            <a href="{{ route('bookings.index') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-arrow-left"></i> Terug naar overzicht
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

                        <!-- Status banner -->
                        <div class="alert alert-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'warning') }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-{{ $booking->status === 'confirmed' ? 'check-circle' : ($booking->status === 'cancelled' ? 'x-circle' : 'clock') }} me-2"></i>
                                    <strong>Status:</strong> {!! $booking->status_badge !!}
                                    <span class="mx-2">|</span>
                                    <strong>Betaling:</strong> {!! $booking->payment_status_badge !!}
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">
                                        Aangemaakt: {{ $booking->created_at->format('d-m-Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Linker kolom: Boeking details -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Boeking Informatie</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <td width="40%"><strong>Boekingnummer:</strong></td>
                                                <td><code>{{ $booking->booking_number }}</code></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Referentie:</strong></td>
                                                <td>#{{ $booking->id }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Aangemaakt op:</strong></td>
                                                <td>{{ $booking->created_at->format('d-m-Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Laatst bijgewerkt:</strong></td>
                                                <td>{{ $booking->updated_at->format('d-m-Y H:i') }}</td>
                                            </tr>
                                            @if($booking->notes)
                                                <tr>
                                                    <td><strong>Opmerkingen:</strong></td>
                                                    <td class="text-muted">{{ $booking->notes }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Rechter kolom: Ticket details -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="bi bi-ticket-perforated"></i> Ticket Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-primary text-white rounded p-3 me-3">
                                                <i class="bi bi-ticket-perforated fs-2"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-1">{{ $booking->ticket->type }}</h5>
                                                <p class="text-muted mb-0">{{ $booking->ticket->description }}</p>
                                            </div>
                                        </div>
                                        <table class="table table-sm">
                                            <tr>
                                                <td width="40%"><strong>Prijs per ticket:</strong></td>
                                                <td class="h5">{{ $booking->formatted_unit_price }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Aantal tickets:</strong></td>
                                                <td><span class="badge bg-primary fs-6">{{ $booking->quantity }}x</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Totaalbedrag:</strong></td>
                                                <td class="h4 text-success">{{ $booking->formatted_total_price }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Evenement details -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Evenement Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4>{{ $booking->event->title }}</h4>
                                                <p class="text-muted">{{ Str::limit($booking->event->description, 200) }}</p>

                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <p class="mb-1">
                                                            <i class="bi bi-calendar me-2"></i>
                                                            <strong>Datum:</strong> {{ $booking->event->start_date->format('d-m-Y') }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <i class="bi bi-clock me-2"></i>
                                                            <strong>Tijd:</strong> {{ $booking->event->start_time }} - {{ $booking->event->end_time }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1">
                                                            <i class="bi bi-geo-alt me-2"></i>
                                                            <strong>Locatie:</strong> {{ $booking->event->location }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <i class="bi bi-person me-2"></i>
                                                            <strong>Organisator:</strong> {{ $booking->event->user->name }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <a href="{{ route('events.show', $booking->event) }}" class="btn btn-outline-primary mb-2">
                                                    <i class="bi bi-eye"></i> Bekijk evenement
                                                </a>
                                                @if($booking->event->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $booking->event->images->first()->image_path) }}"
                                                         class="img-fluid rounded mt-3"
                                                         alt="{{ $booking->event->title }}"
                                                         style="max-height: 150px;">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Acties -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="bi bi-gear"></i> Acties</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-2">
                                            @if($booking->status === 'pending')
                                                <!-- Bevestig betaling -->
                                                <form action="{{ route('bookings.confirm', $booking) }}" method="POST" class="me-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success"
                                                            onclick="return confirm('Betaling bevestigen? Dit kan niet ongedaan worden gemaakt.')">
                                                        <i class="bi bi-check-circle"></i> Bevestig Betaling
                                                    </button>
                                                </form>

                                                <!-- Annuleer boeking -->
                                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="me-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Weet je zeker dat je deze boeking wilt annuleren?')">
                                                        <i class="bi bi-x-circle"></i> Annuleer Boeking
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Algemene acties -->
                                            <button class="btn btn-outline-primary" onclick="window.print()">
                                                <i class="bi bi-printer"></i> Print Details
                                            </button>

                                            <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                                                <i class="bi bi-list"></i> Terug naar overzicht
                                            </a>

                                            <a href="{{ route('events.index') }}" class="btn btn-primary">
                                                <i class="bi bi-calendar-plus"></i> Meer evenementen
                                            </a>

                                            @if($booking->status === 'cancelled')
                                                <!-- Verwijder geannuleerde boeking -->
                                                <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="ms-auto">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger"
                                                            onclick="return confirm('Boeking permanent verwijderen? Dit kan niet ongedaan worden gemaakt.')">
                                                        <i class="bi bi-trash"></i> Verwijderen
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <!-- Status informatie -->
                                        <div class="alert alert-info mt-3">
                                            <i class="bi bi-info-circle"></i>
                                            @if($booking->status === 'pending')
                                                <strong>Je boeking is in afwachting van betaling.</strong>
                                                Bevestig de betaling om je tickets te activeren, of annuleer als je niet wilt doorgaan.
                                            @elseif($booking->status === 'confirmed')
                                                <strong>Je boeking is bevestigd en betaald!</strong>
                                                Je tickets zijn nu geldig voor het evenement.
                                            @elseif($booking->status === 'cancelled')
                                                <strong>Deze boeking is geannuleerd.</strong>
                                                Je kunt deze nu permanent verwijderen uit je overzicht.
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-shield-check"></i> Veilige transactie via EventHub
                            </small>
                            <small class="text-muted">
                                Voor vragen: <a href="mailto:support@eventhub.com">support@eventhub.com</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print styling -->
    <style>
        @media print {
            .btn, .card-footer, .alert-info, .d-print-none {
                display: none !important;
            }

            .card {
                border: 1px solid #000 !important;
                box-shadow: none !important;
            }

            body {
                background-color: white !important;
            }

            .container {
                max-width: 100% !important;
                padding: 0 !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Boeking details geladen:', {
                id: {{ $booking->id }},
                number: '{{ $booking->booking_number }}',
                status: '{{ $booking->status }}'
            });

            // Print functionaliteit
            document.querySelector('button[onclick="window.print()"]').addEventListener('click', function() {
                // Voeg een print header toe
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                <html>
                <head>
                    <title>Boeking {{ $booking->booking_number }}</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .print-header {
                            text-align: center;
                            margin-bottom: 20px;
                            border-bottom: 2px solid #000;
                            padding-bottom: 10px;
                        }
                        .print-footer {
                            margin-top: 30px;
                            text-align: center;
                            font-size: 12px;
                            color: #666;
                        }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <h2>EventHub - Boeking Confirmation</h2>
                        <p>Boekingnummer: {{ $booking->booking_number }}</p>
                        <p>Datum: {{ now()->format('d-m-Y H:i') }}</p>
                    </div>
                    ${document.querySelector('.card-body').innerHTML}
                    <div class="print-footer">
                        <p>EventHub - Evenementen Platform</p>
                        <p>Dit is een officiële boekingbevestiging</p>
                    </div>
                </body>
                </html>
            `);
                printWindow.document.close();
                printWindow.print();
            });
        });
    </script>
@endsection
