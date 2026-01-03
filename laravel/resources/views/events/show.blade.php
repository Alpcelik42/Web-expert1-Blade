{{-- resources/views/events/show.blade.php --}}
@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <style>
        /* =========================
           Algemene mobile tweaks
           ========================= */
        @media (max-width: 767.98px) {
            .row.py-5 {
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }

            h1 {
                font-size: 1.6rem;
                line-height: 1.2;
            }

            h5 {
                font-size: 1.1rem;
            }

            p, li {
                font-size: 0.95rem;
            }

            /* Buttons stacken */
            .btn {
                width: 100%;
            }

            .d-flex.flex-wrap.gap-2 > * {
                flex: 1 1 100%;
            }
        }

        /* =========================
           Carousel responsive
           ========================= */
        @media (max-width: 767.98px) {
            #eventCarousel img {
                height: 220px !important;
                object-fit: cover;
            }

            .carousel-indicators {
                margin-bottom: 0;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            #eventCarousel img {
                height: 300px !important;
            }
        }

        /* =========================
           Layout columns
           ========================= */
        @media (max-width: 991.98px) {
            .col-md-8,
            .col-md-4 {
                margin-top: 1.5rem !important;
            }
        }

        /* =========================
           Tickets card
           ========================= */
        @media (max-width: 767.98px) {
            .card-body .border.rounded {
                padding: 1rem;
            }

            .card-body .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 0.75rem;
            }

            .card-body strong.h4 {
                font-size: 1.3rem;
            }
        }

        /* =========================
           Modals (booking & invite)
           ========================= */
        @media (max-width: 575.98px) {
            .modal-dialog {
                margin: 0.75rem;
            }

            .modal-content {
                border-radius: 0.75rem;
            }

            .modal-footer {
                flex-direction: column;
                gap: 0.5rem;
            }

            .modal-footer .btn {
                width: 100%;
            }
        }

        /* =========================
           Share section
           ========================= */
        @media (max-width: 767.98px) {
            .input-group {
                flex-direction: column;
            }

            .input-group .form-control {
                border-radius: 0.375rem !important;
                margin-bottom: 0.5rem;
                width: 100% !important;

            }

            .input-group .btn {
                border-radius: 0.375rem !important;
            }
        }

        /* =========================
           Kleine UX verbeteringen
           ========================= */
        @media (hover: none) {
            button, a {
                touch-action: manipulation;
            }
        }
    </style>

    <div class="row py-5">
        <div class="col-md-8 mt-5">
            <!-- Image Carousel -->
            @if($event->images->count() > 0)
                <div id="eventCarousel" class="carousel slide mb-5" data-bs-ride="carousel" >
                    <div class="carousel-indicators">
                        @foreach($event->images as $key => $image)
                            <button type="button" data-bs-target="#eventCarousel"
                                    data-bs-slide-to="{{ $key }}"
                                    class="{{ $key === 0 ? 'active' : '' }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($event->images as $key => $image)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     class="d-block w-100 rounded"
                                     alt="{{ $event->title }}"
                                     style="height: 400px; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            @endif

            <!-- Event Title + Buttons -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h1 class="mb-0">{{ $event->title }}</h1>
                <div class="d-flex flex-wrap gap-2">
                    @auth
                        <form action="{{ route('events.favorite', $event) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning" style="border: 2px solid goldenrod">
                                <i class="bi bi-star{{ $isFavorite ? '-fill' : '' }}"></i>
                                {{ $isFavorite ? 'Verwijder uit favorieten' : 'Voeg toe aan favorieten' }}
                            </button>
                        </form>

                        @if(Auth::check() && Auth::id() === $event->user_id)
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil"></i> Bewerk
                            </a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"
                                        onclick="return confirm('Weet je zeker dat je dit evenement wilt verwijderen?')" style="border: 2px solid red">
                                    <i class="bi bi-trash"></i> Verwijder
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Event Description & Details -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5><i class="bi bi-info-circle"></i> Beschrijving</h5>
                    <p>{{ $event->description }}</p>
                </div>
                <div class="col-md-6">
                    <h5><i class="bi bi-calendar-event"></i> Details</h5>
                    <ul class="list-unstyled">
                        <li><strong>Start:</strong> {{ $event->start_date->format('d-m-Y H:i') }}</li>
                        <li><strong>Eind:</strong> {{ $event->end_date->format('d-m-Y H:i') }}</li>
                        <li><strong>Locatie:</strong> {{ $event->location }}</li>
                        <li><strong>Organisator:</strong> {{ $event->user->name }}</li>
                        @if($event->capacity)
                            <li><strong>Capaciteit:</strong> {{ $event->capacity }} personen</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4 mt-5">
            <!-- Tickets Section -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-ticket-perforated"></i> Tickets</h5>
                </div>
                <div class="card-body">
                    @if($event->tickets->count() > 0)
                        @if($event->ticket_sale_start && $event->ticket_sale_start->isFuture())
                            <div class="alert alert-info">
                                Ticketverkoop start op {{ $event->ticket_sale_start->format('d-m-Y H:i') }}
                            </div>
                        @elseif($event->is_sold_out)
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle"></i> Uitverkocht!
                            </div>
                        @else
                            @foreach($event->tickets as $ticket)
                                <div class="border rounded p-3 mb-3">
                                    <h6>{{ $ticket->type }}</h6>
                                    <p class="text-muted small">{{ $ticket->description }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="h4">€{{ number_format($ticket->price, 2) }}</strong>
                                            <div class="text-muted small">
                                                {{ $ticket->available_quantity }} beschikbaar
                                            </div>
                                        </div>
                                        @auth
                                            @if(!$event->is_sold_out && $ticket->available_quantity > 0)
                                                <button class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#ticketModal{{ $ticket->id }}">
                                                    Boek
                                                </button>
                                            @else
                                                <button class="btn btn-secondary" disabled>Uitverkocht</button>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                                Inloggen om te boeken
                                            </a>
                                        @endauth
                                    </div>
                                </div>

                                <!-- Booking Modal -->
                                @auth
                                    <div class="modal fade" id="ticketModal{{ $ticket->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $ticket->type }} boeken</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('tickets.book', [$event, $ticket]) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Aantal tickets (max: {{ $ticket->max_per_user }})</label>
                                                            <input type="number" name="quantity"
                                                                   class="form-control"
                                                                   min="1"
                                                                   max="{{ min($ticket->max_per_user, $ticket->available_quantity) }}"
                                                                   value="1" required>
                                                        </div>
                                                        <div class="alert alert-info">
                                                            Totaalprijs: <span id="totalPrice{{ $ticket->id }}">€{{ number_format($ticket->price, 2) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                                                        <button type="submit" class="btn btn-primary">Bevestig boeking</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const quantityInput = document.querySelector('#ticketModal{{ $ticket->id }} input[name="quantity"]');
                                            const totalPriceSpan = document.querySelector('#totalPrice{{ $ticket->id }}');
                                            const ticketPrice = {{ $ticket->price }};

                                            quantityInput.addEventListener('input', function() {
                                                const quantity = parseInt(this.value) || 0;
                                                const total = quantity * ticketPrice;
                                                totalPriceSpan.textContent = '€' + total.toFixed(2);
                                            });
                                        });
                                    </script>
                                @endauth
                            @endforeach
                        @endif
                    @else
                        <p class="text-muted">Nog geen tickets beschikbaar</p>
                        @if($canEdit)
                            <a href="{{ route('tickets.create', $event) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus"></i> Ticket toevoegen
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Share Event -->
            @auth
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-share"></i> Deel evenement</h5>
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control"
                                   value="{{ route('events.show', $event) }}"
                                   id="shareLink{{ $event->id }}" readonly>
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="copyToClipboard('shareLink{{ $event->id }}')">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                        @if($canEdit)
                            <a href="#" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                               data-bs-target="#inviteModal">
                                <i class="bi bi-person-plus"></i> Nodig co-host uit
                            </a>
                        @endif
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <!-- Invite Co-host Modal -->
    @if($canEdit)
        <div class="modal fade" id="inviteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Co-host uitnodigen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('events.collaborators.store', $event) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">E-mailadres van de gebruiker</label>
                                <input type="email" name="email" class="form-control" required>
                                <div class="form-text">
                                    De gebruiker moet al een account hebben op EventHub.
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                            <button type="submit" class="btn btn-primary">Uitnodigen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        function copyToClipboard(elementId) {
            const copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
            alert('Link gekopieerd naar klembord!');
        }
    </script>
@endsection
