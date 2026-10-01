@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-book-open"></i>Oferty</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                        <a href="{{ route('admin.crm.offer.create') }}" class="btn btn-primary">Nowa oferta</a>
                    </div>
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

        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table data-table mb-0 w-100">
                        <thead class="thead-default">
                            <tr>
                                <th>#</th>
                                <th>Wysyłający</th>
                                <th>Klient</th>
                                <th>Temat</th>
                                <th>Status</th>
                                <th>Data utworzenia</th>
                                <th>Data wysłania</th>
                                <th>Data otwarcia</th>
                                <th>Data wygaśnięcia</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="content"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group form-group-submit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{ route('admin.crm.offer.create') }}" class="btn btn-primary">Nowa oferta</a>
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
                        url: "{{ route('admin.crm.offer.datatable') }}",
                        type: "GET",
                        data: function(d) {
                            d.minDate = $('#form_date_from').val();
                            d.maxDate = $('#form_date_to').val();
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
                            data: 'user',
                            name: 'user'
                        },
                        /* 2 */
                        {
                            data: 'contact',
                            name: 'contact'
                        },
                        /* 3 */
                        {
                            data: 'title',
                            name: 'title'
                        },
                        /* 4 */
                        {
                            data: 'status',
                            name: 'status'
                        },
                        /* 5 */
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        /* 6 */
                        {
                            data: 'sended_at',
                            name: 'sended_at'
                        },
                        /* 7 */
                        {
                            data: 'readed_at',
                            name: 'readed_at'
                        },
                        /* 8 */
                        {
                            data: 'date_end',
                            name: 'date_end'
                        },
                        /* 9 */
                        {
                            data: 'actions',
                            name: 'actions'
                        }
                    ],
                    bSort: false,
                    columnDefs: [{
                            className: 'select-column',
                            targets: [4]
                        },
                        {
                            className: 'text-center',
                            targets: [4, 5, 6, 7, 8]
                        },
                        {
                            className: 'option-120',
                            targets: [9]
                        },
                        {
                            className: 'text-end',
                            targets: [9]
                        }
                    ]
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
