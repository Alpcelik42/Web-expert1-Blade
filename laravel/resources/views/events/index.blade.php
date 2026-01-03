{{-- resources/views/events/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Evenementen')

@section('content')
    <style>
        /* =====================================
           ALGEMENE CONTAINER FIX
           ===================================== */
        @media (max-width: 991.98px) {
            .container.mx-5 {
                margin-left: auto !important;
                margin-right: auto !important;
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        /* =====================================
           HERO SECTIE
           ===================================== */

        /* Smartphone: ONGEWIJZIGD */

        /* Tablet: afbeelding sneller kleiner */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .row.align-items-center img {
                max-width: 280px !important;
            }
        }

        /* =====================================
           OVER ONS – ICONS CENTRAAL
           ===================================== */
        @media (max-width: 991.98px) {
            .d-flex.align-items-start {
                flex-direction: column;
                align-items: center !important;
                text-align: center;
            }

            .d-flex.align-items-start .me-4 {
                margin-right: 0 !important;
                margin-bottom: 1rem;
            }

            .d-flex.align-items-start .rounded-circle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* =====================================
           EVENEMENTEN HEADER + VIEW SWITCH
           ===================================== */
        @media (max-width: 767.98px) {
            #evenementen {
                text-align: center;
                margin-bottom: 1rem;
            }

            .col-md-6.text-end {
                text-align: center !important;
            }

            .btn-group {
                width: 100%;
            }

            .btn-group .btn {
                width: 50%;
            }
        }

        /* =====================================
           MEER RUIMTE TUSSEN EVENTS
           ===================================== */

        /* Grid view spacing */
        @media (max-width: 991.98px) {
            .row.row-cols-1.row-cols-md-3.g-4 {
                --bs-gutter-x: 1.5rem;
                --bs-gutter-y: 2.2rem;
            }
        }

        /* Mobiel iets meer lucht */
        @media (max-width: 767.98px) {
            .row.row-cols-1.row-cols-md-3.g-4 {
                --bs-gutter-y: 2.5rem;
            }

            .event-card {
                margin-bottom: 0.5rem;
            }
        }

        /* List view spacing */
        @media (max-width: 767.98px) {
            .list-group-item {
                margin-bottom: 1rem;
                border-radius: 0.75rem;
            }
        }

        /* =====================================
           GRID CARDS FINETUNE
           ===================================== */
        @media (max-width: 767.98px) {
            .img-fluid{
                margin-bottom: 5rem !important;
            }

            .event-card {
                border-radius: 1rem;
                overflow: hidden;
            }

            .event-card .card-img-top {
                height: 180px;
                object-fit: cover;
            }

            .event-card .card-body {
                padding: 1rem;
            }

            .event-card .card-title {
                font-size: 1.1rem;
            }

            .event-card .card-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }

        /* =====================================
           LIST VIEW (MOBIEL)
           ===================================== */
        @media (max-width: 767.98px) {
            .list-group-item .row {
                flex-direction: column;
                text-align: center;
            }

            .list-group-item .col-md-2,
            .list-group-item .col-md-8,
            .list-group-item .col-md-2.text-end {
                width: 100%;
                max-width: 100%;
            }

            .list-group-item img {
                max-height: 180px;
                margin-bottom: 1rem;
            }

            .list-group-item .text-end {
                text-align: center !important;
                margin-top: 1rem;
            }
        }

        /* =====================================
           PAGINATION
           ===================================== */
        @media (max-width: 575.98px) {
            .pagination {
                justify-content: center;
                flex-wrap: wrap;
            }

            .pagination .page-item {
                margin: 0.15rem;
            }
        }

        /* =====================================
           TOUCH UX
           ===================================== */
        @media (hover: none) {
            a, button {
                touch-action: manipulation;
            }
        }
    </style>


    {{-- BOVENSTE SECTIE MET AFBEELDING EN TEKST --}}
    <div class="container mt-5 mx-5">
        <div class="row align-items-center mb-5 pt-5  pb-5">
            <!-- Afbeelding div - rechts -->
            <div class="col-md-6 order-md-2 text-center mt-4 mt-md-0">
                <img src="{{ asset('michael-discenza-MxfcoxycH_Y-unsplash (1).jpg') }}"
                     alt="Eventhub"
                     class="img-fluid rounded shadow-lg"
                     style="max-width: 400px;">
            </div>

            <!-- Tekst div - links -->
            <div class="col-md-6 order-md-1">
                <h1 class="display-4 fw-bold mb-3">EventHub!</h1>
                <p class="lead mb-4">Een alles-in-één eventplanner waar je je vrienden
                    <br>of collega's kunt uitnodigen, enjoy life!</p>

                <!-- Knoppen -->
                <div class="d-flex flex-wrap gap-3">
                    <!-- Knop 1: Scroll naar evenementen -->
                    <a href="#evenementen" class="btn btn-primary btn d-flex align-items-center gap-3 px-5 py-3 fw-bold rounded-pill">
                        <i class="bi bi-calendar-event"></i>
                        <span>Bekijk Evenementen</span>
                    </a>

                    <!-- Knop 2: Naar bookings (alleen voor ingelogde gebruikers) -->
                    @auth
                        <a href="{{ url('/bookings') }}" class="btn btn-outline-primary border-primary border-2 btn d-flex align-items-center gap-3 px-5 py-3 fw-bold rounded-pill text-white">
                            <i class="bi bi-ticket"></i>
                            <span>Mijn Bookings</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary border-primary border-2 btn d-flex align-items-center gap-3 px-5 py-3 fw-bold rounded-pill text-white"
                           title="Log in om je bookings te bekijken">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Bookings</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- "OVER ONS" SECTIE --}}
        <div class="row mt-5 pt-4">
            <div class="col-12">
                <h2 class="text-center mb-5 fw-bold">Over EventHub
                </h2>

                {{-- Introductie tekst --}}
                <div class="row mb-5">
                    <div class="col-md-8 mx-auto">
                        <p class="text-center fs-5 mb-0">
                            EventHub is ontstaan uit een gedeelde passie voor het verbinden van mensen door middel van memorabele evenementen.
                            Wat begon als een idee tussen twee vrienden is uitgegroeid tot een platform waar duizenden mensen hun perfecte
                            evenement vinden.
                        </p>
                    </div>
                </div>

                {{-- Alperen links --}}
                <div class="row align-items-center mb-5">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="me-4">
                                <div class="rounded-circle bg-primary-subtle p-3">
                                    <i class="bi bi-code-slash fs-1 text-primary"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-2">Alperen</h3>
                                <p class="mb-0">
                                    Full-stack developer met een scherp oog voor UI/UX design.
                                    Alperen combineert technische expertise met een passie voor gebruikerservaring
                                    om intuïtieve interfaces te creëren die zowel mooi als functioneel zijn.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Eventuele afbeelding of extra content kan hier --}}
                    </div>
                </div>

                {{-- Sergio rechts --}}
                <div class="row align-items-center">
                    <div class="col-md-6 order-md-2">
                        <div class="d-flex align-items-start">
                            <div class="me-4">
                                <div class="rounded-circle bg-primary-subtle p-3">
                                    <i class="bi bi-palette fs-1 text-primary"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-2">Sergio</h3>
                                <p class="mb-0">
                                    Front-end specialist met een achtergrond in visueel design.
                                    Sergio focust op het bouwen van responsieve en toegankelijke interfaces
                                    die zowel op desktop als mobiel optimaal functioneren.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 order-md-1">
                        {{-- Eventuele afbeelding of extra content kan hier --}}
                    </div>
                </div>

                {{-- Quote onderaan --}}
                <div class="row mt-5 pt-4">
                    <div class="col-md-8 mx-auto">
                        <div class="text-center">
                            <p class="fs-5 fst-italic text-primary mb-3">
                                "Samen combineren we technische expertise met creatief design om van elke gelegenheid een onvergetelijk moment te maken."
                            </p>
                            <p class="text-white">- Het EventHub Team</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- EXTRA RUIMTE TUSSEN DE SECTIES --}}
    <div class="my-5 py-4"></div>

    {{-- ONDERSTE SECTIE MET EVENEMENTEN --}}
    <div class="container mt-4 pt-3">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 id="evenementen">Evenementen</h1>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <a href="{{ route('events.index', ['view' => 'list']) }}"
                       class="btn btn-outline-primary text-white {{ $viewType === 'list' ? 'active' : '' }}">
                        <i class="bi bi-list"></i> Lijst
                    </a>
                    <a href="{{ route('events.index', ['view' => 'grid']) }}"
                       class="btn btn-outline-primary text-white {{ $viewType === 'grid' ? 'active' : '' }}">
                        <i class="bi bi-grid"></i> Grid
                    </a>
                </div>
            </div>
        </div>

        @if($viewType === 'list')
            <div class="list-group">
                @foreach($events as $event)
                    <div class="list-group-item list-group-item-action">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                @if($event->mainImage)
                                    <img src="{{ asset('storage/' . $event->mainImage->image_path) }}"
                                         alt="{{ $event->title }}"
                                         class="img-fluid rounded" style="max-height: 100px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                                        <i class="bi bi-calendar-event" style="font-size: 2rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        {{ $event->title }}
                                        @if(auth()->check() && $event->isFavoritedByUser(auth()->id()))
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @endif
                                    </h5>
                                    <small>{{ $event->start_date->format('d-m-Y H:i') }}</small>
                                </div>
                                <p class="mb-1">{{ Str::limit($event->description, 150) }}</p>
                                <small><i class="bi bi-geo-alt"></i> {{ $event->location }}</small>
                            </div>
                            <div class="col-md-2 text-end">
                                <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm">Bekijk</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($events as $event)
                    <div class="col">
                        <div class="card event-card h-100">
                            @if($event->mainImage)
                                <img src="{{ asset('storage/' . $event->mainImage->image_path) }}"
                                     class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-calendar-event" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="card-title">{{ $event->title }}</h5>
                                    @auth
                                        <form action="{{ route('events.favorite', $event) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 favorite-btn {{ $event->isFavoritedByUser(auth()->id()) ? 'active' : '' }}">
                                                <i class="bi bi-star{{ $event->isFavoritedByUser(auth()->id()) ? '-fill' : '' }}" style="font-size: 1.5rem;"></i>
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                                <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> {{ $event->start_date->format('d-m-Y H:i') }}<br>
                                        <i class="bi bi-geo-alt"></i> {{ $event->location }}
                                    </small>
                                </p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm">Meer info</a>
                                @if($event->price)
                                    <span class="badge bg-success float-end">€{{ number_format($event->price, 2) }}</span>
                                @else
                                    <span class="badge bg-info float-end">Gratis</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-4 mb-5">
            {{ $events->links() }}
        </div>
    </div>
@endsection
