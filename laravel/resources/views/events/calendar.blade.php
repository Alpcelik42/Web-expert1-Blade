{{-- resources/views/events/calendar.blade.php --}}
@extends('layouts.app')

@section('title', 'Evenementen Kalender')

@push('styles')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css'>
    <style>
        #calendar {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .fc-event {
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Evenementen Kalender</h1>
        <a href="{{ route('events.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-list"></i> Lijstweergave
        </a>
    </div>

    <div id="calendar"></div>
@endsection

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/nl.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const events = @json($events);

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'nl',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: events.map(event => ({
                    title: event.title,
                    start: event.start_date,
                    end: event.end_date,
                    url: `/events/${event.id}`,
                    extendedProps: {
                        location: event.location
                    }
                })),
                eventMouseEnter: function(info) {
                    info.el.setAttribute('title', info.event.extendedProps.location);
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    window.location.href = info.event.url;
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                }
            });

            calendar.render();
        });
    </script>
@endpush
