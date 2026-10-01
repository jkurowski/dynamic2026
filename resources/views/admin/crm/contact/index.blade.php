@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-book-open"></i>Kontakty</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                        <a href="#" class="btn btn-primary btn-add">Dodaj kontakt</a>
                    </div>
                </div>
            </div>
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
                        <th>Nazwisko</th>
                        <th>E-mail</th>
                        <th>Telefon</th>
                        <th>Telefon 2</th>
                        <th>Kategoria</th>
                        <th>Data utworzenia</th>
                        <th>Data edycji</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="content"></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group form-group-submit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <a href="#" class="btn btn-primary btn-add">Dodaj kontakt</a>
                </div>
            </div>
        </div>
    </div>
    <div id="modalHolder"></div>
    @routes('contact')
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
            $(document).ready(function() {
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
                            columns: function (idx, title, th) {
                                return $(th).text().trim() !== '';
                            }
                        }
                    ],
                    language: {
                        "url": "{{ asset('/js/polish.json') }}"
                    },
                    iDisplayLength: 13,
                    ajax: {
                        url: "{{ route('admin.crm.contact.datatable') }}",
                        type: "GET",
                        data: function(d) {
                            d.minDate = $('#form_date_from').val();
                            d.maxDate = $('#form_date_to').val();
                        }
                    },
                    columns: [
                        /* 0 */ { data: null, defaultContent: '' },
                        /* 1 */ { data: 'name', name: 'name' },
                        /* 2 */ { data: 'surname', name: 'surname' },
                        /* 3 */ { data: 'email', name: 'email' },
                        /* 4 */ { data: 'phone_1', name: 'phone_1' },
                        /* 5 */ { data: 'phone_2', name: 'phone_2' },
                        /* 6 */ { data: 'category_id', name: 'category_id' },
                        /* 7 */ { data: 'created_at', name: 'created_at' },
                        /* 8 */ { data: 'updated_at', name: 'updated_at' },
                        /* 9 */ {data: 'actions', name: 'actions'}
                    ],
                    bSort: false,
                    columnDefs: [
                        // { className: 'text-center', targets: [5,6,7] },
                        { className: 'select-column', targets: [6] },
                        { className: 'option-120 text-end', targets: [9] }
                    ],
                    initComplete: function () {
                        this.api().columns('.select-column').every(function () {
                            const column = this;
                            const select = $('<select class="selectpicker"><option value="">' + this.header().textContent + '</option></select>')
                                .appendTo($(column.header()).empty())
                                .on('change', function () {
                                    const val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });
                            column.data().unique().sort().each(function (value) {
                                if (value !== null) {
                                    select.append('<option value="' + value + '">' + value + '</option>')
                                }
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

                        $(".btn-add").click((d) => {
                            d.preventDefault();
                            const modalHolder = $('#modalHolder');
                            modalHolder.empty();

                            jQuery.ajax({
                                url: '{{ route('admin.crm.contact.create') }}',
                                success: function (response) {
                                    if (response) {
                                        //TODO: Uruchomienie modala z opcja dodawania
                                        modalHolder.append(response);
                                        initModal('store');

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
                            const modalHolder = $('#modalHolder');
                            modalHolder.empty();

                            jQuery.ajax({
                                url: route('admin.crm.contact.edit', dataId),
                                type: 'GET',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if(response) {
                                        //TODO: Uruchomienie modala z opcja edycji
                                        modalHolder.append(response);
                                        initModal('update');

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

                t.on('init.dt', function () {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });
                });

                t.on('order.dt search.dt', function () {
                    const count = t.page.info().recordsDisplay;
                    t.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function (cell, i) {
                        cell.innerHTML = count - i
                    });
                }).draw();
            });
            const token = '{{ csrf_token() }}';

            function initModal(action = 'update'){
                const modal = document.getElementById('portletModal'),
                    bootstrapModal = new bootstrap.Modal(modal),
                    form = document.getElementById('modalForm'),
                    inputName = $('#inputname'),
                    inputSurname = $('#inputsurname'),
                    inputEmail = $('#inputemail'),
                    inputPhone1 = $('#inputphone_1'),
                    inputPhone2 = $('#inputphone_2'),
                    inputNote = $('#inputNote'),
                    entry = $('#inputEntryId');

                bootstrapModal.show();

                modal.addEventListener('shown.bs.modal', function () {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl, {
                            trigger : 'hover'
                        })
                    });
                })

                modal.addEventListener('hidden.bs.modal', function () {
                    $('#portletModal').remove();
                })

                const alert = $('.alert-danger');

                const url = action === 'update' ? route('admin.crm.contact.' + action, { contact: entry.val() }) : route('admin.crm.contact.' + action);
                const method = action === 'update' ? 'PUT' : 'POST';

                form.addEventListener('submit', (e) => {
                    e.preventDefault();

                    jQuery.ajax({
                        url: url,
                        method: method,
                        data: {
                            '_token': token,
                            'name': inputName.val(),
                            'surname': inputSurname.val(),
                            'email': inputEmail.val(),
                            'phone_1': inputPhone1.val(),
                            'phone_2': inputPhone2.val(),
                            'note': inputNote.val(),
                        },
                        success: function () {
                            bootstrapModal.hide();
                            toastr.options =
                                {
                                    "closeButton": true,
                                    "progressBar": true
                                }
                            toastr.success("Wpis został zaktualizowany");
                        },
                        error: function (result) {
                            if (result.responseJSON.data) {
                                alert.html('');
                                $.each(result.responseJSON.data, function (key, value) {
                                    alert.show();
                                    alert.append('<span>' + value + '</span>');
                                });
                            }
                        }
                    });
                });
            }
        </script>
    @endpush
@endsection
