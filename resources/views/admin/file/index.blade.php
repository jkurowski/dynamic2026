@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-grid"></i>Pliki</h4>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="FileBrowserModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Przeglądaj pliki</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"><i class="fe-x"></i></button>
                                </div>
                                <div class="modal-body">
                                    <iframe
                                        id="filemanager"
                                        width="100%"
                                        height="550"
                                        style="border:0"
                                        src="/admin/file?type=modal"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                        <a href="" class="btn btn-primary me-1 d-none" data-bs-toggle="modal" data-bs-target="#FileBrowserModal">Modal</a>
                        <a href="{{route('admin.file.create')}}" class="btn btn-primary me-1">Dodaj plik</a>
                        <a href="{{route('admin.file-catalog.create')}}" class="btn btn-primary">Dodaj katalog</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="table-overflow">
                @if (session('success'))
                    <div class="alert alert-success border-0 mb-0">
                        {{ session('success') }}
                        <script>setTimeout(function(){$(".alert").slideUp(500,function(){$(this).remove()})},3000)</script>
                    </div>
                @endif
                <table id="sortable" class="table mb-0">
                    <thead class="thead-default">
                    <tr>
                        <th style="width: 25px"></th>
                        <th>Nazwa</th>
                        <th>Opis</th>
                        <th class="text-center">Rozmiar</th>
                        <th class="text-center">Pobrania</th>
                        <th>Data dodania</th>
                        <th>Data modyfikacji</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="content">
                    @foreach ($list as $item)
                        <tr id="recordsArray_{{ $item->id }}">
                            <td><i class="mime-icon {{ mime2icon($item->mime) }}"></i></td>
                            <td>
                                @if($item->type == 1)
                                    <a href="{{ route('admin.file.download', $item) }}" target="_blank">{{ $item->name }}</a>
                                @else
                                    <a href="{{route('admin.file-catalog.show', $item->id)}}"><b>{{ $item->name }}</b></a>
                                @endif
                            </td>
                            <td>{{ $item->description }}</td>
                            <td class="text-center">
                                @if($item->type == 1)
                                    {{ parseFilesize($item->size) }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->type == 1)
                                    {{ $item->download }}
                                @endif
                            </td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->updated_at }}</td>
                            <td class="option-120">
                                <div class="btn-group">
                                    @if($item->type == 1)
                                        <a href="{{ route('admin.file.download', $item)}}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Pobierz plik" target="_blank"><i class="fe-download"></i></a>
                                        <a href="{{route('admin.file.edit', $item->id)}}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Edytuj wpis"><i class="fe-edit"></i></a>
                                    @else
                                        <a href="{{route('admin.file-catalog.show', $item->id)}}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Otwórz katalog"><i class="fe-folder"></i></a>
                                        <a href="{{route('admin.file-catalog.edit', $item->id)}}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Edytuj wpis"><i class="fe-edit"></i></a>
                                    @endif

                                    @if($item->children()->count() == 0)
                                        @if($item->id <> 14)
                                            <form method="POST" action="{{route('admin.file.destroy', $item->id)}}">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button
                                                    type="submit"
                                                    class="btn action-button confirm"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Usuń wpis"
                                                    data-id="{{ $item->id }}"
                                                ><i class="fe-trash-2"></i></button>
                                            </form>
                                        @endif
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
    <div class="form-group form-group-submit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{route('admin.file.create')}}" class="btn btn-primary me-1">Dodaj plik</a>
                    <a href="{{route('admin.file-catalog.create')}}" class="btn btn-primary">Dodaj katalog</a>
                </div>
            </div>
        </div>
    </div>
@endsection
