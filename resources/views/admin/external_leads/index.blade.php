@extends('admin.layout')


@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-inbox"></i>Leady zewnętrzne</h4>
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
                        <div class="col-12 d-flex justify-content-end">
                            <div class="row">
                                <div class="col">
                                    <label for="form_source" class="form-label">Źródło</label>
                                    <select id="form_source" name="invest" class="form-control">

                                        <option value="">Wszystkie</option>
                                        @foreach ($select_options as $i)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="form_invest" class="form-label">Inwestycja</label>
                                    <select id="form_invest" name="invest" class="form-control">
                                        <option value="">Wszystkie</option>
                                        @foreach ($investments as $i)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="form_assigned" class="form-label">Przypisane do opiekuna</label>
                                    <select id="form_assigned" name="assigned" class="form-control">
                                        <option value="">Wszystkie</option>
                                        <option value="1">Nie</option>
                                        <option value="2">Tak</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="form_assigned_investment" class="form-label">Przypisane do
                                        inwestycji</label>
                                    <select id="form_assigned_investment" name="assigned_investment" class="form-control">
                                        <option value="">Wszystkie</option>
                                        <option value="1">Nie</option>
                                        <option value="2">Tak</option>
                                    </select>
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
                            <th>Mieszkanie/lokal</th>
                            <th class="colsearch">Źródło</th>
                            <th>Data</th>
                            <th>Akcje</th>
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
                        'colvis',
                    ],
                    language: {
                        "url": "{{ asset('/js/polish.json') }}"
                    },
                    iDisplayLength: 13,
                    ajax: {
                        url: "{{ route('admin.externalLeads.datatable') }}",
                        type: "GET",
                        data: function(d) {
                            d.minDate = $('#form_date_from').val();
                            d.maxDate = $('#form_date_to').val();
                            d.user_assigned = $('#form_assigned').val();
                            d.investment_assigned = $('#form_assigned_investment').val();
                            d.invest = $('#form_invest').val();
                            d.source = $('#form_source').val();
                            console.log(d);
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
                            data: 'invest',
                            name: 'invest'
                        },
                        /* 5 */
                        {
                            data: 'property',
                            name: 'property'
                        },
                        /* 6 */
                        {
                            data: 'source',
                            name: 'source'
                        },
                        /* 7 */
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        /* 8 */
                        {
                            data: 'actions',
                            name: 'actions'
                        }
                    ],
                    bSort: false,
                    initComplete: function() {
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

                        $('#form_date_to, #form_date_from,  #form_invest,#form_source, #form_assigned,#form_assigned_investment')
                            .on('change', function() {
                                t.ajax.reload();
                            });
                    },
                    createdRow: function(row) {
                        $('td', row).eq(6).addClass('text-break w-20');
                    }
                });

                t.on('init.dt', function() {
                    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
                        tooltipTriggerEl))
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


                const modal = document.getElementById('actionsModal');
                const modalBody = modal.querySelector('.modal-body');

                async function fetchSelectedOptions(message_id) {
                    const response = await fetch("{{ route('admin.externalLeads.assign.getSelected') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message_id
                        })
                    })

                    if (response.ok) {
                        toastr.success('Pobrano dane');
                        return response.json();
                    }

                    toastr.error('Błąd pobierania danych');
                }

                modal.addEventListener('shown.bs.modal', (e) => {
                    const clickedButton = e.relatedTarget;
                    const messageId = clickedButton.getAttribute('data-msg-id');
                    const msgIdInput = document.querySelector('input[name="assign_message_id"]');
                    const userSelect = modalBody.querySelector('select[name="assign_user_id"]');
                    const investmentSelect = modalBody.querySelector('select[name="assign_investment_id"]');

                    userSelect.disabled = true;
                    investmentSelect.disabled = true;

                    if (msgIdInput) {
                        msgIdInput.value = messageId;
                    }
                    const response = fetchSelectedOptions(messageId)

                    response.then(data => {
                        userSelect.value = data.selected_user ? data.selected_user : '';
                        investmentSelect.value = data.selected_investment ? data.selected_investment :
                            '';
                    }).then(() => {
                        userSelect.disabled = false;
                        investmentSelect.disabled = false;
                    })
                })

            });
        </script>
    @endpush
    <!-- Modal -->
    <div class="modal fade" id="actionsModal" tabindex="-1" aria-labelledby="actionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-white" id="actionsModalLabel">Przypisz lead</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class='fe-x'></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.externalLeads.assign') }}" method="POST" id="assign-lead-form">
                        @csrf
                        <input type="hidden" name="assign_message_id" value="">
                        <div class="row w-100 form-group">
                            @include('form-elements.html-select', [
                                'label' => 'Przypisz do opiekuna inwestycji',
                                'name' => 'assign_user_id',
                                'selected' => 0,
                                'select' => $select_users,
                            ])
                        </div>
                        <div class="row w-100 form-group">
                            @include('form-elements.html-select', [
                                'label' => 'Przypisz do investycji',
                                'name' => 'assign_investment_id',
                                'selected' => 0,
                                'select' => $select_investments,
                            ])
                        </div>

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-primary" form="assign-lead-form">Zapisz</button>
                </div>
            </div>
        </div>
    </div>
@endsection
