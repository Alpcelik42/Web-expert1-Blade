<!DOCTYPE html>
<!-- resources/views/tickets/create.blade.php -->
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Toevoegen - EventHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">EventHub</a>
        <div class="navbar-nav ms-auto">
            <a href="{{ route('events.show', $event->id) }}" class="nav-link">
                <i class="bi bi-arrow-left"></i> Terug naar evenement
            </a>
        </div>
    </div>
</nav>

<main class="container mt-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-ticket-perforated"></i>
                        Ticket toevoegen voor: {{ $event->title }}
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tickets.store', $event->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="type" class="form-label">Ticket Type *</label>
                            <input type="text" class="form-control @error('type') is-invalid @enderror"
                                   id="type" name="type" value="{{ old('type') }}" required
                                   placeholder="Bijv: Standaard, VIP, Early Bird">
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Naam van het ticket type</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Prijs (€) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">€</span>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price') }}" required>
                                </div>
                                @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Prijs per ticket</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_per_user" class="form-label">Max. per persoon *</label>
                                <input type="number" min="1"
                                       class="form-control @error('max_per_user') is-invalid @enderror"
                                       id="max_per_user" name="max_per_user"
                                       value="{{ old('max_per_user', 1) }}" required>
                                @error('max_per_user')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Maximum aantal tickets per gebruiker</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantity_available" class="form-label">Aantal beschikbaar *</label>
                                <input type="number" min="1"
                                       class="form-control @error('quantity_available') is-invalid @enderror"
                                       id="quantity_available" name="quantity_available"
                                       value="{{ old('quantity_available') }}" required>
                                @error('quantity_available')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Totaal aantal tickets beschikbaar</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="capacity_remaining" class="form-label">Overige capaciteit</label>
                                <input type="text" class="form-control"
                                       value="{{ $event->capacity ?? 'Geen limiet' }}" disabled>
                                <div class="form-text">Totale capaciteit evenement</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Beschrijving (optioneel)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3"
                                      placeholder="Bijv.: Inclusief drankjes, toegang tot VIP gebied...">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Extra informatie over dit ticket type</div>
                        </div>

                        <div class="alert alert-info">
                            <h5><i class="bi bi-info-circle"></i> Informatie</h5>
                            <ul class="mb-0">
                                <li>Ticketverkoop start:
                                    @if($event->ticket_sale_start)
                                        {{ $event->ticket_sale_start->format('d-m-Y H:i') }}
                                    @else
                                        Direct
                                    @endif
                                </li>
                                <li>Evenement: {{ $event->start_date->format('d-m-Y H:i') }}</li>
                                <li>Locatie: {{ $event->location }}</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('events.show', $event->id) }}"
                               class="btn btn-secondary me-md-2">
                                <i class="bi bi-x-circle"></i> Annuleren
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Ticket Toevoegen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Valideer dat quantity_available niet hoger is dan capaciteit
        const capacity = {{ $event->capacity ?? 'null' }};
        const quantityInput = document.getElementById('quantity_available');

        if (capacity && quantityInput) {
            quantityInput.max = capacity;
            quantityInput.addEventListener('change', function() {
                if (this.value > capacity) {
                    alert('Aantal tickets kan niet hoger zijn dan de totale capaciteit (' + capacity + ')');
                    this.value = capacity;
                }
            });
        }
    });
</script>
</body>
</html>
