@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-home"></i><a href="{{route('admin.developro.investment.index')}}">Inwestycje</a><span class="d-inline-flex me-2 ms-2">/</span>{{$investment->name}}<span class="d-inline-flex me-2 ms-2">-</span>Logi aktywności</h4>
                </div>
            </div>
        </div>
        @include('admin.developro.investment_shared.menu')

        <div class="card-header card-nav">
            <nav class="nav">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-4">

                        </div>
                        <div class="col-8 d-flex justify-content-end">
                            <div class="row">
                                <div class="col">
                                    <label for="form_date_from" class="form-label">Data od</label>
                                    <input type="text" class="form-control" id="form_date_from" name="date_from">
                                </div>
                                <div class="col">
                                    <label for="form_date_to" class="form-label">Data do</label>
                                    <input type="text" class="form-control" id="form_date_to" name="date_to">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table data-table mb-0 w-100">
                        <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>Wydarzenie</th>
                            <th class="text-center">Data</th>
                            <th class="text-center">Godzina</th>
                            <th>Nazwa</th>
                            <th>Użytkownik</th>
                            <th>Klient</th>
                            <th class="text-center">Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @routes('events')
    @push('scripts')
        <script src="{{ asset('/js/datatables.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/bootstrap-select/bootstrap-select.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/moment.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>

        <link href="{{ asset('/js/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/datatables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/js/datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
        <script>
            $(function () {
                $.fn.dataTable.ext.errMode = 'none';
                $('.data-table').on( 'error.dt', function ( e, settings, techNote, message ) {
                    console.log( 'An error has been reported by DataTables: ', message );
                });
            });
            $(document).ready(function(){
                const t = $('.data-table').DataTable({
                    processing: true,
                    serverSide: false,
                    responsive: true,
                    dom: 'Brtip',
                    "buttons": [
                        {
                            extend: 'excelHtml5',
                            header: true,
                            exportOptions: {
                                modifier: {
                                    order: 'index',  // 'current', 'applied', 'index',  'original'
                                    page: 'all',      // 'all',     'current'
                                    search: 'applied'     // 'none', 'applied', 'removed'
                                }
                            }
                        },
                        {
                            extend: 'csv',
                            header: true,
                            exportOptions: {
                                modifier: {
                                    order: 'index',  // 'current', 'applied', 'index',  'original'
                                    page: 'all',      // 'all',     'current'
                                    search: 'applied'     // 'none', 'applied', 'removed'
                                }
                            }
                        },
                        {
                            extend: 'colvis',
                            columns: function (idx, title, th) {
                                return $(th).text().trim() !== '';
                            }
                        }
                    ],
                    language: {
                        "url": "{{ asset('/js/polish.json') }}"
                    },
                    iDisplayLength: 30,
                    ajax: "{{route('admin.developro.investment.eventtable', $investment)}}",
                    columns: [
                        /* 0 */ {data: null, defaultContent:''},
                        /* 1 */ {data: 'type', name: 'type'},
                        /* 2 */ {data: 'start', name: 'start'},
                        /* 3 */ {data: 'time', name: 'time'},
                        /* 4 */ {data: 'name', name: 'name'},
                        /* 5 */ {data: 'user_id', name: 'user_id'},
                        /* 6 */ {data: 'client_id', name: 'client_id'},
                        /* 7 */ {data: 'active', name: 'active'},
                        /* 8 */ {data: 'actions', name: 'actions'},
                    ],
                    bSort: false,
                    columnDefs: [
                        { "visible": false, "targets": 0 },
                        {className: 'text-center', targets: [2,3,7]},
                        {className: 'select-column', targets: [1,6]},
                        {className: 'option-120 text-end', targets: [8]}
                    ],
                    initComplete: function () {
                        this.api().columns('.select-column').every(function () {
                            const column = this;
                            const select = $('<select class="selectpicker"><option value="">' + this.header().textContent + '</option></select>')
                                .appendTo($(column.header()).empty())
                                .on('change', function () {
                                    const val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function (value) {

                                if (value && value.indexOf("span") >= 0) {
                                    value = value.replace(/<[^>]+>/g, '');
                                }

                                if(value)
                                {
                                    select.append('<option value="'+value+'">' + value + '</option>')
                                }
                            });
                            $('.selectpicker').selectpicker();
                        });
                        $('<button class="dt-button buttons-refresh">Odśwież tabelę</button>').appendTo('div.dt-buttons');

                        $('<div class="dt-buttons-right"><a href="#" class="btn btn-primary btn-addevent">Dodaj wydarzenie</a></div>').insertAfter('div.dt-buttons');

                        $(".btn-addevent").click((d) => {
                            d.preventDefault();
                            jQuery.ajax({
                                type: 'POST',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    'date': new Date().toISOString().split('T')[0],
                                    'allDay': 1
                                },
                                url: '{{ route('admin.crm.calendar.create') }}',
                                success: function (response) {
                                    if (response) {
                                        $('body').append(response);
                                        const modal = document.getElementById('portletModal');
                                        modal.addEventListener('hidden.bs.modal', function () {
                                            t.ajax.reload(null, false);
                                        });
                                    } else {
                                        alert('Error');
                                    }
                                }
                            });
                        });

                        $(".buttons-refresh").click(function () {
                            t.ajax.reload();
                        });
                    },
                    "drawCallback": function () {
                        $(".confirmForm").click(function (d) {
                            d.preventDefault();
                            const c = $(this).closest("form");
                            const a = c.attr("action");
                            const b = $("meta[name='csrf-token']").attr("content");
                            $.confirm({
                                title: "Potwierdzenie usunięcia",
                                message: "Czy na pewno chcesz usunąć?",
                                buttons: {
                                    Tak: {
                                        "class": "btn btn-primary",
                                        action: function () {
                                            $.ajax({
                                                url: a,
                                                type: "DELETE",
                                                data: {
                                                    _token: b,
                                                }
                                            }).done(function () {
                                                t.row(c.parents('tr')).remove().draw();
                                            });
                                        }
                                    },
                                    Nie: {
                                        "class": "btn btn-secondary",
                                        action: function () {
                                        }
                                    }
                                }
                            })
                        });

                        $(".action-button").click(function (d) {
                            d.preventDefault();
                            const dataId = $(this).data("bs-id");

                            jQuery.ajax({
                                url: route('admin.crm.calendar.event.edit', dataId),
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if(response) {
                                        $('body').append(response);
                                        const modal = document.getElementById('portletModal');
                                        modal.addEventListener('hidden.bs.modal', function () {
                                            t.ajax.reload(null, false);
                                        })
                                    } else {
                                        alert('Error');
                                    }
                                }
                            });
                        });
                    },
                });
                t.on( 'init.dt', function () {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });
                })
                t.on( 'order.dt search.dt', function () {
                    const count = t.page.info().recordsDisplay;
                    t.column(0, {
                        search:'applied',
                        order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = count - i
                    } );
                }).draw();
            });
        </script>
    @endpush
@endsection

