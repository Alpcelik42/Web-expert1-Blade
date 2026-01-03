@extends('layouts.app')

@section('title', 'Boeking Details: ' . $booking->booking_number)

@section('content')
    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="bi bi-ticket-perforated"></i>
                                Boeking Details: {{ $booking->booking_number }}
                            </h4>
                            <a href="{{ route('bookings.index') }}" class="first-btn btn btn-outline-light btn-sm" style="color: #ffffff !important; border-color: #ffffff !important;">
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
            /* Reset en algemene instellingen */
            @page {
                margin: 15mm;
                size: A4 portrait;
                marks: crop cross;
            }

            @page :first {
                margin-top: 10mm;
            }

            body {
                font-family: "Poppins", "Arial", "Helvetica", sans-serif;
                font-size: 11pt;
                color: #212529 !important;  /* Terug naar donkere tekst voor goede leesbaarheid */
                line-height: 1.4;
                background: white !important;
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Elementen verbergen */
            .btn,
            .alert,
            .card-footer,
            nav,
            .no-print,
            button,
            .actions,
            .form-control,
            .dropdown,
            .navbar,
            .modal,
            .badge,
            .search-form,
            footer,
            .back-to-top,
            .theme-toggle,
            .spinner-container,
            .alert-dismissible .btn-close {
                display: none !important;
            }

            /* Layout optimalisatie */
            .container,
            .container-fluid {
                width: 100%;
                max-width: 100%;
                padding: 0;
                margin: 0;
            }

            .row {
                display: block !important;
                margin: 0;
            }

            .col {
                width: 100%;
                float: none;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
                margin-bottom: 20px;
                page-break-inside: avoid;
                background: transparent !important;
                border-radius: 0 !important;
                color: #212529 !important;  /* Donkere tekst voor normale inhoud */
            }

            .card-header {
                background: linear-gradient(135deg, #4361ee, #3f37c9) !important;
                color: white !important;  /* Witte tekst op donkere achtergrond */
                border: none !important;
                padding: 12px 15px !important;
                margin-bottom: 15px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                font-family: 'Montserrat', sans-serif;
                font-weight: 600;
                border-radius: 4px 4px 0 0 !important;
            }


            .card-body {
                border: 1px solid #dee2e6 !important;
                border-top: none !important;
                padding: 15px !important;
                margin: 0 !important;
                color: #212529 !important;  /* Donkere tekst */
                background: white !important;
            }

            /* Typografie */
            h1, h2, h3, h4, h5, h6 {
                color: #212529 !important;  /* Donkere kleur voor headers */
                font-weight: 600;
                margin-top: 20px;
                margin-bottom: 10px;
                page-break-after: avoid;
                font-family: 'Montserrat', sans-serif;
            }

            h1 {
                font-size: 24pt;
                color: #4361ee !important;  /* Blauw voor H1 */
                margin-top: 0;
                padding-bottom: 10px;
                border-bottom: 3px solid #4361ee;
                background: transparent !important;
            }

            h2 {
                font-size: 18pt;
                color: #3f37c9 !important;  /* Donkerblauw voor H2 */
                border-bottom: 2px solid #e9ecef;
                padding-bottom: 8px;
                background: transparent !important;
            }

            h3 {
                font-size: 16pt;
                color: #212529 !important;  /* Donker voor H3 */
                border-left: 4px solid #4cc9f0;
                padding-left: 12px;
                margin-left: -12px;
                background: transparent !important;
            }

            h4 {
                font-size: 14pt;
                color: #3f37c9 !important;  /* Donkerblauw voor H4 */
                border-bottom: 1px solid #dee2e6;
                padding-bottom: 6px;
                margin-top: 25px;
                background: transparent !important;
            }

            h5 {
                font-size: 12pt;
                color: #6c757d !important;  /* Grijs voor H5 voor subtiel contrast */
                border-bottom: 1px dashed #ced4da;
                padding-bottom: 4px;
                background: transparent !important;
            }

            .text-gradient {
                background: linear-gradient(135deg, #4361ee, #4cc9f0) !important;
                -webkit-background-clip: text !important;
                -webkit-text-fill-color: transparent !important;
                background-clip: text !important;
                color: #4361ee !important;  /* Fallback kleur */
            }

            /* Tabellen */
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 12px 0 20px 0;
                page-break-inside: avoid;
                font-size: 10pt;
            }

            table th {
                background: linear-gradient(135deg, #4361ee, #3f37c9) !important;
                color: white !important;  /* Witte tekst op donkere header */
                font-weight: 600;
                padding: 10px 8px;
                border: 1px solid #3f37c9;
                text-align: left;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                font-family: 'Montserrat', sans-serif;
            }

            table td {
                padding: 10px 8px;
                border: 1px solid #dee2e6;
                vertical-align: top;
                color: #212529 !important;  /* Donkere tekst in cellen */
                background: white !important;
            }

            table tr:nth-child(even) td {
                background-color: #f8f9fa !important;  /* Lichtgrijze achtergrond */
                color: #212529 !important;  /* Donkere tekst erop */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Print specifieke elementen */
            .print-header {
                text-align: center;
                margin-bottom: 30px;
                padding-bottom: 15px;
                border-bottom: 3px solid #4361ee;
                position: relative;
            }

            .print-header::before {
                content: '';
                position: absolute;
                bottom: -3px;
                left: 25%;
                width: 50%;
                height: 3px;
                background: linear-gradient(90deg, #4361ee, #4cc9f0);
            }

            .print-header h1 {
                font-size: 28pt;
                margin: 0 0 10px 0;
                border: none;
                color: #4361ee !important;
                background: transparent !important;
            }

            .print-header .logo {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-bottom: 15px;
                font-family: 'Montserrat', sans-serif;
                font-weight: 700;
                font-size: 16pt;
                color: #4361ee;
            }

            .print-header .logo i {
                color: #4cc9f0;
            }

            .print-header .meta-info {
                font-size: 9pt;
                color: #6c757d !important;  /* Grijze tekst voor metadata */
                margin-top: 10px;
                display: flex;
                justify-content: center;
                gap: 20px;
                flex-wrap: wrap;
            }

            .print-header .meta-info span {
                padding: 4px 12px;
                background: #f8f9fa !important;  /* Lichtgrijze achtergrond */
                color: #6c757d !important;  /* Donkere tekst */
                border-radius: 4px;
                border: 1px solid #dee2e6;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
                font-size: 9pt;
                color: #6c757d !important;  /* Grijze tekst */
                padding: 10px 0;
                border-top: 2px solid #4361ee;
                background: white !important;  /* Witte achtergrond */
                z-index: 1000;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print-footer::before {
                content: '';
                position: absolute;
                top: -2px;
                left: 25%;
                width: 50%;
                height: 2px;
                background: linear-gradient(90deg, #4361ee, #4cc9f0);
            }

            .print-footer .page-number:after {
                content: "Pagina " counter(page) " van " counter(pages);
                font-weight: 500;
                color: #4361ee !important;  /* Blauwe paginanummers */
                background: transparent !important;
            }

            .print-footer .copyright {
                color: #6c757d !important;
                margin-top: 4px;
                background: transparent !important;
            }

            /* Pagina breaks beheren */
            .page-break {
                page-break-before: always;
                padding-top: 30px;
            }

            .no-break {
                page-break-inside: avoid;
            }

            .break-after {
                page-break-after: always;
            }

            .avoid-break {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            /* Afbeeldingen */
            img {
                max-width: 100% !important;
                height: auto !important;
                page-break-inside: avoid;
                border-radius: 8px;
                border: 1px solid #dee2e6;
            }

            /* Event Cards specifiek voor print */
            .event-card {
                border: 1px solid #dee2e6 !important;
                border-radius: 8px !important;
                margin-bottom: 15px;
                padding: 15px;
                page-break-inside: avoid;
                position: relative;
                background: white !important;  /* Witte achtergrond */
                color: #212529 !important;  /* Donkere tekst */
            }

            .event-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 4px;
                background: linear-gradient(90deg, #4361ee, #4cc9f0);
                border-radius: 8px 8px 0 0;
            }

            .event-card-badge {
                background: #f72585 !important;
                color: white !important;  /* Witte tekst op roze achtergrond */
                padding: 3px 10px;
                border-radius: 12px;
                font-size: 9pt;
                font-weight: 600;
                display: inline-block;
                margin-bottom: 10px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Buttons en badges zichtbaar maken voor print */
            .badge {
                display: inline-block !important;
                padding: 4px 8px;
                border: 1px solid #dee2e6;
                background: #f8f9fa !important;
                color: #212529 !important;  /* Donkere tekst op lichte achtergrond */
                font-size: 9pt;
                margin: 2px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                border-radius: 4px;
            }

            .badge.bg-primary {
                background: #4361ee !important;
                color: white !important;  /* Witte tekst op blauwe achtergrond */
                border: none;
            }

            .badge.bg-success {
                background: #4bb543 !important;
                color: white !important;  /* Witte tekst op groene achtergrond */
                border: none;
            }

            .badge.bg-warning {
                background: #f8961e !important;
                color: #212529 !important;  /* Donkere tekst op oranje achtergrond */
                border: none;
            }

            .badge.bg-danger {
                background: #f72585 !important;
                color: white !important;  /* Witte tekst op roze achtergrond */
                border: none;
            }

            /* Links */
            a {
                color: #4361ee !important;
                text-decoration: none;
                font-weight: 500;
                border-bottom: 1px dotted #4361ee;
                background: transparent !important;
            }

            a[href^="http"]:after {
                content: " (" attr(href) ")";
                font-size: 8pt;
                color: #6c757d !important;
                border: none;
            }

            /* Lijsten */
            ul, ol {
                margin: 10px 0 10px 20px;
                padding: 0;
            }

            li {
                margin-bottom: 8px;
                page-break-inside: avoid;
                color: #212529 !important;  /* Donkere tekst */
                background: transparent !important;
            }

            ul li::marker {
                color: #4361ee;
            }

            /* Formulier elementen */
            input[type="text"],
            textarea,
            select {
                border: 1px solid #ced4da;
                padding: 6px 10px;
                background: #fff !important;  /* Witte achtergrond */
                color: #212529 !important;  /* Donkere tekst */
                border-radius: 4px;
                min-height: 38px;
                width: 100%;
                margin: 5px 0;
                display: block;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Extra marges voor print */
            .print-margin-top {
                margin-top: 25px;
            }

            .print-margin-bottom {
                margin-bottom: 25px;
            }

            /* Highlighted sections */
            .highlight-box {
                border: 2px solid #4361ee;
                background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(76, 201, 240, 0.05)) !important;
                padding: 20px;
                margin: 20px 0;
                border-radius: 8px;
                page-break-inside: avoid;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color: #212529 !important;  /* Donkere tekst op lichte achtergrond */
            }

            /* Status indicators */
            .status-indicator {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 10pt;
                font-weight: 500;
                margin: 2px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .status-active {
                background: rgba(75, 181, 67, 0.1) !important;
                color: #155724 !important;  /* Donkergroene tekst */
                border: 1px solid #4bb543;
            }

            .status-pending {
                background: rgba(248, 150, 30, 0.1) !important;
                color: #856404 !important;  /* Donkeroranje tekst */
                border: 1px solid #f8961e;
            }

            .status-cancelled {
                background: rgba(247, 37, 133, 0.1) !important;
                color: #721c24 !important;  /* Donkerrode tekst */
                border: 1px solid #f72585;
            }

            /* QR Codes en barcodes */
            .qr-code,
            .barcode {
                border: 1px solid #dee2e6;
                padding: 10px;
                text-align: center;
                margin: 10px auto;
                max-width: 200px;
                page-break-inside: avoid;
                background: white !important;
            }

            .qr-code img,
            .barcode img {
                max-width: 150px !important;
            }

            /* Watermark (optioneel) */
            .watermark {
                opacity: 0.05;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 80pt;
                color: #4361ee;
                pointer-events: none;
                font-family: 'Montserrat', sans-serif;
                font-weight: 700;
                z-index: -1;
            }

            /* Alerts voor print */
            .alert {
                display: block !important;
                border: 2px solid transparent;
                padding: 15px;
                margin: 15px 0;
                border-radius: 8px;
                page-break-inside: avoid;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .alert-success {
                border-color: #4bb543;
                background: rgba(75, 181, 67, 0.1) !important;
                color: #155724 !important;  /* Donkergroene tekst */
                border-left: 6px solid #4bb543;
            }

            .alert-danger {
                border-color: #f72585;
                background: rgba(247, 37, 133, 0.1) !important;
                color: #721c24 !important;  /* Donkerrode tekst */
                border-left: 6px solid #f72585;
            }

            .alert-warning {
                border-color: #f8961e;
                background: rgba(248, 150, 30, 0.1) !important;
                color: #856404 !important;  /* Donkeroranje tekst */
                border-left: 6px solid #f8961e;
            }

            .alert-info {
                border-color: #4361ee;
                background: rgba(67, 97, 238, 0.1) !important;
                color: #0c5460 !important;  /* Donkerblauwe tekst */
                border-left: 6px solid #4361ee;
            }

            /* Event details specifiek */
            .event-details-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 15px;
                margin: 20px 0;
            }

            .event-detail-item {
                border: 1px solid #dee2e6;
                padding: 15px;
                border-radius: 8px;
                background: #f8f9fa !important;  /* Lichtgrijze achtergrond */
                page-break-inside: avoid;
                color: #212529 !important;  /* Donkere tekst */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .event-detail-item strong {
                color: #4361ee !important;
                display: block;
                margin-bottom: 5px;
                font-family: 'Montserrat', sans-serif;
            }

            /* Print alleen voor bepaalde secties */
            .print-only {
                display: block !important;
                color: #212529 !important;  /* Zorg voor donkere tekst */
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            /* Zorg dat alle tekst goed contrast heeft */
            p, span, div:not(.card-header):not(.badge):not(.event-card-badge):not(.alert):not(.status-indicator) {
                color: #212529 !important;
                background: transparent !important;
            }

            /* Specifieke achtergrond-kleur combinaties */
            [class*="bg-"]:not(.bg-transparent):not(.bg-white) {
                color: white !important;  /* Witte tekst op gekleurde achtergronden */
            }

            .bg-light {
                color: #212529 !important;  /* Donkere tekst op lichte achtergrond */
            }

            .bg-white {
                color: #212529 !important;  /* Donkere tekst op witte achtergrond */
            }
        }
        .card-header,
        .card-header *,
        .card-header h1,
        .card-header h2,
        .card-header h3,
        .card-header h4,
        .card-header h5,
        .card-header h6,
        .card-header i,
        .card-header span {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }
        .first-btn:hover{
            background: #000000 !important;
            color: #ffffff !important;
        }
        /* Responsive styling voor smartphones en tablets */
        @media (max-width: 768px) {
            /* Algemene aanpassingen voor tablets */
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }

            .container.py-4.mt-5 {
                padding-top: 1rem !important;
                margin-top: 1rem !important;
            }

            .col-md-10 {
                padding-left: 0;
                padding-right: 0;
            }

            /* Card header aanpassingen */
            .card-header.bg-primary .d-flex {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start !important;
            }

            .card-header.bg-primary h4 {
                font-size: 1.25rem;
                margin-bottom: 0.5rem;
            }

            .first-btn {
                align-self: flex-end;
                margin-top: -40px;
            }

            /* Status banner responsive */
            .alert .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px;
            }

            .alert .d-flex .text-end {
                text-align: left !important;
                width: 100%;
            }

            /* Kolommen stapelen op mobiel */
            .row .col-md-6,
            .row .col-md-8,
            .row .col-md-4 {
                width: 100%;
                flex: 0 0 100%;
                max-width: 100%;
            }

            /* Card body padding verminderen */
            .card-body {
                padding: 1rem !important;
            }

            /* Tabel responsive maken */
            .table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                white-space: nowrap;
            }

            .table-sm td,
            .table-sm th {
                padding: 0.5rem;
                font-size: 0.9rem;
            }

            /* Evenement details responsive */
            .card.mb-4 .row {
                flex-direction: column;
            }

            .card.mb-4 .col-md-4.text-end {
                text-align: left !important;
                margin-top: 1rem;
            }

            .card.mb-4 .col-md-4.text-end img {
                max-width: 100%;
                height: auto;
            }

            /* Acties responsive */
            .d-flex.flex-wrap.gap-2 {
                flex-direction: column;
                gap: 10px !important;
            }

            .d-flex.flex-wrap.gap-2 .btn,
            .d-flex.flex-wrap.gap-2 form {
                width: 100%;
                margin-right: 0 !important;
                margin-bottom: 0.5rem;
            }

            .d-flex.flex-wrap.gap-2 .btn:last-child,
            .d-flex.flex-wrap.gap-2 form:last-child {
                margin-bottom: 0;
            }

            /* Footer responsive */
            .card-footer .d-flex {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .card-footer .d-flex small {
                display: block;
            }
        }

        @media (max-width: 576px) {
            /* Smartphone specifieke aanpassingen */
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }

            .card {
                border-radius: 0.5rem;
                margin-bottom: 1rem;
            }

            .card-header.bg-primary .d-flex {
                flex-direction: column;
                align-items: stretch !important;
            }

            .card-header.bg-primary h4 {
                font-size: 1.1rem;
                text-align: center;
                margin-bottom: 0.75rem;
            }

            .first-btn {
                align-self: stretch;
                margin-top: 0;
                text-align: center;
                width: 100%;
            }

            /* Typografie voor smartphones */
            h4, h5 {
                font-size: 1.1rem;
            }

            .h4, h4 {
                font-size: 1.25rem;
            }

            .h5, h5 {
                font-size: 1.1rem;
            }

            /* Ticket details op smartphones */
            .d-flex.align-items-center.mb-3 {
                flex-direction: column;
                text-align: center;
            }

            .d-flex.align-items-center.mb-3 .me-3 {
                margin-right: 0 !important;
                margin-bottom: 1rem;
            }

            /* Tabel optimalisatie voor smartphones */
            .table-sm {
                font-size: 0.85rem;
            }

            .table-sm td,
            .table-sm th {
                padding: 0.4rem 0.3rem;
            }

            .table tr {
                display: flex;
                flex-direction: column;
                margin-bottom: 0.5rem;
                border: 1px solid #dee2e6;
                border-radius: 0.25rem;
            }

            .table tr td {
                width: 100% !important;
                border: none;
                padding: 0.3rem 0.5rem;
            }

            .table tr td:first-child {
                background-color: #f8f9fa;
                font-weight: 600;
                border-bottom: 1px solid #dee2e6;
            }

            /* Badges op smartphones */
            .badge.fs-6 {
                font-size: 0.9rem !important;
                padding: 0.25em 0.6em;
            }

            /* Evenement afbeelding */
            .col-md-4.text-end img {
                max-height: 120px !important;
            }

            /* Actie buttons optimalisatie */
            .btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.9rem;
            }

            .btn-group-vertical {
                width: 100%;
            }

            /* Alert marges */
            .alert {
                padding: 0.75rem;
                margin-bottom: 1rem;
            }

            /* Card footer tekst */
            .card-footer small {
                font-size: 0.8rem;
            }

            /* D-flex aanpassingen voor smartphones */
            .d-flex {
                flex-wrap: wrap;
            }

            /* Evenement details grid */
            .row.mt-3 .col-md-6 {
                margin-bottom: 0.5rem;
            }

            /* Icon sizes voor smartphones */
            .bi {
                font-size: 1.1em;
            }

            .fs-2 {
                font-size: 1.75rem !important;
            }
        }

        @media (min-width: 577px) and (max-width: 768px) {
            /* Tablet specifieke aanpassingen */
            .container {
                max-width: 100%;
            }

            .col-md-10 {
                max-width: 100%;
            }

            /* Card header tablet layout */
            .card-header.bg-primary .d-flex {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            /* Evenement details tablet layout */
            .card.mb-4 .row {
                flex-direction: row;
            }

            /* Tabel responsive voor tablets */
            .table {
                min-width: 500px;
            }

            /* Acties voor tablets */
            .d-flex.flex-wrap.gap-2 {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .d-flex.flex-wrap.gap-2 .btn,
            .d-flex.flex-wrap.gap-2 form {
                width: auto;
                margin-bottom: 0.5rem;
            }

            /* Responsive font sizes voor tablets */
            h4 {
                font-size: 1.3rem;
            }

            h5 {
                font-size: 1.15rem;
            }

            /* Evenement afbeelding tablet */
            .col-md-4.text-end img {
                max-height: 140px !important;
            }
        }

        /* Landscape mode voor smartphones */
        @media (max-width: 768px) and (orientation: landscape) {
            .container.py-4.mt-5 {
                padding-top: 2rem !important;
                margin-top: 2rem !important;
            }

            .card-header.bg-primary h4 {
                font-size: 1.1rem;
            }

            .d-flex.flex-wrap.gap-2 {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .col-md-4.text-end img {
                max-height: 100px !important;
            }
        }

        /* Extra kleine smartphones */
        @media (max-width: 375px) {
            .card-header.bg-primary h4 {
                font-size: 1rem;
            }

            .first-btn .btn-sm {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
            }

            .h4, h4 {
                font-size: 1.1rem;
            }

            .h5, h5 {
                font-size: 1rem;
            }

            .badge.fs-6 {
                font-size: 0.8rem !important;
            }

            .btn {
                font-size: 0.85rem;
                padding: 0.3rem 0.6rem;
            }
        }

        /* Houd de bestaande print styling - deze blijft ongewijzigd */
        @media print {
            /* ... bestaande print styling blijft hier staan ... */
            .card-header,
            .card-header *,
            .card-header h1,
            .card-header h2,
            .card-header h3,
            .card-header h4,
            .card-header h5,
            .card-header h6,
            .card-header i,
            .card-header span {
                color: #ffffff !important;
                -webkit-text-fill-color: #ffffff !important;
            }
            .first-btn:hover{
                background: #000000 !important;
                color: #ffffff !important;
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
