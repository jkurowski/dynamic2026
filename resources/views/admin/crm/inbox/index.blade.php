@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-inbox"></i>Leads</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-header border-bottom card-nav">
            <nav class="nav">
                <a class="nav-link {{ Request::routeIs('admin.crm.inbox.*') ? ' active' : '' }}"
                    href="{{ route('admin.crm.inbox.index') }}"><span class="fe-check-square"></span>Wszystkie</a>
                <a class="nav-link {{ Request::routeIs('admin.externalLeads*') ? ' active' : '' }}"
                    href="{{ route('admin.externalLeads.index') }}"><span class="fe-external-link"></span>Zewnętrzne</a>
                <a class="nav-link {{ Request::routeIs('admin.crm.assign-leads.*') ? ' active' : '' }}"
                    href="{{ route('admin.crm.assign-leads.index') }}"><span class="fe-save"></span>Przypisywanie
                    automatyczne</a>
            </nav>
        </div>

        <div class="card-header card-nav">
            <nav class="nav">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-4">

                        </div>
                        <div class="col-8 d-flex justify-content-end">
                            <div class="row">
                                <div class="col">
                                    <label for="form_invest" class="form-label">Inwestycja</label>
                                    <select id="form_invest" name="invest" class="form-control">
                                        <option value="">Wszystkie</option>
                                        @foreach ($investments as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="form_rooms_from" class="form-label">Pokoje</label>
                                    <input type="text" class="form-control" id="form_rooms_from" name="area_rooms">
                                </div>
                                <div class="col">
                                    <label for="form_area_from" class="form-label">Pow. od</label>
                                    <input type="text" class="form-control" id="form_area_from" name="area_from">
                                </div>
                                <div class="col">
                                    <label for="form_area_to" class="form-label">Pow. do</label>
                                    <input type="text" class="form-control" id="form_area_to" name="area_to">
                                </div>
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
            <div class="table-overflow">
                <table class="table data-table w-100">
                    <thead class="thead-default">
                        <tr>
                            <th>#</th>
                            <th>Imię</th>
                            <th>E-mail</th>
                            <th>Telefon</th>
                            <th>Inwestycja</th>
                            <th>Piętro</th>
                            <th>Pokoje</th>
                            <th>Pow.</th>
                            <th>Miejsce</th>
                            <th class="colsearch">Źródło</th>
                            <th class="colsearch">Kampania</th>
                            <th>Data</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="content"></tbody>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('/js/datatables.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/bootstrap-select/bootstrap-select.min.js') }}" charset="utf-8"></script>

        <link href="{{ asset('/css/datatables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
        <link href="{{ asset('/js/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">

        <script>
            $(function() {
                $.fn.dataTable.ext.errMode = 'none';
                $('.data-table').on('error.dt', function(e, settings, techNote, message) {
                    console.log('An error has been reported by DataTables: ', message);
                });
            });
            $(document).ready(function() {
                const t = $('.data-table').DataTable({
                    processing: true,
                    serverSide: false,
                    responsive: true,
                    dom: 'Brtip',
                    "buttons": [{
                            extend: 'excelHtml5',
                            header: true,
                            exportOptions: {
                                modifier: {
                                    order: 'index',
                                    page: 'all',
                                    search: 'applied'
                                }
                            }
                        },
                        {
                            extend: 'csv',
                            header: true,
                            exportOptions: {
                                modifier: {
                                    order: 'index',
                                    page: 'all',
                                    search: 'applied'
                                }
                            }
                        },
                        {
                            extend: 'colvis',
                            columns: function(idx, title, th) {
                                return $(th).text().trim() !== '';
                            }
                        }
                    ],
                    language: {
                        "url": "{{ asset('/js/polish.json') }}"
                    },
                    iDisplayLength: 13,
                    ajax: {
                        url: "{{ route('admin.crm.inbox.datatable') }}",
                        type: "GET",
                        data: function(d) {
                            d.minDate = $('#form_date_from').val();
                            d.maxDate = $('#form_date_to').val();
                            d.areaFrom = $('#form_area_from').val();
                            d.areaTo = $('#form_area_to').val();
                            d.rooms = $('#form_rooms_from').val();
                            d.invest = $('#form_invest').val();
                        }
                    },
                    columns: [
                        /* 0 */
                        {
                            data: null,
                            defaultContent: ''
                        },
                        /* 1 */
                        {
                            data: 'name',
                            name: 'name'
                        },
                        /* 2 */
                        {
                            data: 'mail',
                            name: 'mail'
                        },
                        /* 3 */
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        /* 4 */
                        {
                            data: 'investment_name',
                            name: 'investment_name'
                        },
                        /* 5 */
                        {
                            data: 'floor',
                            name: 'floor'
                        },
                        /* 6 */
                        {
                            data: 'rooms',
                            name: 'rooms'
                        },
                        /* 7 */
                        {
                            data: 'area',
                            name: 'area'
                        },
                        /* 8 */
                        {
                            data: 'source',
                            name: 'source'
                        },
                        /* 9 */
                        {
                            data: 'referrer',
                            name: 'referrer'
                        },
                        /* 10 */
                        {
                            data: 'campaign',
                            name: 'campaign'
                        },
                        /* 11 */
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        /* 12 */
                        {
                            data: 'actions',
                            name: 'actions'
                        }
                    ],
                    bSort: false,
                    columnDefs: [{
                            className: 'text-center',
                            targets: [5, 6, 7]
                        },
                        {
                            className: 'select-column',
                            targets: [9, 10]
                        },
                        {
                            className: 'option-60 text-end',
                            targets: [12]
                        }
                    ],
                    initComplete: function() {
                        this.api().columns('.select-column').every(function() {
                            const column = this;
                            const select = $('<select class="selectpicker"><option value="">' + this
                                    .header().textContent + '</option></select>')
                                .appendTo($(column.header()).empty())
                                .on('change', function() {
                                    const val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column.search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });
                            column.data().unique().sort().each(function(value) {
                                if (value !== null) {
                                    select.append('<option value="' + value + '">' + value +
                                        '</option>')
                                }
                            });
                            $('.selectpicker').selectpicker();
                        });

                        $('<button class="dt-button buttons-refresh">Odśwież tabelę</button>').appendTo(
                            'div.dt-buttons');

                        $(".buttons-refresh").click(function() {
                            t.ajax.reload();
                        });

                        $('#form_date_to, #form_date_from').datepicker({
                            orientation: 'bottom',
                            format: 'yyyy-mm-dd',
                            todayHighlight: true,
                            language: "pl"
                        });

                        $('#form_date_to, #form_date_from, #form_area_to, #form_area_from, #form_rooms_from, #form_invest')
                            .on('change', function() {
                                t.ajax.reload();
                            });
                    },
                    createdRow: function(row) {
                        $('td', row).eq(6).addClass('text-break w-20');
                    }
                });

                t.on('init.dt', function() {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                        '[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function(tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });
                });

                t.on('order.dt search.dt', function() {
                    const count = t.page.info().recordsDisplay;
                    t.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = count - i
                    });
                }).draw();
            });
        </script>
    @endpush
@endsection
