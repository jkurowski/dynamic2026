@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title row"><i class="fe-airplay"></i>Slider</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                        <a href="{{route('admin.slider.create')}}" class="btn btn-primary">Dodaj obrazek</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table id="sortable" class="table mb-0">
                        <thead class="thead-default">
                        <tr>
                            <th>Nazwa</th>
                            <th class="text-center">Status</th>
                            <th>Miniaturka</th>
                            <th>Data modyfikacji</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($list as $item)
                            <tr id="recordsArray_{{ $item->id }}">
                                <td>{{ $item->title }}</td>
                                <td class="text-center">{!! status($item->active) !!}</td>
                                <td>@if($item->file)<img src="/uploads/slider/thumbs/{{$item->file}}" alt="{{ $item->name }}">@endif</td>
                                <td>{{ $item->updated_at }}</td>
                                <td class="option-120">
                                    <div class="btn-group">
                                        <span class="btn action-button move-button me-1"><i class="fe-move"></i></span>
                                        <a href="{{route('admin.slider.edit', $item->id)}}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Edytuj wpis"><i class="fe-edit"></i></a>
                                        <form method="POST" action="{{route('admin.slider.destroy', $item->id)}}">
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
                    <a href="{{route('admin.slider.create')}}" class="btn btn-primary">Dodaj obrazek</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-left",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif
    </script>
    <script type="text/javascript">$(document).ready(function(){$("#sortable tbody.content").sortuj('{{route('admin.slider.sort')}}');});</script>
@endpush
