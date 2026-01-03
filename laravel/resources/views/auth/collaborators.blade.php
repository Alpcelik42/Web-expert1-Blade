{{-- resources/views/events/collaborators.blade.php --}}
@extends('layouts.app')

@section('title', 'Co-hosts beheren - ' . $event->title)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-people"></i> Co-hosts voor {{ $event->title }}
                    </h5>
                </div>
                <div class="card-body">
                    @if($collaborators->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Naam</th>
                                    <th>E-mail</th>
                                    <th>Rol</th>
                                    <th>Acties</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($collaborators as $collaborator)
                                    <tr>
                                        <td>{{ $collaborator->user->name }}</td>
                                        <td>{{ $collaborator->user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $collaborator->isOwner() ? 'primary' : 'info' }}">
                                                {{ $collaborator->role === 'owner' ? 'Eigenaar' : 'Co-host' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(!$collaborator->isOwner())
                                                <form action="{{ route('events.collaborators.destroy', [$event, $collaborator]) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Weet je zeker dat je deze co-host wilt verwijderen?')">
                                                        <i class="bi bi-trash"></i> Verwijder
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">Eigenaar</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Nog geen co-hosts toegevoegd.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-person-plus"></i> Co-host uitnodigen</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('events.collaborators.store', $event) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">E-mailadres van de gebruiker</label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="email@voorbeeld.nl" required>
                            <div class="form-text">
                                De gebruiker krijgt toegang om dit evenement te bewerken.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send"></i> Uitnodigen
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h6><i class="bi bi-info-circle"></i> Informatie</h6>
                    <ul class="small text-muted">
                        <li>Co-hosts kunnen het evenement bewerken</li>
                        <li>Co-hosts kunnen tickets toevoegen/verwijderen</li>
                        <li>Alleen de eigenaar kan co-hosts verwijderen</li>
                        <li>De eigenaar kan niet verwijderd worden</li>
                    </ul>
                    <a href="{{ route('events.show', $event) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-left"></i> Terug naar evenement
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
