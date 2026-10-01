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

        <div class="card-header card-nav">
            <nav class="nav">
                <div class="container-fluid">
                    <form class="row">
                        <div class="col">
                            <label for="form_name" class="form-label">Nazwa</label>
                            <input type="text" class="form-control" id="form_name" name="name" value="{{ request()->get('name') }}">
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col-6">
                                    <label for="form_area_from" class="form-label">Pow. od</label>
                                    <input type="text" class="form-control" id="form_area_from" name="area_from" value="{{ request()->get('area_from') }}">
                                </div>
                                <div class="col-6">
                                    <label for="form_area_to" class="form-label">Pow. do</label>
                                    <input type="text" class="form-control" id="form_area_to" name="area_to" value="{{ request()->get('area_to') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <label for="form_rooms" class="form-label">Pokoje</label>
                            <select class="form-select" id="form_rooms" name="rooms">
                                <option value="">Wybierz ilość / wszystkie</option>
                                @foreach($uniqueRooms as $room)
                                    <option value="{{ $room }}" @if(request()->input('rooms') == $room) selected @endif>{{ $room }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label for="form_status" class="form-label">Status</label>
                            <select class="form-select" id="form_status" name="status">
                                <option value="">Wybierz status / wszystkie</option>
                                <option value="1" @if(request()->get('status') == 1) selected @endif>Na sprzedaż</option>
                                <option value="2" @if(request()->get('status') == 2) selected @endif>Rezerwacja</option>
                                <option value="3" @if(request()->get('status') == 3) selected @endif>Sprzedane</option>
                                <option value="4" @if(request()->get('status') == 4) selected @endif>Wynajęte</option>
                            </select>
                        </div>
                        <div class="col d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100" id="form_button">Szukaj</button>
                        </div>
                    </form>
                </div>
            </nav>
        </div>

        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table mb-0" id="sortable">
                        <thead class="thead-default">
                        <tr>
                            <th>#</th>
                            <th>Nazwa</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Pokoje</th>
                            <th class="text-center">Metraż</th>
                            <th class="text-center">Wizyty</th>
                            <th class="text-center">Widoczność</th>
                            <th>Data modyfikacji</th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($list as $index => $p)
                            <tr id="recordsArray_{{ $p->id }}">
                                <th class="position" scope="row">{{ $index+1 }}</th>
                                <td>{{ $p->name }}</td>
                                <td><span class="badge room-list-status-{{ $p->status }}">{{ roomStatus($p->status) }}</span></td>
                                <td class="text-center">{{ $p->rooms }}</td>
                                <td class="text-center">{{ $p->area }} m<sup>2</sup></td>
                                <td class="text-center">{{ $p->views }}</td>
                                <td class="text-center">{!! status($p->active) !!}</td>
                                <td>{{ $p->updated_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>

        </script>
    @endpush
@endsection
