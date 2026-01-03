{{-- resources/views/bookings/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mijn Bookings')

@section('content')
    <div class="container py-4 mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="bi bi-ticket-perforated"></i>
                                Mijn Bookings
                            </h4>
                            <a href="{{ route('events.create') }}" class="btn btn-light btn-sm">
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
                                    <th class="no-print">Acties</th>
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
                                        <td class="no-print">
                                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($booking->status === 'pending')
                                                <a href="{{ route('bookings.payment-method', $booking) }}" class="btn btn-sm btn-success" title="Ga naar betalingspagina">
                                                    <i class="bi bi-check"></i>
                                                </a>
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

                    <div class="card-footer no-print">
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

    <style>

        @media print {
            /* Reset en algemene instellingen */
            @page {
                margin: 15mm;
                size: A4 portrait;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                font-family: "Inter", "Segoe UI", "Arial", sans-serif;
                font-size: 11pt;
                color: #1a1a1a !important;
                line-height: 1.5;
                background: #ffffff !important;
                margin: 0;
                padding: 0;
            }

            /* Verberg website menu en footer */
            nav,
            header,
            .navbar,
            .navbar-header,
            .navbar-nav,
            .navbar-brand,
            .navbar-toggler,
            .nav-item,
            .nav-link,
            footer,
            .footer,
            .site-footer,
            #footer,
            [class*="footer-"],
            [id*="footer"],
            [class*="navbar-"],
            [id*="navbar"],
            [class*="header-"],
            [id*="header"] {
                display: none !important;
            }

            /* Verberg ook eventuele andere menu/footer elementen */
            .main-header,
            .main-nav,
            .top-bar,
            .header-wrapper,
            .footer-wrapper,
            .bottom-bar,
            .copyright,
            .social-links,
            .site-info {
                display: none !important;
            }

            /* Header styling */
            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 30px;
                padding-bottom: 15px;
                border-bottom: 2px solid #4361ee;
            }

            .print-header h1 {
                font-size: 24pt;
                color: #4361ee !important;
                margin: 0 0 5px 0;
                font-weight: 700;
            }

            .print-header .date {
                font-size: 10pt;
                color: #666666;
                background: #f5f7ff;
                padding: 5px 15px;
                border-radius: 12px;
                display: inline-block;
            }

            /* Main container */
            .container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* Main card - alles blijft zichtbaar */
            .card {
                border: 1px solid #e0e0e0 !important;
                border-radius: 8px !important;
                margin: 0 0 20px 0 !important;
                background: #ffffff !important;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05) !important;
                page-break-inside: avoid;
            }

            .card-header {
                background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%) !important;
                color: white !important;
                padding: 15px 20px !important;
                border-radius: 8px 8px 0 0 !important;
            }

            .card-header h4 {
                font-size: 16pt !important;
                margin: 0 !important;
                font-weight: 600;
            }

            .card-header .btn {
                display: none !important; /* Verberg alleen de knop */
            }

            .card-body {
                padding: 20px !important;
                background: #ffffff !important;
            }

            /* Statistieken - mooie kaarten */
            .row.mb-4 {
                display: flex !important;
                flex-wrap: wrap !important;
                gap: 15px !important;
                margin: 20px 0 30px 0 !important;
                justify-content: space-between;
            }

            .row.mb-4 > div {
                flex: 1 !important;
                min-width: 22% !important;
                max-width: 24% !important;
            }

            .row.mb-4 .card {
                border: 2px solid;
                border-radius: 10px !important;
                padding: 15px 10px !important;
                text-align: center;
                background: #ffffff !important;
                margin: 0 !important;
                box-shadow: 0 3px 10px rgba(0,0,0,0.08) !important;
                min-height: 100px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .row.mb-4 h3 {
                font-size: 24pt !important;
                margin: 0 0 8px 0 !important;
                color: #4361ee !important;
                font-weight: 700;
                line-height: 1;
            }

            .row.mb-4 small {
                font-size: 10pt !important;
                color: #666666 !important;
                font-weight: 500;
                letter-spacing: 0.3px;
                text-transform: uppercase;
            }

            /* Border kleuren */
            .border-primary { border-color: #4361ee !important; }
            .border-warning { border-color: #ff9800 !important; }
            .border-success { border-color: #4caf50 !important; }
            .border-info { border-color: #2196f3 !important; }

            /* Alerts blijven zichtbaar */
            .alert {
                display: block !important;
                border: 1px solid;
                border-radius: 6px !important;
                padding: 12px 15px !important;
                margin: 0 0 15px 0 !important;
                font-size: 10pt;
            }

            .alert-success {
                border-color: #4caf50 !important;
                background: rgba(76, 175, 80, 0.1) !important;
                color: #2e7d32 !important;
            }

            .alert-danger {
                border-color: #f44336 !important;
                background: rgba(244, 67, 54, 0.1) !important;
                color: #c62828 !important;
            }

            /* Tabel - volledig zichtbaar */
            .table-responsive {
                display: block !important;
                width: 100% !important;
                overflow: visible !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                margin: 20px 0 !important;
                font-size: 10pt !important;
                page-break-inside: avoid;
            }

            table thead {
                background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%) !important;
                color: white !important;
            }

            table th {
                padding: 12px 10px !important;
                border: 1px solid #3a56d4 !important;
                font-weight: 600;
                text-align: left;
                font-size: 10pt;
            }

            table td {
                padding: 10px 8px !important;
                border: 1px solid #e0e0e0 !important;
                vertical-align: middle;
                line-height: 1.4;
            }

            table tr:nth-child(even) td {
                background-color: #f8fafc !important;
            }

            /* Badges - mooi weergegeven */
            .badge {
                display: inline-block !important;
                padding: 4px 10px !important;
                border-radius: 12px !important;
                font-size: 9pt !important;
                font-weight: 600 !important;
                margin: 2px 0 !important;
            }

            .badge.bg-success {
                background: #4caf50 !important;
                color: white !important;
            }

            .badge.bg-warning {
                background: #ff9800 !important;
                color: #1a1a1a !important;
            }

            .badge.bg-danger {
                background: #f44336 !important;
                color: white !important;
            }

            .badge.bg-info {
                background: #2196f3 !important;
                color: white !important;
            }

            .badge.bg-primary {
                background: #4361ee !important;
                color: white !important;
            }

            /* Action knoppen - tonen als tekst */
            .actions {
                display: block !important;
            }

            .btn {
                display: none !important; /* Verberg knoppen */
            }

            /* Toon acties als tekst */
            .no-print {
                display: none !important; /* Verberg actiekolom */
            }

            /* Voeg extra info toe voor print */
            .booking-actions-print {
                display: block !important;
                font-size: 9pt;
                color: #666;
                margin-top: 5px;
            }

            /* Footer */
            .card-footer {
                display: block !important;
                padding: 15px 20px !important;
                border-top: 1px solid #e0e0e0 !important;
                background: #f8fafc !important;
                border-radius: 0 0 8px 8px !important;
                margin-top: 20px;
            }

            .card-footer small {
                color: #666666 !important;
                font-size: 9pt;
            }

            /* Print footer met paginanummers */
            .print-footer {
                display: block !important;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
                font-size: 9pt;
                color: #666666;
                padding: 10px 0;
                border-top: 1px solid #e0e0e0;
                background: #ffffff !important;
                z-index: 1000;
            }

            .print-footer .page-number:after {
                content: "Pagina " counter(page) " van " counter(pages);
                font-weight: 500;
                color: #4361ee;
                margin-right: 15px;
            }

            /* Paginatie */
            .pagination {
                display: none !important;
            }

            .d-flex.justify-content-center {
                display: none !important;
            }

            /* Pagina breaks */
            .page-break {
                page-break-before: always;
            }

            .no-break {
                page-break-inside: avoid;
            }

            /* Watermark */
            body::after {
                content: "";
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 60pt;
                opacity: 0.03;
                color: #4361ee;
                pointer-events: none;
                font-weight: 700;
                z-index: -1;
            }

            /* Links */
            a {
                color: #4361ee !important;
                text-decoration: none;
                font-weight: 500;
            }

            /* Zorg dat alle inhoud zichtbaar is */
            .col-12, .row {
                display: block !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Lege staat */
            td.text-center {
                padding: 30px 20px !important;
                text-align: center !important;
                border: 1px solid #e0e0e0 !important;
                background: #f8fafc !important;
            }

            .display-5 {
                font-size: 36pt !important;
                color: #cccccc !important;
            }

            /* Voeg extra print-info toe */
            .print-info {
                display: block !important;
                background: #f5f7ff;
                padding: 10px 15px;
                border-radius: 6px;
                margin: 15px 0;
                font-size: 9pt;
                color: #666;
            }

            /* Responsive voor print */
            @media print and (max-width: 210mm) {
                .row.mb-4 > div {
                    min-width: 48% !important;
                    max-width: 48% !important;
                    margin-bottom: 10px;
                }

                .row.mb-4 {
                    gap: 10px !important;
                }
            }
        }

        /* Normale weergave - verberg print-only */
        .print-only {
            display: none;
        }

        @media print {
            .print-only {
                display: block !important;
            }
        }
        /* Responsive aanpassingen voor de footer */
        @media (max-width: 767.98px) {
            .card-footer .d-flex {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
                text-align: center;
            }

            .card-footer .btn {
                width: 100%;
                max-width: 200px;
                margin: 0 auto;
            }

            /* Optioneel: maak de knop wat prominenter */
            .card-footer .btn-primary {
                font-size: 1rem;
                padding: 0.75rem 1.5rem;
            }
        }

        /* Voor extra kleine schermen */
        @media (max-width: 576px) {
            .card-footer .btn-primary {
                max-width: 100%;
            }
        }


    </style>
    <!-- Print-only content -->
    <div class="print-only">
        <div class="print-header">
            <h1>Mijn Bookings - Overzicht</h1>
            <div class="date">Geprint op: {{ now()->format('d-m-Y H:i') }}</div>
        </div>
    </div>

    <div class="print-footer print-only">
        <div class="page-number"></div>
        <div class="copyright">© {{ date('Y') }} EventHub - Alle rechten voorbehouden</div>
    </div>
@endsection
