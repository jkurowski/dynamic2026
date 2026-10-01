@extends('admin.settings.index')

@section('settings')
    <div class="container-fluid p-3">
        <div class="card-header card-nav m-0">
            <nav class="nav">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-4">

                        </div>
                        <div class="col-8 d-flex justify-content-end">
                            <div class="row">
                                <div class="col">
                                    <label for="form_date_from" class="form-label">Data od</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="form_date_from" name="date_from">
                                        <span class="input-group-text" data-focuses="form_date_from" aria-hidden="true"><i class="fe-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="form_date_to" class="form-label">Data do</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="form_date_to" name="date_to">
                                        <span class="input-group-text" data-focuses="form_date_to" aria-hidden="true"><i class="fe-calendar"></i></span>
                                    </div>
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
                            <th scope="col"></th>
                            <th scope="col">Użytkownik</th>
                            <th scope="col" class="text-center">Moduł</th>
                            <th scope="col">Nazwa</th>
                            <th scope="col" class="text-center">Akcja</th>
                            <th scope="col">Co się zmieniło</th>
                            <th scope="col">URL</th>
                            <th scope="col">Referer</th>
                            <th scope="col" class="text-center">Adres IP</th>
                            <th scope="col" class="text-center">Data utworzenia</th>
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
                    /*
                        Przyciski „Excel" i „CSV" zdjęte 2026-08-16 (decyzja usera — ze WSZYSTKICH list).
                        Dziennik czynności to nie są dane osobowe klientów, ale zapis KTO CO ZROBIŁ
                        w systemie — wynoszenie go bez śladu jest tym bardziej nie na miejscu,
                        że dziennik ma być właśnie śladem.
                        Eksport robimy na życzenie, po stronie serwera — wzór: lista klientów
                        (`admin.crm.clients.export.xlsx`). Opis: `docs/analiza/39-eksport-tabel-zdjety.md`.
                    */
                    "buttons": [
                        {
                            extend: 'colvis',
                            columns: function (idx, title, th) {
                                return $(th).text().trim() !== '';
                            }
                        }
                    ],
                    language: {
                        "url": "{{ asset('/js/polish.json') }}?v={{ filemtime(public_path('js/polish.json')) }}"
                    },
                    iDisplayLength: 30,
                    ajax: {
                        url: "{{ route('admin.log.datatable') }}",
                        type: "GET",
                        data: function(d) {
                            d.minDate = $('#form_date_from').val();
                            d.maxDate = $('#form_date_to').val();
                        }
                    },
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'log_name', name: 'log_name'},
                        {data: 'subject', name: 'subject'},
                        {data: 'action', name: 'action'},
                        {data: 'changes', name: 'changes', orderable: false, searchable: false},
                        {data: 'route', name: 'route'},
                        {data: 'referer', name: 'referer'},
                        {data: 'ip', name: 'ip'},
                        {data: 'created_at', name: 'created_at'}
                    ],
                    /*
                        Sortowanie włączone 2026-08-10 (wzorzec z listy ofert).

                        `order: []` zostawia kolejność ustawioną przez kontroler — bez niego
                        DataTables posortowałby listę po pierwszej kolumnie rosnąco.

                        Sortowania NIE przyjmują: kolumna akcji (przyciski, nie dane) i kolumny
                        z listą wyboru w nagłówku (`select-column`) — tam kliknięcie w filtr
                        przestawiałoby przy okazji porządek.
                    */
                    order: [],
                    columnDefs: [
                        {className: 'text-center', targets: [2, 4, 8, 9]},
                        {className: 'select-column', targets: [1, 2, 4]},
                        { orderable: false, targets: [1, 2, 4] }
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
                                        .search(val ? val : '', true, false)
                                        .draw();
                                });
                            column.data().unique().sort().each(function (value) {

                                let text = $('<div>').html(value).find('[data-filter]').data('filter');

                                if (!text) {
                                    text = $('<div>').html(value).text().trim();
                                }

                                select.append('<option value="' + text + '">' + text + '</option>')
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

