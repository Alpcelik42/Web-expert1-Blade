{{-- resources/views/events/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Evenementen')

@section('content')
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Evenementen</h1>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('events.index', ['view' => 'list']) }}"
                   class="btn btn-outline-primary {{ $viewType === 'list' ? 'active' : '' }}">
                    <i class="bi bi-list"></i> Lijst
                </a>
                <a href="{{ route('events.index', ['view' => 'grid']) }}"
                   class="btn btn-outline-primary {{ $viewType === 'grid' ? 'active' : '' }}">
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

    <div class="mt-4">
        {{ $events->links() }}
    </div>
@endsection
