@extends('admin.layout')
@section('meta_title', '- Sekcje stron')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-12 pl-0">
                        <h4 class="page-title row"><i class="fe-layout"></i>Sekcje stron</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table mb-0">
                        <thead class="thead-default">
                        <tr>
                            <th>Sekcja</th>
                            <th>Strona</th>
                            <th>Ostatnia zmiana</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($lista as $item)
                            <tr>
                                <td>{{ $item->nazwa }}</td>
                                <td>@if($item->strona)<a href="{{ url($item->strona) }}" target="_blank">{{ $item->strona }}</a>@endif</td>
                                <td>{{ $item->zmieniona ?? 'treść z szablonu' }}</td>
                                <td class="option-120">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.sekcja.edit', $item->klucz) }}" class="btn action-button" title="Edytuj sekcję"><i class="fe-edit"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-left",timeOut:"3000"};toastr.success(@json(session('success'))); @endif
    </script>
@endpush
