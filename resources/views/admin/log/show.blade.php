@extends('admin.settings.index')

@section('settings')
    <div class="container-fluid">
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
                            <th>Użytkownik</th>
                            <th class="text-center">Moduł</th>
                            <th class="text-center">Metoda</th>
                            <th>URL</th>
                            <th>Referer</th>
                            <th class="text-center">Adres IP</th>
                            <th class="text-center">Data utworzenia</th>
                        </tr>
                        </thead>
                        <tbody class="content"></tbody>
                    </table>
                </div>
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
                    ajax: {
                        url: "{{route('admin.log.datatable-user', $causer)}}",
                        type: "GET",
                        data: function(d) {
                            d.minDate = $('#form_date_from').val();
                            d.maxDate = $('#form_date_to').val();
                        }
                    },
                    columns: [
                        /* 0 */ {data: 'id', name: 'id'},
                        /* 1 */ {data: 'name', name: 'name'},
                        /* 2 */ {data: 'log_name', name: 'log_name'},
                        /* 3 */ {data: 'method', name: 'method'},
                        /* 4 */ {data: 'route', name: 'route'},
                        /* 5 */ {data: 'referer', name: 'referer'},
                        /* 6 */ {data: 'ip', name: 'ip'},
                        /* 7 */ {data: 'created_at', name: 'created_at'}
                    ],
                    bSort: false,
                    columnDefs: [
                        {className: 'text-center', targets: [2, 3, 6, 7]},
                        {className: 'select-column', targets: [2, 3]}
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
                                if (value.indexOf("span") >= 0) {
                                    value = value.replace(/<[^>]+>/g, '');
                                }
                                select.append('<option value="' + value + '">' + value + '</option>')
                            });
                            $('.selectpicker').selectpicker();
                        });

                        $('<button class="dt-button buttons-refresh">Odśwież tabelę</button>').appendTo('div.dt-buttons');

                        $(".buttons-refresh").click(function () {
                            t.ajax.reload();
                        });

                        $('#form_date_to, #form_date_from').datepicker({
                            orientation: 'bottom',
                            format: 'yyyy-mm-dd',
                            todayHighlight: true,
                            language: "pl"
                        });

                        $('#form_date_to, #form_date_from').on('change', function() {
                            t.ajax.reload();
                        });
                    },
                });
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

