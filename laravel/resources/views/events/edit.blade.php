{{-- resources/views/events/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Evenement Bewerken')

@section('content')
    <div class="row py-5">
        <div class="col-md-8 mx-auto mt-5">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Evenement Bewerken: {{ $event->title }}</h4>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Sectie 1: Titel & Locatie --}}
                        <div class="p-3 mb-3 bg-light rounded-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Titel *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $event->title) }}" required>
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">Locatie *</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror"
                                           id="location" name="location" value="{{ old('location', $event->location) }}" required>
                                    @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Sectie 2: Beschrijvingen --}}
                        <div class="p-3 mb-3 bg-light rounded-3">
                            <div class="mb-3">
                                <label for="description" class="form-label">Beschrijving *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="4" required>{{ old('description', $event->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="short_description" class="form-label">Korte beschrijving</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror"
                                          id="short_description" name="short_description" rows="2">{{ old('short_description', $event->short_description) }}</textarea>
                                @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Sectie 3: Start & Eind --}}
                        <div class="p-3 mb-3 bg-light rounded-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">Startdatum *</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                           id="start_date" name="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="start_time" class="form-label">Starttijd *</label>
                                    <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                           id="start_time" name="start_time" value="{{ old('start_time', $event->start_time) }}" required>
                                    @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">Einddatum *</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                           id="end_date" name="end_date" value="{{ old('end_date', $event->end_date->format('Y-m-d')) }}" required>
                                    @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_time" class="form-label">Eindtijd *</label>
                                    <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                           id="end_time" name="end_time" value="{{ old('end_time', $event->end_time) }}" required>
                                    @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Sectie 4: Categorie & Prijs --}}
                        <div class="p-3 mb-3 bg-light rounded-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">Categorie *</label>
                                    <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">Selecteer een categorie</option>
                                        <option value="Muziek" {{ old('category', $event->category) == 'Muziek' ? 'selected' : '' }}>Muziek</option>
                                        <option value="Sport" {{ old('category', $event->category) == 'Sport' ? 'selected' : '' }}>Sport</option>
                                        <option value="Kunst & Cultuur" {{ old('category', $event->category) == 'Kunst & Cultuur' ? 'selected' : '' }}>Kunst & Cultuur</option>
                                        <option value="Workshop" {{ old('category', $event->category) == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                                        <option value="Networking" {{ old('category', $event->category) == 'Networking' ? 'selected' : '' }}>Networking</option>
                                        <option value="Feest" {{ old('category', $event->category) == 'Feest' ? 'selected' : '' }}>Feest</option>
                                        <option value="Conferentie" {{ old('category', $event->category) == 'Conferentie' ? 'selected' : '' }}>Conferentie</option>
                                        <option value="Lezing" {{ old('category', $event->category) == 'Lezing' ? 'selected' : '' }}>Lezing</option>
                                        <option value="Festival" {{ old('category', $event->category) == 'Festival' ? 'selected' : '' }}>Festival</option>
                                        <option value="Expositie" {{ old('category', $event->category) == 'Expositie' ? 'selected' : '' }}>Expositie</option>
                                        <option value="Beurs" {{ old('category', $event->category) == 'Beurs' ? 'selected' : '' }}>Beurs</option>
                                        <option value="Theater" {{ old('category', $event->category) == 'Theater' ? 'selected' : '' }}>Theater</option>
                                        <option value="Film" {{ old('category', $event->category) == 'Film' ? 'selected' : '' }}>Film</option>
                                        <option value="Food & Drink" {{ old('category', $event->category) == 'Food & Drink' ? 'selected' : '' }}>Food & Drink</option>
                                        <option value="Gezondheid" {{ old('category', $event->category) == 'Gezondheid' ? 'selected' : '' }}>Gezondheid</option>
                                        <option value="Onderwijs" {{ old('category', $event->category) == 'Onderwijs' ? 'selected' : '' }}>Onderwijs</option>
                                        <option value="Anders" {{ old('category', $event->category) == 'Anders' ? 'selected' : '' }}>Anders</option>
                                    </select>
                                    @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Prijs (€) *</label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price', $event->price) }}" required>
                                    @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Sectie 5: Capaciteit & Beschikbare tickets --}}
                        <div class="p-3 mb-3 bg-light rounded-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="capacity" class="form-label">Maximaal aantal deelnemers</label>
                                    <input type="number" min="1" class="form-control @error('capacity') is-invalid @enderror"
                                           id="capacity" name="capacity" value="{{ old('capacity', $event->capacity) }}">
                                    @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="available_tickets" class="form-label">Beschikbare tickets</label>
                                    <input type="number" min="0" class="form-control @error('available_tickets') is-invalid @enderror"
                                           id="available_tickets" name="available_tickets" value="{{ old('available_tickets', $event->available_tickets) }}">
                                    @error('available_tickets')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Sectie 6: Status --}}
                        <div class="p-3 mb-3 bg-light rounded-3">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', $event->status) == 'active' ? 'selected' : '' }}>Actief</option>
                                    <option value="inactive" {{ old('status', $event->status) == 'inactive' ? 'selected' : '' }}>Inactief</option>
                                    <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Geannuleerd</option>
                                    <option value="completed" {{ old('status', $event->status) == 'completed' ? 'selected' : '' }}>Voltooid</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Knoppen --}}
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-outline-secondary btn-cancel me-md-2">
                                <i class="bi bi-x-circle"></i> Annuleren
                            </a>
                            <button type="submit" class="btn btn-primary btn-save">
                                <i class="bi bi-check-circle"></i> Opslaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Annuleren knop hover */
        .btn-cancel:hover {
            background-color: #dc3545 !important;
            color: #fff !important;
            border-color: #dc3545 !important;
        }

        /* Opslaan knop hover */
        .btn-save:hover {
            background-color: #0b5ed7 !important;
            border-color: #0a58ca !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
            });
        });
    </script>
@endsection
