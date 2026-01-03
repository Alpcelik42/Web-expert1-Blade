{{-- resources/views/auth/profile.blade.php --}}
@extends('layouts.app')

@section('title', 'Mijn Profiel')

@push('styles')
    <style>
        /* =====================
           Algemene card styling
        ===================== */
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        }

        /* =====================
           Card headers
        ===================== */
        .card-header {
            border-radius: 12px 12px 0 0;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .card-header.bg-primary { background: linear-gradient(135deg, #0d6efd, #0b5ed7); }
        .card-header.bg-warning { background: linear-gradient(135deg, #ffc107, #ffca2c); }
        .card-header.bg-success { background: linear-gradient(135deg, #198754, #28a745); }
        .card-header.bg-secondary { background: linear-gradient(135deg, #6c757d, #495057); }

        /* =====================
           Form styling
        ===================== */
        .form-control {
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 6px rgba(13,110,253,0.25);
        }

        /* =====================
           Buttons
        ===================== */
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: #fff;
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            color: #fff;
        }

        .btn-outline-primary {
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }

        /* =====================
           Event Cards in Favorites
        ===================== */
        .card-body img {
            border-radius: 8px 8px 0 0;
        }

        .card-body h6 {
            font-weight: 600;
        }

        .list-group-item {
            border-radius: 8px;
            margin-bottom: 6px;
            transition: all 0.2s ease;
        }

        .list-group-item:hover {
            background-color: rgba(13,110,253,0.05);
        }

        /* =====================
           Small text styling
        ===================== */
        small.text-muted {
            font-size: 0.85rem;
        }
        .btn-primary,
        .btn-outline-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem; /* ruimte tussen icon en tekst als je een icon hebt */
        }

    </style>
@endpush

@section('content')
    <div class="row">

        {{-- PROFIELGEGEVENS --}}
        <div class="col-md-4 mt-5 mb-5">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-person-circle fs-4 me-2"></i> <h5 class="mb-0">Profielgegevens</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Naam</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telefoon</label>
                            <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Adres</label>
                            <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <hr>

                        <h6>Wachtwoord wijzigen</h6>
                        <div class="mb-3">
                            <label class="form-label">Huidig wachtwoord</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nieuw wachtwoord</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bevestig nieuw wachtwoord</label>
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-pencil-square"></i> Bewerken
                        </button>

                    </form>
                </div>
            </div>
        </div>

        {{-- FAVORIETEN & EVENEMENTEN --}}
        <div class="col-md-8 mt-5 mb-5">

            {{-- Favoriete Evenementen --}}
            <div class="card mb-4">
                <div class="card-header bg-warning text-white d-flex align-items-center">
                    <i class="bi bi-star-fill fs-4 me-2"></i> <h5 class="mb-0">Favoriete Evenementen</h5>
                </div>
                <div class="card-body">
                    @if($favorites->count() > 0)
                        <div class="row row-cols-1 row-cols-md-2 g-4">
                            @foreach($favorites as $event)
                                <div class="col">
                                    <div class="card h-100">
                                        @if($event->mainImage)
                                            <img src="{{ asset('storage/' . $event->mainImage->image_path) }}"
                                                 class="card-img-top" alt="{{ $event->title }}" style="height:150px; object-fit:cover;">
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $event->title }}</h6>
                                            <p class="card-text small">
                                                <i class="bi bi-calendar"></i> {{ $event->start_date->format('d-m-Y H:i') }}<br>
                                                <i class="bi bi-geo-alt"></i> {{ $event->location }}
                                            </p>
                                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary w-100">
                                                Bekijk
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Je hebt nog geen favoriete evenementen.</p>
                    @endif
                </div>
            </div>

            {{-- Aankomende Evenementen --}}
            <div class="card mb-4">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <i class="bi bi-calendar-check fs-4 me-2"></i> <h5 class="mb-0">Aankomende Evenementen</h5>
                </div>
                <div class="card-body">
                    @if($upcomingEvents->count() > 0)
                        <div class="list-group">
                            @foreach($upcomingEvents as $event)
                                <a href="{{ route('events.show', $event) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $event->title }}</h6>
                                        <small>{{ $event->start_date->format('d-m-Y H:i') }}</small>
                                    </div>
                                    <small class="text-muted">{{ $event->location }}</small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Je hebt geen aankomende evenementen.</p>
                    @endif
                </div>
            </div>

            {{-- Afgelopen Evenementen --}}
            <div class="card">
                <div class="card-header bg-secondary text-white d-flex align-items-center">
                    <i class="bi bi-calendar-event fs-4 me-2"></i> <h5 class="mb-0">Afgelopen Evenementen</h5>
                </div>
                <div class="card-body">
                    @if($pastEvents->count() > 0)
                        <div class="list-group">
                            @foreach($pastEvents as $event)
                                <a href="{{ route('events.show', $event) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $event->title }}</h6>
                                        <small>{{ $event->start_date->format('d-m-Y') }}</small>
                                    </div>
                                    <small class="text-muted">{{ $event->location }}</small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Je hebt nog geen evenementen bezocht.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
