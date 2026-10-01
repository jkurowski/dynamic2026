@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-git-merge" aria-hidden="true"></i>Składniki cen mieszkań</h4>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.developro.investment_shared.main_menu')
        <div class="card mt-3">
            <div class="table-overflow">
                <table class="table mb-0" id="sortable" aria-describedby="Investment list">
                    <thead class="thead-default">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nazwa</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody class="content">
                    @foreach ($list as $index => $p)
                        <tr id="recordsArray_{{ $p->id }}">
                            <th class="position" scope="row">{{ $index+1 }}</th>
                            <td>{{ $p->name }}</td>
                            <td class="option-120">
                                <div class="btn-group">
                                    <a
                                        href="{{ route('admin.developro.property-price-components.edit', $p->id) }}"
                                        class="btn action-button me-1"
                                        data-bs-toggle="tooltip"
                                        data-placement="top"
                                        data-bs-title="Edytuj">
                                        <i class="fe-edit" aria-hidden="true"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.developro.property-price-components.destroy', $p->id) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn action-button confirm" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Usuń inwestycje" data-id="{{ $p->id }}"><i class="fe-trash-2"></i></button>
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
    <div class="form-group form-group-submit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{ route('admin.developro.property-price-components.create') }}" class="btn btn-primary">Dodaj wpis</a>
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
            @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-right",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif
        </script>
    @endpush
@endsection
