@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-home"></i><a href="{{route('admin.developro.investment.index')}}">Inwestycje</a><span class="d-inline-flex me-2 ms-2">/</span>{{$investment->name}}</h4>
                </div>
            </div>
        </div>
        @include('admin.developro.investment_shared.menu')

        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table mb-0" id="sortable">
                        <thead class="thead-default">
                        <tr>
                            <th>#</th>
                            <th>Nazwa</th>
                            <th class="text-center">Kolejność</th>
                            <th class="text-center">Lokale</th>
                            <th class="text-center">Widoczność</th>
                            <th>Data modyfikacji</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($list as $index => $p)
                            <tr id="recordsArray_{{ $p->id }}">
                                <th class="position" scope="row">{{ $index+1 }}</th>
                                <td><a href="{{route('admin.developro.investment.properties.index', [$investment, $p])}}">{{ $p->name }}</a></td>
                                <td class="text-center">{{ $p->position }}</td>
                                <td class="text-center">{{ $p->properties->count() }}</td>
                                <td class="text-center">{!! status($p->active) !!}</td>
                                <td>{{ $p->updated_at }}</td>
                                <td class="option-120">
                                    <div class="btn-group">
                                        <a href="{{route('admin.developro.investment.floors.copy', [$investment, $p])}}" class="btn action-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Kopiuj piętro"><i class="fe-copy"></i></a>
                                        <a href="{{route('admin.developro.investment.properties.index', [$investment, $p])}}" class="btn action-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Pokaż piętro"><i class="fe-folder"></i></a>
                                        <a href="{{route('admin.developro.investment.floors.edit', [$investment, $p])}}" class="btn action-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Edytuj piętro"><i class="fe-edit"></i></a>
                                        <form method="POST" action="{{route('admin.developro.investment.floors.destroy', [$investment, $p])}}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn action-button confirm" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Usuń piętro" data-id="{{ $p->id }}"><i class="fe-trash-2"></i></button>
                                        </form>
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
    <div class="form-group form-group-submit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{route('admin.developro.investment.floors.create', $investment)}}" class="btn btn-primary">Dodaj piętro</a>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-menu-bottom-right",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif
        </script>
    @endpush
@endsection
