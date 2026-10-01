@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-calendar"></i><a href="{{route('admin.crm.clients.index')}}">Klienci</a><span
                                class="d-inline-flex me-2 ms-2">/</span><a
                                href="{{ route('admin.crm.clients.show', $client->id) }}">{{$client->name}}</a><span
                                class="d-inline-flex me-2 ms-2">/</span>Mieszkania</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit"></div>
            </div>
        </div>
        @include('admin.crm.client.client_shared.menu')
        <div class="row">
            <div class="col-4">
                @include('admin.crm.client.client_shared.aside')
            </div>
            <div class="col-8 pt-3 payments">
                @include('form-elements.back-route-button')
                <div class="card">
                    <div class="card-body card-body-rem">
                        <div class="row">
                            <div class="col-3">
                                <img src="https://placehold.co/600x400" alt="">
                            </div>
                            <div class="col-6">
                                <h2>{{ $property->name }}</h2>
                                <h4>{{ $property->investment->name }}</h4>
                                <hr>
                                <ul>
                                    <li>Powierzchnia: {{ $property->area }}m<sup>2</sup></li>
                                    <li>Ilość pokoi: {{ $property->rooms }}</li>
                                    <li>Piętro: {{ $property->floor->number }}</li>
                                    <li>Aneks kuchenny</li>
                                    <li>Data podpisania umowy: 03.07.2024</li>
                                </ul>
                            </div>
                            <div class="col-3 d-flex justify-content-center align-items-center text-center">
                                <div>
                                    @if($property->price_brutto)
                                    <h4>Kwota za całość:</h4>
                                    <h3>{{ number_format($property->price_brutto, 2, '.', ' ') }} zł</h3>
                                    @else
                                        <div class="alert alert-warning m-0" role="alert">
                                            Brak wprowadzonej ceny
                                        </div>
                                        <a href="#" class="btn btn-primary mt-3 w-100">Wprowadź cenę</a>
                                        <a href="{{ route('admin.developro.investment.payments', $property->investment) }}" class="btn btn-primary mt-3 w-100">Przeprowadź symulację</a>
                                    @endif
                                    @if($property->payments->count() > 0)

                                    <h4>Kwota najbliższej płatności:</h4>
                                    <h3 id="latestAmount"></h3>
                                    <h4 class="mt-3">Najbliższy termin płatności:</h4>
                                    <h3 id="latestDate"></h3>

                                    @endif

                                    @if($property->price_brutto)
                                        @if($property->investment->payments->count() > 0)
                                        <button class="btn btn-primary mt-3" id="generatePaymentsButton">Generuj harmonogram</button>
                                        @else
                                        Inwestycja nie posiada harmonogramu.
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 pt-3 border-top">
                            <div class="col-2 d-flex align-items-center text-center">
                                <h4>Powiązane produkty:</h4>
                            </div>
                            <div class="col-3 border-start text-center">
                                <h4><i class="fe-check-circle text-success"></i> Komórka lokatorska nr. 4</h4>
                            </div>
                            <div class="col-3 border-start text-center">
                                <h4><i class="fe-check-circle text-success"></i> Miejsce postojowe nr. 28</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table data-table mb-0 w-100">
                    <thead class="thead-default">
                    <tr>
                        <th>Termin płatności</th>
                        <th class="text-center">Wartość procentowa</th>
                        <th class="text-center">Kwota</th>
                        <th class="text-center">Data edycji</th>
                        <th class="text-center">Status płatności</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="content" id="tableContent"></tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="editModal"></div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Potwierdzenie usunięcia</h5>
                </div>
                <div class="modal-body">
                    Czy na pewno chcesz usunąć?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nie</button>
                    <button type="button" class="btn btn-primary" id="confirmDeleteButton">Tak</button>
                </div>
            </div>
        </div>
    </div>
    @routes('payments')
    @push('scripts')
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
        <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
        <script>
            const token = '{{ csrf_token() }}';

            document.addEventListener('DOMContentLoaded', function() {
                loadPaymentsTable();
            });

            function loadPaymentsTable() {
                fetch('{{ route('admin.crm.clients.payments.generate-table', [$client, $property]) }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('tableContent').innerHTML = data.html;
                        document.getElementById('latestDate').innerHTML = data.data.latestPayment;
                        document.getElementById('latestAmount').innerHTML = data.data.latestAmount;
                        attachDeleteButtonHandlers();
                        attachEditButtonHandlers();
                        attachAddPaymentButton();
                    })
                    .catch(error => {
                        console.error('There has been a problem with your fetch operation:', error);
                    });
            }

            function attachDeleteButtonHandlers() {
                const deleteButtons = document.querySelectorAll('.confirm-delete-button');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();

                        const paymentId = this.getAttribute('data-id');

                        // Show confirmation modal
                        $('#confirmDeleteModal').modal('show');

                        // Define confirmHandler function
                        const confirmHandler = () => {
                            // Close modal
                            $('#confirmDeleteModal').modal('hide');

                            // Perform delete action via AJAX
                            fetch('{{ route('admin.crm.clients.payments.destroy', '') }}/' + paymentId, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                }
                            })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        // Remove the row from the table
                                        document.getElementById('recordsArray_' + paymentId).remove();
                                    } else {
                                        alert('An error occurred.');
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        };

                        // Attach confirmHandler to confirm button click event
                        document.getElementById('confirmDeleteButton').addEventListener('click', confirmHandler);

                        // Clear event listener when modal is dismissed
                        $('#confirmDeleteModal').on('hidden.bs.modal', function () {
                            document.getElementById('confirmDeleteButton').removeEventListener('click', confirmHandler);
                        });
                    });
                });
            }

            function attachEditButtonHandlers() {
                const editButtons = document.querySelectorAll('.edit-button');

                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const paymentId = this.getAttribute('data-id');

                        // Assuming you want to load the edit modal via AJAX
                        fetch('{{ route('admin.crm.clients.payments.edit', '') }}/' + paymentId, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.text();
                        })
                        .then(html => {
                            // Assuming you have a modal container with id 'editModal' where you'll load the edit form
                            document.getElementById('editModal').innerHTML = html;

                            initPaymentModal();
                        })
                        .catch(error => {
                            console.error('There has been a problem with your fetch operation:', error);
                        });
                    });
                });
            }

            function attachAddPaymentButton() {
                const addButtons = document.getElementById('addPayment');

                addButtons.addEventListener('click', function() {
                    fetch('{{ route('admin.crm.clients.payments.create', '') }}/{{ $property->id }}', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.text();
                        })
                        .then(html => {
                            document.getElementById('editModal').innerHTML = html;
                            initPaymentModal('store');
                        })
                        .catch(error => {
                            console.error('There has been a problem with your fetch operation:', error);
                        });
                });
            }

            function initPaymentModal(action = 'update'){
                const modal = document.getElementById('paymentEditModal'),
                    paymentEditModal = new bootstrap.Modal(modal),
                    form = document.getElementById('modalForm'),
                    inputDate = $('#inputDate'),
                    inputPercent = $('#inputPercent'),
                    inputAmount = $('#inputAmount'),
                    inputStatus = $('#inputStatus'),
                    inputPaymentId = $('#inputPaymentId'),
                    inputPropertyId = $('#inputPropertyId'),
                    inputPaid = $('#inputPaid');

                paymentEditModal.show();

                modal.addEventListener('shown.bs.modal', function () {
                    $('.date-picker').datepicker({
                        format: 'yyyy-mm-dd',
                        todayHighlight: true,
                        language: "pl"
                    });

                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl, {
                            trigger : 'hover'
                        })
                    });
                })

                modal.addEventListener('hidden.bs.modal', function () {
                    $('#paymentEditModal').remove();
                })

                const alert = $('.alert-danger');

                const url = action === 'update' ? route('admin.crm.clients.payments.' + action, { payment: inputPaymentId.val() }) : route('admin.crm.clients.payments.' + action, { property: inputPropertyId.val() });
                const method = action === 'update' ? 'PUT' : 'POST';

                form.addEventListener('submit', (e) => {
                    e.preventDefault();

                    jQuery.ajax({
                        url: url,
                        method: method,
                        data: {
                            '_token': token,
                            'percent': inputPercent.val(),
                            'amount': inputAmount.val(),
                            'due_date': inputDate.val(),
                            'status': inputStatus.val(),
                            'paid_at': inputPaid.val(),
                            'property_id': inputPropertyId.val()
                        },
                        success: function () {
                            paymentEditModal.hide();
                            toastr.options =
                                {
                                    "closeButton": true,
                                    "progressBar": true
                                }
                            toastr.success("Wpis został zaktualizowany");
                            document.getElementById('tableContent').innerHTML = '';
                            loadPaymentsTable();
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

            @if($property->investment->payments->count() > 0 && $property->price)
            document.getElementById('generatePaymentsButton').addEventListener('click', function() {
                fetch('{{ route('admin.crm.clients.payments.generate', [$client, $property]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadPaymentsTable();
                        } else {
                            alert('Failed to generate payments.');
                        }
                    });
            });
            @endif
        </script>
    @endpush
@endsection
