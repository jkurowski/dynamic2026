@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-users"></i>Klienci</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit"></div>
                </div>
            </div>
        </div>

        <div class="card-header border-bottom card-nav">
            <nav class="nav">
                <a class="nav-link {{ Request::routeIs('admin.crm.clients.*') ? ' active' : '' }}"
                    href="{{ route('admin.crm.clients.index') }}"><span class="fe-home"></span>Klienci</a>
                <a class="nav-link {{ Request::routeIs('admin.crm.funnel.*') ? ' active' : '' }}"
                    href="{{ route('admin.crm.funnel.index') }}"><span class="fe-filter"></span>Lejek sprzedaży</a>
                <a class="nav-link {{ Request::routeIs('admin.crm.offer.*') ? ' active' : '' }}"
                    href="{{ route('admin.crm.offer.index') }}"><span class="fe-file"></span>Oferty</a>
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
                                    <label for="form_client_name" class="form-label">Imię</label>
                                    <input type="text" class="form-control table-filter" id="form_client_name"
                                        name="client_name">
                                </div>
                                <div class="col">
                                    <label for="form_client_lastname" class="form-label">Nazwisko</label>
                                    <input type="text" class="form-control table-filter" id="form_client_lastname"
                                        name="client_lastname">
                                </div>
                                <div class="col">
                                    <label for="form_client_phone" class="form-label">Telefon</label>
                                    <input type="text" class="form-control table-filter" id="form_client_phone"
                                        name="client_phone">
                                </div>
                                <div class="col">
                                    <label for="form_client_email" class="form-label">E-mail</label>
                                    <input type="text" class="form-control table-filter" id="form_client_email"
                                        name="client_email">
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
                                <th>#</th>
                                <th>Imię</th>
                                <th>Nazwisko</th>
                                <th>E-mail</th>
                                <th>Telefon</th>
                                <th>Miasto</th>
                                <th>Handlowiec</th>
                                <th>Status</th>
                                <th>Dołączył</th>
                                <th></th>
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
        <link href="{{ asset('/css/datatables.min.css') }}" rel="stylesheet">
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
                                    order: 'index', // 'current', 'applied', 'index',  'original'
                                    page: 'all', // 'all',     'current'
                                    search: 'applied' // 'none', 'applied', 'removed'
                                }
                            }
                        },
                        {
                            extend: 'csv',
                            header: true,
                            exportOptions: {
                                modifier: {
                                    order: 'index', // 'current', 'applied', 'index',  'original'
                                    page: 'all', // 'all',     'current'
                                    search: 'applied' // 'none', 'applied', 'removed'
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
                        url: "{{ route('admin.crm.clients.datatable') }}",
                        type: "GET",
                        data: function(d) {
                            d.name = $('#form_client_name').val();
                            d.lastname = $('#form_client_lastname').val();
                            d.phone = $('#form_client_phone').val();
                            d.email = $('#form_client_email').val();
                        }
                    },
                    columns: [{
                            data: null,
                            defaultContent: ''
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: null,
                            defaultContent: ''
                        },
                        {
                            data: 'mail',
                            name: 'mail'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: null,
                            defaultContent: ''
                        },
                        {
                            data: null,
                            defaultContent: ''
                        },
                        {
                            data: null,
                            defaultContent: ''
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'actions',
                            name: 'actions'
                        }
                    ],
                    bSort: false,
                    columnDefs: [{
                            className: 'text-center',
                            targets: [0]
                        },
                        {
                            className: 'option-120',
                            targets: [2]
                        },
                        {
                            className: 'text-end',
                            targets: [9]
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

                        $('.table-filter').on('change', function() {
                            t.ajax.reload();
                        });
                    }
                });
                t.on('init.dt', function() {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                        '[data-bs-toggle="tooltip"]'));
                    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });
                })
            });
        </script>
    @endpush
@endsection
