{{-- resources/views/events/calendar.blade.php --}}
@extends('layouts.app')

@section('title', 'Evenementen Kalender')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">

    <style>
        /* =====================
           HEADER
        ====================== */
        .calendar-header {
            padding: 2rem 0 1rem 0;
        }

        .calendar-header h1 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .calendar-header small {
            color: #6c757d;
        }

        /* =====================
           CALENDAR CARD
        ====================== */
        #calendar {
            max-width: 1100px;
            margin: 0 auto 4rem auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
            padding: 26px;
        }

        /* =====================
           FULLCALENDAR UI
        ====================== */
        .fc-toolbar {
            display: flex;
            justify-content: space-between; /* buttons links/rechts */
            align-items: center;
            margin-bottom: 1.8rem;
        }

        .fc-toolbar-title {
            font-size: 1.7rem;
            font-weight: 600;
        }

        /* Buttons */
        .fc-button {
            background-color: #f1f3f5;
            color: #212529;
            border: none;
            border-radius: 10px;
            padding: 6px 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .fc-button:hover {
            background-color: #e9ecef;
        }

        .fc-button.fc-button-active {
            background-color: #0d6efd;
            color: #ffffff;
        }

        /* Weekdagen */
        .fc-col-header-cell-cushion {
            text-decoration: none !important;
            font-weight: 600;
            color: #495057;
            padding: 10px 0;
        }

        /* Datum nummers */
        .fc-daygrid-day-number {
            text-decoration: none;
            font-weight: 500;
            color: #868e96;
            position: relative;
            display: inline-block;
            width: 26px;
            height: 26px;
            line-height: 26px;
            text-align: center;
            border-radius: 50%;
        }

        /* Vandaag alleen achter het dagcijfer */
        .fc-day-today .fc-daygrid-day-number {
            background-color: #000000;
            color: #ffffff !important;
            font-weight: 600;
        }

        /* Events */
        .fc-event {
            background-color: #0d6efd;
            border: none;
            border-radius: 8px;
            padding: 4px 8px;
            font-size: 0.85rem;
            box-shadow: 0 6px 14px rgba(13,110,253,0.25);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .fc-event:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .calendar-header {
                padding: 1.5rem 0 1rem 0;
                text-align: center;
            }

            #calendar {
                padding: 18px;
            }

            .fc-toolbar {
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
            }

            .fc-toolbar .fc-left,
            .fc-toolbar .fc-right {
                justify-content: center;
            }
        }

        .btn-gradient-hover {
            border: 2px solid #0b5ed7 !important ;
            color: #fff;
            background: transparent;
            border-radius: 10px;
            padding: 6px 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-gradient-hover:hover {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border-color: #0d6efd;
            color: #fff;
        }

    </style>
@endpush

@section('content')
    <div class="calendar-header d-flex justify-content-between align-items-center flex-wrap mt-5 mb-5">
        <div>
            <h1 class="mb-1">Evenementen Kalender</h1>
            <small class="text-white">Overzicht van alle geplande evenementen</small>
        </div>

        <a href="{{ route('events.index') }}" class="btn btn-gradient-hover mt-3 mt-md-0">
            <i class="bi bi-list"></i> Lijstweergave
        </a>

    </div>

    <div id="calendar"></div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/nl.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const events = @json($events);

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'nl',

                headerToolbar: {
                    left: 'dayGridMonth,timeGridWeek,timeGridDay', // view buttons links
                    center: 'title',
                    right: 'prev,next today' // navigatie rechts
                },

                events: events.map(event => ({
                    title: event.title,
                    start: event.start_date,
                    end: event.end_date,
                    url: `/events/${event.id}`,
                    extendedProps: { location: event.location }
                })),

                eventMouseEnter(info) {
                    if (info.event.extendedProps.location) {
                        info.el.setAttribute('title', info.event.extendedProps.location);
                    }
                },

                eventClick(info) {
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
