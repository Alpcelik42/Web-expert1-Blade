{{-- resources/views/auth/profile.blade.php --}}
@extends('layouts.app')

@section('title', 'Mijn Profiel')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-circle"></i> Profielgegevens</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Naam</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telefoon</label>
                            <input type="tel" name="phone" class="form-control"
                                   value="{{ old('phone', $user->phone) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Adres</label>
                            <textarea name="address" class="form-control"
                                      rows="3">{{ old('address', $user->address) }}</textarea>
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

                        <button type="submit" class="btn btn-primary">Bijwerken</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Favorites -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="bi bi-star-fill"></i> Favoriete Evenementen</h5>
                </div>
                <div class="card-body">
                    @if($favorites->count() > 0)
                        <div class="row row-cols-1 row-cols-md-2 g-4">
                            @foreach($favorites as $event)
                                <div class="col">
                                    <div class="card h-100">
                                        @if($event->mainImage)
                                            <img src="{{ asset('storage/' . $event->mainImage->image_path) }}"
                                                 class="card-img-top" alt="{{ $event->title }}"
                                                 style="height: 150px; object-fit: cover;">
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $event->title }}</h6>
                                            <p class="card-text small">
                                                <i class="bi bi-calendar"></i> {{ $event->start_date->format('d-m-Y H:i') }}<br>
                                                <i class="bi bi-geo-alt"></i> {{ $event->location }}
                                            </p>
                                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary">
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

            <!-- Upcoming Events -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Aankomende Evenementen</h5>
                </div>
                <div class="card-body">
                    @if($upcomingEvents->count() > 0)
                        <div class="list-group">
                            @foreach($upcomingEvents as $event)
                                <a href="{{ route('events.show', $event) }}"
                                   class="list-group-item list-group-item-action">
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

            <!-- Past Events -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Afgelopen Evenementen</h5>
                </div>
                <div class="card-body">
                    @if($pastEvents->count() > 0)
                        <div class="list-group">
                            @foreach($pastEvents as $event)
                                <a href="{{ route('events.show', $event) }}"
                                   class="list-group-item list-group-item-action">
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
