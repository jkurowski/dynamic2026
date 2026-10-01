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
                            <th>Nazwa</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Pokoje</th>
                            <th class="text-center">Metraż</th>
                            <th class="text-center">Cena brutto</th>
                            <th class="text-center">Dodatkowe</th>
                            <th class="text-center">Wizyty</th>
                            <th class="text-center">Klient</th>
                            <th class="text-center">Widoczność</th>
                            <th>Data modyfikacji</th>
                            <th>Typ</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($list as $index => $p)
                            <tr id="recordsArray_{{ $p->id }}">
                                <td>
                                    <p>{{ $p->name }}</p>
                                    <span class="small-text">{{ $p->getLocation() }}</span>
                                </td>
                                <td><span class="badge room-list-status-{{ $p->status }}">{{ roomStatus($p->status) }}</span></td>
                                <td class="text-center">{{ $p->rooms }}</td>
                                <td class="text-center">{{ $p->area }} m<sup>2</sup></td>
                                <td class="text-center">
                                    @if($p->price_brutto)
                                        <p>@money($p->price_brutto)</p>
                                    @endif
                                    @if($p->price)
                                        <span class="small-text">(netto: @money($p->price))</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($p->garden_area)
                                        <span class="d-block mb-2">Ogród: {{ $p->garden_area }} m<sup>2</sup></span>
                                    @endif
                                    @if($p->balcony_area)
                                        <span class="d-block mb-2">Balkon: {{ $p->balcony_area }} m<sup>2</sup></span>
                                    @endif
                                    @if($p->terrace_area)
                                        <span class="d-block mb-2">Taras: {{ $p->terrace_area }} m<sup>2</sup></span>
                                    @endif
                                    @if($p->loggia_area)
                                        <span class="d-block mb-2">Logia: {{ $p->loggia_area }} m<sup>2</sup></span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $p->views }}</td>
                                <td class="text-center">
                                    @if($p->client_id != null)
                                        <a href="{{ route('admin.crm.clients.show', $p->client->id) }}">{{ $p->client->name }} {{ $p->client->lastname }}</a>
                                    @endif
                                </td>
                                <td class="text-center">{!! status($p->active) !!}</td>
                                <td>{{ $p->updated_at }}</td>
                                <td>{{ $p->type }}</td>
                                <td><a href="{{ $p->property_url }}" class="btn action-button me-1"
                                       data-bs-toggle="tooltip"
                                       data-placement="top"
                                       data-bs-title="Edytuj lokal">
                                        <i class="fe-edit"></i>
                                    </a>
                                </td>
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
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-left",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif
        </script>
    @endpush
@endsection
