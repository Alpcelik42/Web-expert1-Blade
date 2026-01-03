{{-- resources/views/tickets/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Ticket Toevoegen')

@section('content')
    <div class="row py-5">
        <div class="col-md-8 mx-auto mt-5">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h4 class="mb-0">
                        <i class="bi bi-ticket-perforated"></i>
                        Ticket toevoegen voor: {{ $event->title }}
                    </h4>
                </div>

                <div class="card-body p-4">

                    {{-- Success --}}
                    @if(session('success'))
                        <div class="alert alert-success rounded-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tickets.store', $event->id) }}" method="POST">
                        @csrf

                        {{-- Sectie 1: Ticket info --}}
                        <div class="p-3 mb-3 bg-light rounded-3">
                            <div class="mb-3">
                                <label for="type" class="form-label">Ticket Type *</label>
                                <input type="text"
                                       class="form-control @error('type') is-invalid @enderror"
                                       id="type"
                                       name="type"
                                       value="{{ old('type') }}"
                                       placeholder="Bijv: Standaard, VIP, Early Bird"
                                       required>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Beschrijving</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="3"
                                          placeholder="Extra info over dit ticket">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Sectie 2: Prijs & Limieten --}}
                        <div class="p-3 mb-3 bg-light rounded-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Prijs (€) *</label>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price"
                                           name="price"
                                           value="{{ old('price') }}"
                                           required>
                                    @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="max_per_user" class="form-label">Max per persoon *</label>
                                    <input type="number" min="1"
                                           class="form-control @error('max_per_user') is-invalid @enderror"
                                           id="max_per_user"
                                           name="max_per_user"
                                           value="{{ old('max_per_user', 1) }}"
                                           required>
                                    @error('max_per_user')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Sectie 3: Beschikbaarheid --}}
                        <div class="p-3 mb-3 bg-light rounded-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="quantity_available" class="form-label">Aantal beschikbaar *</label>
                                    <input type="number" min="1"
                                           class="form-control @error('quantity_available') is-invalid @enderror"
                                           id="quantity_available"
                                           name="quantity_available"
                                           value="{{ old('quantity_available') }}"
                                           required>
                                    @error('quantity_available')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Evenement capaciteit</label>
                                    <input type="text"
                                           class="form-control"
                                           value="{{ $event->capacity ?? 'Geen limiet' }}"
                                           disabled>
                                </div>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="alert alert-info rounded-3">
                            <h6><i class="bi bi-info-circle"></i> Evenement info</h6>
                            <ul class="mb-0">
                                <li>Start ticketverkoop:
                                    {{ $event->ticket_sale_start
                                        ? $event->ticket_sale_start->format('d-m-Y H:i')
                                        : 'Direct' }}
                                </li>
                                <li>Evenementdatum: {{ $event->start_date->format('d-m-Y H:i') }}</li>
                                <li>Locatie: {{ $event->location }}</li>
                            </ul>
                        </div>

                        {{-- Knoppen --}}
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('events.show', $event->id) }}"
                               class="btn btn-outline-secondary btn-cancel me-md-2">
                                <i class="bi bi-x-circle"></i> Annuleren
                            </a>
                            <button type="submit" class="btn btn-primary btn-save">
                                <i class="bi bi-check-circle"></i> Ticket toevoegen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Styles --}}
    <style>
        .btn-cancel:hover {
            background-color: #dc3545 !important;
            color: #fff !important;
            border-color: #dc3545 !important;
        }

        .btn-save:hover {
            background-color: #0b5ed7 !important;
            border-color: #0a58ca !important;
        }
    </style>

    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const capacity = {{ $event->capacity ?? 'null' }};
            const quantityInput = document.getElementById('quantity_available');

            if (capacity && quantityInput) {
                quantityInput.max = capacity;
                quantityInput.addEventListener('change', function () {
                    if (this.value > capacity) {
                        alert('Aantal tickets kan niet hoger zijn dan ' + capacity);
                        this.value = capacity;
                    }
                });
            }
        });
    </script>
@endsection
