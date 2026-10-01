@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-users"></i>Użytkownicy</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                    <a href="{{route('admin.user.create')}}" class="btn btn-primary">Dodaj użytkownika</a>
                </div>
            </div>
        </div>

        <div class="card-header border-bottom card-nav">
            <nav class="nav">
                <a class="nav-link {{ Request::routeIs('admin.user.index') ? 'active' : '' }}" href="{{route('admin.user.index')}}"><span class="fe-list"></span> Lista użytkowników</a>
                <a class="nav-link" href="{{route('admin.role.index')}}"><span class="fe-shield"></span> Grupy użytkowników</a>
            </nav>
        </div>

        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    @if (session('success'))
                        <div class="alert alert-success border-0 mb-0">
                            {{ session('success') }}
                            <script>setTimeout(function(){$(".alert").slideUp(500,function(){$(this).remove()})},3000)</script>
                        </div>
                    @endif
                    <table class="table data-table w-100">
                        <thead class="thead-default">
                        <tr>
                            <th>#</th>
                            <th>Imię</th>
                            <th>Adres e-mail</th>
                            <th>Telefon</th>
                            <th>Dział</th>
                            <th class="text-center">Status</th>
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
    </div>
    <div class="form-group form-group-submit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{route('admin.user.create')}}" class="btn btn-primary">Dodaj użytkownika</a>
                </div>
            </div>
        </div>
    </div>
    @routes('users')
    @push('scripts')
        <script src="{{ asset('/js/datatables.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/bootstrap-select/bootstrap-select.min.js') }}" charset="utf-8"></script>

        <link href="{{ asset('/css/datatables.min.css') }}" rel="stylesheet">
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
                processing:!0,serverSide:!1,responsive:!0,dom:'Brtip',"buttons":[{extend:'excelHtml5',header:!0,exportOptions:{modifier:{order:'index',page:'all',search:'applied'}}},{extend:'csv',header:!0,exportOptions:{modifier:{order:'index',page:'all',search:'applied'}}},
                        {
                            extend: 'colvis',
                            columns: function (idx, title, th) {
                                return $(th).text().trim() !== '';
                            }
                        }],language:{"url":"{{ asset('/js/polish.json') }}"},iDisplayLength:11,
                    ajax: {
                        url: "{{ route('admin.user.datatable') }}",
                        type: "GET",
                        data: function(d) {
                            d.minDate = $('#form_date_from').val();
                            d.maxDate = $('#form_date_to').val();
                        }
                    },
                    columns: [
                        /* 0 */ { data: null, defaultContent: '' },
                        /* 1 */ { data: 'name', name: 'name' },
                        /* 2 */ { data: 'email', name: 'email' },
                        /* 3 */ { data: 'phone', name: 'phone' },
                        /* 4 */ { data: 'job_position', name: 'job_position' },
                        /* 5 */ { data: 'status', name: 'status' },
                        /* 6 */ { data: 'created_at', name: 'created_at' },
                        /* 7 */ { data: 'updated_at', name: 'updated_at' },
                        /* 8 */ {data: 'actions', name: 'actions'}
                    ],
                    bSort: false,
                    columnDefs: [
                        { className: 'text-center', targets: [5] },
                        { className: 'select-column', targets: [4,5] },
                        { className: 'option-120 text-end', targets: [8] }
                    ],
                    initComplete: function () {
                        this.api().columns('.select-column').every(function() {
                            const column = this;
                            const select = $('<select class="selectpicker"><option value="">' + this.header().textContent + '</option></select>').appendTo($(column.header()).empty()).on('change', function() {
                                const val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', !0, !1).draw()
                            });
                            column.data().unique().sort().each(function(value) {

                                if(value) {
                                    if (value.indexOf("span") >= 0) {
                                        const tempElement = document.createElement('div');
                                        tempElement.innerHTML = value;
                                        const spanElement = tempElement.querySelector('span[data-filter]');
                                        value = spanElement ? spanElement.getAttribute('data-filter') : null;
                                    }
                                    select.append('<option value="' + value + '">' + value + '</option>')
                                }

                            });

                            $('.selectpicker').selectpicker();
                        });

                        $('<button class="dt-button buttons-refresh">Odśwież tabelę</button>').appendTo('div.dt-buttons');$(".buttons-refresh").click(function(){t.ajax.reload()});


                    },
                    "drawCallback":function(){$(".confirmForm").click(function(d){d.preventDefault();const c=$(this).closest("form");const a=c.attr("action");const b=$("meta[name='csrf-token']").attr("content");$.confirm({title:"Potwierdzenie usunięcia",message:"Czy na pewno chcesz usunąć?",buttons:{Tak:{"class":"btn btn-primary",action:function(){$.ajax({url:a,type:"DELETE",data:{_token:b,}}).done(function(){t.row(c.parents('tr')).remove().draw()})}},Nie:{"class":"btn btn-secondary",action:function(){}}}})})},
                });

                t.on('init.dt', function () { const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')); tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) }); }); t.on('order.dt search.dt', function () { const count = t.page.info().recordsDisplay; t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) { cell.innerHTML = count - i }); }).draw();
            });
        </script>
    @endpush
@endsection
