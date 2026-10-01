@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-briefcase"></i>Generator dokumentów</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                    <a href="{{ route('admin.contract.template.create') }}" class="btn btn-primary">Stwórz szablon</a>
                    <a href="{{ route('admin.contract.create') }}" class="ms-3 btn btn-primary">Dodaj szablon z pliku</a>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table mb-0">
                        <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>Nazwa</th>
                            <th>Opis</th>
                            <th>Plik templatki</th>
                            <th class="text-center">Data utworzenia</th>
                            <th class="text-center">Data edycji</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($list as $item)
                            <tr id="recordsArray_{{ $item->id }}" class="list-category-{{$item->category_id}}">
                                <td class="pe-0">
                                    @if ($item['type'] == 1)
                                        <i class="mime-icon las la-file-upload"></i>
                                    @elseif ($item['type'] == 2)
                                        <i class="mime-icon las la-file-invoice"></i>
                                    @endif
                                </td>
                                <td class="ps-2">{{ $item->name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->template }}</td>
                                <td class="text-center">{{ $item->created_at }}</td>
                                <td class="text-center">{{ $item->updated_at }}</td>
                                <td class="option-120 text-end">
                                    <div class="btn-group">
                                        @if ($item['type'] == 1)
                                            <a href="{{ route('admin.contract.edit', $item) }}" class="btn action-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Edytuj wpis"><i class="fe-edit"></i></a>
                                            <form method="POST" action="">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn action-button confirm" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Usuń wpis" data-id="{{ $item->id }}"><i class="fe-trash-2"></i></button>
                                            </form>

                                            <a href="{{ route('admin.contract.settings', $item) }}" class="btn action-button ms-3" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Ustawienia dokumentu"><i class="fe-settings"></i></a>

                                            <a href="{{ route('admin.contract.show', $item) }}" class="btn action-button ms-3" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Generuj dokument"><i class="fe-printer"></i></a>
                                        @elseif ($item['type'] == 2)
                                            <a href="{{ route('admin.contract.template.edit', $item) }}" class="btn action-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Edytuj wpis"><i class="fe-edit"></i></a>
                                            <form method="POST" action="">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn action-button confirm" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Usuń wpis" data-id="{{ $item->id }}"><i class="fe-trash-2"></i></button>
                                            </form>

                                            <a href="{{ route('admin.contract.template.settings', $item) }}" class="btn action-button ms-3" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Ustawienia dokumentu"><i class="fe-settings"></i></a>

                                            <a href="{{ route('admin.contract.template.show', $item) }}" class="btn action-button ms-3" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Generuj dokument"><i class="fe-printer"></i></a>
                                        @endif
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
                    <a href="{{ route('admin.contract.template.create') }}" class="btn btn-primary">Stwórz szablon</a>
                    <a href="{{ route('admin.contract.create') }}" class="ms-3 btn btn-primary">Dodaj szablon z pliku</a>
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
