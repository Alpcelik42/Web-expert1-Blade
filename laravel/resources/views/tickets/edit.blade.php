{{-- resources/views/tickets/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Ticket Bewerken')

@section('content')
    <div class="row py-5">
        <div class="col-md-8 mx-auto mt-5">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h4 class="mb-0"><i class="bi bi-ticket-perforated"></i> Ticket Bewerken: {{ $ticket->type }}</h4>
                    <small class="opacity-75">{{ $event->title }}</small>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success rounded-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tickets.update', [$event->id, $ticket->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Sectie 1: Ticket type & prijs --}}
                        <div class="p-3 mb-3 bg-light rounded-3">
                            <div class="mb-3">
                                <label for="type" class="form-label">Ticket Type *</label>
                                <input type="text" class="form-control @error('type') is-invalid @enderror"
                                       id="type" name="type" value="{{ old('type', $ticket->type) }}" required>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Prijs (€) *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" step="0.01" min="0"
                                               class="form-control @error('price') is-invalid @enderror"
                                               id="price" name="price"
                                               value="{{ old('price', $ticket->price) }}" required>
                                    </div>
                                    @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="max_per_user" class="form-label">Max. per persoon *</label>
                                    <input type="number" min="1"
                                           class="form-control @error('max_per_user') is-invalid @enderror"
                                           id="max_per_user" name="max_per_user"
                                           value="{{ old('max_per_user', $ticket->max_per_user) }}" required>
                                    @error('max_per_user')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Sectie 2: Beschikbaarheid --}}
                        <div class="p-3 mb-3 bg-light rounded-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="quantity_available" class="form-label">Aantal beschikbaar *</label>
                                    <input type="number" min="{{ $ticket->quantity_sold }}"
                                           class="form-control @error('quantity_available') is-invalid @enderror"
                                           id="quantity_available" name="quantity_available"
                                           value="{{ old('quantity_available', $ticket->quantity_available) }}" required>
                                    @error('quantity_available')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Reeds verkocht: {{ $ticket->quantity_sold }}</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nog beschikbaar</label>
                                    <input type="text" class="form-control"
                                           value="{{ $ticket->quantity_available - $ticket->quantity_sold }}" disabled>
                                </div>
                            </div>
                        </div>

                        {{-- Sectie 3: Beschrijving --}}
                        <div class="p-3 mb-3 bg-light rounded-3">
                            <label for="description" class="form-label">Beschrijving</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description', $ticket->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Knoppen --}}
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-secondary btn-cancel me-md-2">
                                <i class="bi bi-x-circle"></i> Annuleren
                            </a>
                            <button type="submit" class="btn btn-primary btn-save me-md-2">
                                <i class="bi bi-check-circle"></i> Opslaan
                            </button>
                            <button type="button" class="btn btn-danger btn-delete" onclick="confirmDelete()">
                                <i class="bi bi-trash"></i> Verwijderen
                            </button>
                        </div>
                    </form>

                    <form id="delete-form" action="{{ route('tickets.destroy', [$event->id, $ticket->id]) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Hover effecten */
        .btn-cancel:hover {
            background-color: #dc3545 !important;
            color: #fff !important;
            border-color: #dc3545 !important;
        }

        .btn-save:hover {
            background-color: #0b5ed7 !important;
            border-color: #0a58ca !important;
        }

        .btn-delete:hover {
            background-color: #dc3545 !important;
            border-color: #b02a37 !important;
        }
    </style>

    <script>
        function confirmDelete() {
            if (confirm('Weet je zeker dat je dit ticket type wilt verwijderen? Dit kan niet ongedaan worden gemaakt.')) {
                document.getElementById('delete-form').submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Valideer dat nieuwe beschikbaarheid niet lager is dan reeds verkocht
            const quantitySold = {{ $ticket->quantity_sold }};
            const quantityInput = document.getElementById('quantity_available');

            if (quantityInput) {
                quantityInput.addEventListener('change', function() {
                    if (parseInt(this.value) < quantitySold) {
                        alert('Aantal beschikbaar kan niet lager zijn dan reeds verkochte tickets (' + quantitySold + ')');
                        this.value = quantitySold;
                    }
                });
            }
        });
    </script>
@endsection
