@extends('front.auth.client.layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-calendar"></i>Kalendarz</h4>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body card-body-rem">
                <div id="calendar" class="mb-5"></div>
            </div>
        </div>
    </div>
@endsection
@routes('client_area_calendar')
@push('scripts')
    <script src="{{ asset('/js/fullcalendar/main.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/fullcalendar/pl.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/moment.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>

    <link href="{{ asset('/js/fullcalendar/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/js/datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <style>.fc-daygrid-day:before{display:none !important;}</style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                expandRows: true,
                aspectRatio: 2,
                timeZone: 'local',
                eventSources: [
                    {
                        url: route('front.client.area.calendar.events.show')
                    }
                ],
                headerToolbar: {
                    end: 'dayGridMonth,timeGridWeek,today prev,next'
                },
                initialView: 'dayGridMonth',
                locale: 'pl',
                nowIndicator: true,
                selectable: false,
                displayEventTime: true,
                eventDisplay: 'block',
                allDayText: '',
                slotDuration: '00:60:00',
                slotLabelFormat: [
                    {hour: 'numeric', minute: '2-digit'},
                ],
                editable: false,
                eventDrop: function (info) {
                    jQuery.ajax({
                        type: 'PUT',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'date': info.event.startStr,
                            'allday': info.event.allDay,
                        },
                        url: route('admin.crm.calendar.event.move', info.event.id),
                        success: function () {
                            toastr.options =
                                {
                                    "closeButton": true,
                                    "progressBar": true
                                }
                            toastr.success("Wpis został zaktualizowany");
                        },
                        error: function () {
                            console.log('eventDrop error');
                        }
                    });
                },
                eventContent: function (arg) {
                    const event = arg.event;
                    return {html: event.title}
                },
                eventDidMount: function (info) {
                    const event_date = info.event.start.toLocaleDateString('pl-PL', {
                        weekday: 'long',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    const popover = new bootstrap.Popover(info.el, {
                        container: 'body',
                        trigger: 'click focus',
                        placement: 'bottom',
                        sanitize: false,
                        html: true,
                        title: info.event.extendedProps.name,
                        content: '<div class="popover-time">' + event_date + '</div>' + (info.event.extendedProps.note ? '<div class="popover-note">' + info.event.extendedProps.note + '</div>' : '')
                    });
                    info.el.addEventListener('shown.bs.popover', () => {
                        const iDiv = document.createElement('div');
                        iDiv.className = 'popover-backdrop';
                        document.getElementById('calendar').appendChild(iDiv);

                        iDiv.addEventListener("click", function () {
                            popover.hide();
                            iDiv.remove();
                        });
                    })
                },
            });
            calendar.render();
        });
    </script>
@endpush
