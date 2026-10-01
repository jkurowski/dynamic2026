@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-book"></i><a href="{{route('admin.developro.investment.index')}}">Inwestycje</a><span class="d-inline-flex me-2 ms-2">/</span>{{$investment->name}}<span class="d-inline-flex me-2 ms-2">-</span>Harmonogram</h4>
                </div>
            </div>
        </div>
        @include('admin.developro.investment_shared.menu')
        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">

                    <form id="calculate" class="p-5">
                        @csrf
                        <h3>Przeprowadź symulacje płatności</h3>
                        <div class="input-group mb-3 mt-3">
                            <input type="text" name="amount" class="form-control" placeholder="Wartość w zł" aria-label="Wartość w zł" aria-describedby="button-addon">
                            <input type="hidden" name="investment_id" value="{{$investment->id}}">
                            <button class="btn btn-outline-secondary" type="submit" id="button-addon">Oblicz</button>
                        </div>
                        <div id="result"></div>
                    </form>

                    <table class="table data-table mb-0 w-100">
                        <thead class="thead-default">
                        <tr>
                            <th>Termin płatności</th>
                            <th class="text-center">Wartość procentowa</th>
                            <th class="text-center">Data utworzenia</th>
                            <th class="text-center">Data edycji</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @php
                            $totalAmount = 0;
                        @endphp
                        @foreach ($list as $item)
                            <tr id="recordsArray_{{ $item->id }}">
                                <td>{{ $item->due_date }}</td>
                                <td class="text-center">
                                    {{ $item->amount }}%
                                    @php
                                        $totalAmount += $item->amount;
                                    @endphp
                                </td>
                                <td class="text-center">{{ $item->created_at }}</td>
                                <td class="text-center">{{ $item->updated_at }}</td>
                                <td class="option-120">
                                    <div class="btn-group">
                                        <a href="{{route('admin.developro.investment.payments.edit', [$investment, $item->id])}}" class="btn action-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Edytuj wpis"><i class="fe-edit"></i></a>
                                        <form method="POST" action="{{route('admin.developro.investment.payments.destroy', [$investment, $item->id])}}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn action-button confirm" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Usuń wpis" data-id="{{ $item->id }}"><i class="fe-trash-2"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td class="text-center">Łącznie: {{ $totalAmount }}%</td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="option-120"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group form-group-submit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{route('admin.developro.investment.payments.create', $investment)}}" class="btn btn-primary">Dodaj wpis</a>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#calculate').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.developro.investment.payments.calculate', $investment) }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        const resultDiv = $('#result');
                        resultDiv.empty(); // Clear previous results
                        let html = '<table class="table table-bordered">';
                        html += '<thead><tr><th scope="col">#</th><th scope="col">Wartość procentowa</th><th scope="col">Kwota</th></tr></thead>';
                        for (var i = 0; i < response.amounts.length; i++) {
                            html += '<tr><td>'+i+'</td><td>' + response.percentages[i] + '%</td><td>' + response.amounts[i] + ' zł</td></tr>';
                        }
                        html += '</table>';
                        resultDiv.append(html);
                    },
                    error: function(xhr) {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
    @endpush
@endsection