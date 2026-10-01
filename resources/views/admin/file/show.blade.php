@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-grid"></i><a href="{{ route('admin.file.index') }}">Pliki</a>
                            <span class="d-inline-flex me-2 ms-2">/</span>
                            @foreach($entry->ancestors as $item)
                                <a href="{{ route('admin.file-catalog.show', $item['id']) }}">{{ $item['name'] }}</a>
                                <span class="d-inline-flex me-2 ms-2">/</span>
                            @endforeach
                            {{ $entry->name }}
                        </h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                        <a href="{{ route('admin.file.create.file-file', $entry) }}" class="btn btn-primary me-1">Dodaj plik</a>
                        <a href="{{ route('admin.file.create.catalog', $entry) }}" class="btn btn-primary">Dodaj katalog</a>
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
                    @if($entry->ancestors->count() == 0)
                    <tr>
                        <td class="text-center"><i class="fe-corner-left-up"></i></td>
                        <td colspan="6">
                            <a href="{{ route('admin.file.index') }}" title="Pliki">[ ... ]</a>
                        </td>
                    </tr>
                    @else
                        <tr>
                            <td class="text-center"><i class="fe-corner-left-up"></i></td>
                            <td colspan="6">
                                <a href="{{ route('admin.file-catalog.show', $entry->ancestors->first()->id) }}" title="{{ $entry->ancestors->first()->name }}">[ ... ]</a>
                            </td>
                        </tr>
                    @endif
                    @foreach ($list as $item)
                        <tr id="recordsArray_{{ $item->id }}">
                            <td>
                                <i class="mime-icon {{ mime2icon($item->mime) }}"></i>
                            </td>
                            <td>
                                @if($item->type == 0)
                                    <a href="{{ route('admin.file.download', $item) }}" target="_blank">{{ $item->name }}</a>
                                @else
                                    <a href="{{ route('admin.file-catalog.show', $item->id) }}"><b>{{ $item->name }}</b></a>
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
                                    @if($item->type == 0)
                                        <a href="{{ route('admin.file.download', $item) }}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Pobierz plik" target="_blank"><i class="fe-download"></i></a>
                                        <a href="{{route('admin.file.edit', $item->id) }}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Edytuj wpis"><i class="fe-edit"></i></a>
                                    @else
                                        <a href="{{route('admin.file-catalog.show', $item->id) }}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Otwórz katalog"><i class="fe-folder"></i></a>
                                        <a href="{{route('admin.file-catalog.edit', $item->id) }}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Edytuj wpis"><i class="fe-edit"></i></a>
                                    @endif
                                    @if($item->children()->count() == 0)
                                    <form method="POST" action="{{ route('admin.file.destroy', $item->id) }}">
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
                    <a href="{{ route('admin.file.create.file-file', $entry) }}" class="btn btn-primary me-1">Dodaj plik</a>
                    <a href="{{ route('admin.file.create.catalog', $entry) }}" class="btn btn-primary">Dodaj katalog</a>
                </div>
            </div>
        </div>
    </div>
@endsection
