@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-book-open"></i>Przeglądaj wpisy</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                    <a href="{{route('admin.article.create')}}" class="btn btn-primary">Dodaj wpis</a>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table mb-0">
                        <thead class="thead-default">
                        <tr>
                            <th>Tytuł</th>
                            <th>Miniaturka</th>
                            <th>ALT zdjęcia</th>
                            <th class="text-center">Data</th>
                            <th class="text-center">Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($list as $item)
                            <tr id="recordsArray_{{ $item->id }}" class="list-category-{{$item->category_id}}">
                                <td>{{ $item->title }}</td>
                                <td>@if($item->file)<img src="{{asset('uploads/articles/thumbs/'.$item->file)}}" alt="{{ $item->title }}" style="width: 150px;border-radius: 5px;border:1px solid white">@endif</td>
                                <td>{{ $item->file_alt }}</td>
                                <td class="text-center">{{ $item->created_at }}</td>
                                <td class="text-center">{!! status($item->status) !!}</td>
                                <td class="option-120">
                                    <div class="btn-group">
                                        <a href="https://developers.facebook.com/tools/debug/?q={{route('aktualnosci.show', ['locale' => 'pl', 'aktualnosci' => $item])}}" target="_blank" class="btn action-button me-3" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Facebook sharing debugger"><i class="fe-radio"></i></a>

                                        <a href="{{route('admin.article.edit', ['article' => $item->id, 'lang' => 'en'])}}" class="btn action-button lang-button me-1" data-toggle="tooltip" data-placement="top" title="Edytuj"><img src="{{ asset('/cms/flags/en.png') }}" alt="Tłumaczenie: en"></a>

                                        <a href="{{route('admin.article.edit', $item->id)}}" class="btn action-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Edytuj wpis"><i class="fe-edit"></i></a>
                                        <form method="POST" action="{{route('admin.article.destroy', $item->id)}}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn action-button confirm" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Usuń wpis" data-id="{{ $item->id }}"><i class="fe-trash-2"></i></button>
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
                    <a href="{{route('admin.article.create')}}" class="btn btn-primary">Dodaj wpis</a>
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
