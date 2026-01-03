{{-- resources/views/events/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nieuw Evenement Aanmaken')

@push('styles')
    <style>
        /* =====================
           CARD & HEADER
        ===================== */
        .card-header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: #fff;
            font-weight: 600;
            border-radius: 12px 12px 0 0;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        .card-body {
            padding: 2rem;
        }

        /* =====================
           FORM CONTROLS
        ===================== */
        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background-color: #f8f9fa;
            border-radius: 12px;
        }

        .form-section h5 {
            margin-bottom: 1rem;
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 6px rgba(13,110,253,0.25);
        }

        /* =====================
           BUTTONS
        ===================== */
        .btn-gradient {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-gradient:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            color: #fff !important;
        }

        .btn-gradient:hover i {
            color: #fff !important;
        }

        /* Transparante Annuleren-knop zonder border */
        .btn-cancel-transparent {
            background-color: transparent;
            color: #6c757d; /* grijze tekst */
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-cancel-transparent:hover {
            background-color: #dc3545; /* rood */
            color: #fff;
        }

        /* =====================
           TOGGLE SWITCH
        ===================== */
        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            margin-left: 0;
        }

        /* =====================
           FILE INPUT
        ===================== */
        input[type="file"] {
            border-radius: 8px;
        }

        /* =====================
           RESPONSIVE
        ===================== */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row mt-5 mb-5">
        <div class="col-md-8 mx-auto mt-5">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-calendar-plus fs-3 me-2"></i>
                    <h4 class="mb-0">Nieuw Evenement Aanmaken</h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- ALGEMENE INFO --}}
                        <div class="form-section">
                            <h5>Algemene info</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Titel *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">Locatie *</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror"
                                           id="location" name="location" value="{{ old('location') }}" required>
                                    @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Beschrijving *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="short_description" class="form-label">Korte beschrijving (optioneel)</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror"
                                          id="short_description" name="short_description" rows="2">{{ old('short_description') }}</textarea>
                                @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Maximaal 500 karakters</small>
                            </div>
                        </div>

                        {{-- DATUM & TIJD --}}
                        <div class="form-section">
                            <h5>Data & Tijd</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">Startdatum *</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="start_time" class="form-label">Starttijd *</label>
                                    <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                           id="start_time" name="start_time" value="{{ old('start_time', '19:00') }}" required>
                                    @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">Einddatum *</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                           id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_time" class="form-label">Eindtijd *</label>
                                    <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                           id="end_time" name="end_time" value="{{ old('end_time', '22:00') }}" required>
                                    @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- CATEGORIE & PRIJZEN --}}
                        <div class="form-section">
                            <h5>Categorie & Prijs</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">Categorie *</label>
                                    <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">Selecteer een categorie</option>
                                        @foreach([
                                            'Muziek','Sport','Kunst & Cultuur','Workshop','Networking','Feest','Conferentie','Lezing',
                                            'Festival','Expositie','Beurs','Theater','Film','Food & Drink','Gezondheid','Onderwijs','Anders'
                                        ] as $cat)
                                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Prijs (€) *</label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price', 0) }}" required>
                                    <div class="form-text">Voer 0 in voor gratis evenement</div>
                                    @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- TICKETS --}}
                        <div class="form-section">
                            <h5>Tickets</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="capacity" class="form-label">Maximaal aantal deelnemers</label>
                                    <input type="number" min="1" class="form-control @error('capacity') is-invalid @enderror"
                                           id="capacity" name="capacity" value="{{ old('capacity') }}">
                                    @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="available_tickets" class="form-label">Beschikbare tickets</label>
                                    <input type="number" min="0" class="form-control @error('available_tickets') is-invalid @enderror"
                                           id="available_tickets" name="available_tickets" value="{{ old('available_tickets') }}">
                                    @error('available_tickets')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- LOCATIE --}}
                        <div class="form-section">
                            <h5>Locatie</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="latitude" class="form-label">Latitude (optioneel)</label>
                                    <input type="number" step="0.00000001" class="form-control @error('latitude') is-invalid @enderror"
                                           id="latitude" name="latitude" value="{{ old('latitude') }}">
                                    @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="longitude" class="form-label">Longitude (optioneel)</label>
                                    <input type="number" step="0.00000001" class="form-control @error('longitude') is-invalid @enderror"
                                           id="longitude" name="longitude" value="{{ old('longitude') }}">
                                    @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- OPTIES --}}
                        <div class="form-section">
                            <h5>Opties</h5>
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_online" name="is_online" value="1" {{ old('is_online') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_online">Online evenement</label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">Uitgelicht evenement</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3" id="online_link_container" style="display: none;">
                                <label for="online_link" class="form-label">Online meeting link</label>
                                <input type="url" class="form-control @error('online_link') is-invalid @enderror"
                                       id="online_link" name="online_link" value="{{ old('online_link') }}">
                                @error('online_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="images" class="form-label">Afbeeldingen (optioneel)</label>
                                <input type="file" class="form-control @error('images') is-invalid @enderror"
                                       id="images" name="images[]" multiple accept="image/*">
                                <div class="form-text">Maximaal 5 afbeeldingen (JPG, PNG, GIF, max 2MB per afbeelding)</div>
                                @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('events.index') }}" class="btn btn-cancel-transparent me-md-2">
                                <i class="bi bi-x-circle"></i> Annuleren
                            </a>
                            <button type="submit" class="btn btn-gradient">
                                <i class="bi bi-check-circle"></i> Evenement Aanmaken
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const isOnlineCheckbox = document.getElementById('is_online');
                const onlineLinkContainer = document.getElementById('online_link_container');
                const onlineLinkInput = document.getElementById('online_link');

                function toggleOnlineLink() {
                    if (isOnlineCheckbox.checked) {
                        onlineLinkContainer.style.display = 'block';
                        onlineLinkInput.required = true;
                        onlineLinkContainer.style.opacity = 0;
                        setTimeout(() => onlineLinkContainer.style.opacity = 1, 50);
                    } else {
                        onlineLinkContainer.style.opacity = 0;
                        setTimeout(() => {
                            onlineLinkContainer.style.display = 'none';
                            onlineLinkInput.required = false;
                            onlineLinkInput.value = '';
                        }, 200);
                    }
                }

                isOnlineCheckbox.addEventListener('change', toggleOnlineLink);
                toggleOnlineLink();

                const today = new Date().toISOString().split('T')[0];
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');

                startDateInput.min = today;
                if(!startDateInput.value) startDateInput.value = today;
                startDateInput.addEventListener('change', function() {
                    endDateInput.min = this.value;
                    if(endDateInput.value && endDateInput.value < this.value) endDateInput.value = this.value;
                });
                if(startDateInput.value) endDateInput.min = startDateInput.value;

                const startTimeInput = document.getElementById('start_time');
                const endTimeInput = document.getElementById('end_time');
                if(!startTimeInput.value) startTimeInput.value = '19:00';
                if(!endTimeInput.value) endTimeInput.value = '22:00';

                startTimeInput.addEventListener('change', function() {
                    if(endTimeInput.value && endTimeInput.value <= this.value){
                        const [h,m] = this.value.split(':').map(Number);
                        let endH = h + 3; if(endH >= 24) endH -=24;
                        endTimeInput.value = endH.toString().padStart(2,'0') + ':' + m.toString().padStart(2,'0');
                    }
                });

                const capacityInput = document.getElementById('capacity');
                const availableTicketsInput = document.getElementById('available_tickets');
                capacityInput.addEventListener('change', function() {
                    if(this.value && !availableTicketsInput.value) availableTicketsInput.value = this.value;
                });
                availableTicketsInput.addEventListener('change', function() {
                    if(capacityInput.value && this.value > capacityInput.value){
                        alert('Beschikbare tickets kunnen niet meer zijn dan de capaciteit!');
                        this.value = capacityInput.value;
                    }
                });
            });
        </script>
    @endpush
@endsection
