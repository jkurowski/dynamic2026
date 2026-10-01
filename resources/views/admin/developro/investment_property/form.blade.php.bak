@extends('admin.layout')
@section('content')
    @if(Route::is('admin.developro.investment.properties.edit'))
        <form method="POST" action="{{route('admin.developro.investment.properties.update', [$investment, $floor, $entry])}}" enctype="multipart/form-data" class="mappa">
            {{method_field('PUT')}}
            @else
                <form method="POST" action="{{route('admin.developro.investment.properties.store', [$investment, $floor])}}" enctype="multipart/form-data" class="mappa">
                    @endif
                    @csrf
                    <div class="container">
                        <div class="card">
                            <div class="card-head container">
                                <div class="row">
                                    <div class="col-12 pl-0">
                                        <h4 class="page-title"><i class="fe-home"></i><a href="{{route('admin.developro.investment.index')}}">Inwestycje</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{route('admin.developro.investment.floors.index', $investment)}}">{{$investment->name}}</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{route('admin.developro.investment.properties.index', [$investment, $floor])}}">{{ $floor->name }}</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3">
                            @include('form-elements.back-route-button')
                            <div class="card-body">
                                <div class="mappa-tool">
                                    <div class="mappa-workspace">
                                        <div id="overflow" style="overflow:auto;width:100%;">
                                            <canvas class="mappa-canvas"></canvas>
                                        </div>
                                        <div class="mappa-toolbars">
                                            <ul class="mappa-drawers list-unstyled mb-0">
                                                <li><input type="radio" name="tool" value="polygon" id="new" class="addPoint input_hidden"/><label for="new" data-toggle="tooltip" data-placement="top" class="actionBtn tip addPoint" title="Służy do dodawanie nowego elementu"><i class="fe-edit-2"></i> Dodaj punkt</label></li>
                                            </ul>
                                            <ul class="mappa-points list-unstyled mb-0">
                                                <li><input checked="checked" type="radio" name="tool" id="move" value="arrow" class="movePoint input_hidden"/><label for="move" class="actionBtn tip movePoint" data-toggle="tooltip" data-placement="top" title="Służy do przesuwania punktów"><i class="fe-move"></i> Przesuń / Zaznacz</label></li>
                                                <li><input type="radio" name="tool" value="delete" id="delete" class="deletePoint input_hidden"/><label for="delete" class="actionBtn tip deletePoint" data-toggle="tooltip" data-placement="top" title="Służy do usuwana punków"><i class="fe-trash-2"></i> Usuń punkt</label></li>
                                            </ul>
                                            <ul class="mappa-list list-unstyled mb-0"></ul>
                                            <ul class="mappa-points list-unstyled mb-0">
                                                <li><a href="#" id="toggleparam" class="actionBtn tip toggleParam" data-toggle="tooltip" data-placement="top" title="Służy do pokazywania/ukrywania parametrów"><i class="fe-repeat"></i> Pokaż / ukryj parametry</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body control-col12">
                                <div class="toggleRow w-100">
                                    <div class="row w-100 form-group">
                                        @include('form-elements.mappa', ['label' => 'Współrzędne punktów', 'name' => 'cords', 'value' => $entry->cords, 'rows' => 10, 'class' => 'mappa-html'])
                                    </div>
                                    <div class="row w-100 form-group mb-5">
                                        @include('form-elements.mappa', ['label' => 'Współrzędne punktów HTML', 'name' => 'html', 'value' => $entry->html, 'rows' => 10, 'class' => 'mappa-area'])
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12">
                                                <!-- Display all errors at the top -->
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-4 mb-4">
                                                @include('form-elements.html-select', ['label' => 'Widoczne', 'name' => 'active', 'selected' => $entry->active, 'select' => [
                                                       '1' => 'Tak',
                                                       '0' => 'Nie'
                                                       ]
                                                   ])
                                            </div>
                                            <div class="col-4 mb-4">
                                                @include('form-elements.html-select', ['label' => 'Typ powierzchni', 'name' => 'type', 'selected' => $entry->type, 'select' => [
                                                    '1' => 'Mieszkanie / Apartament',
                                                    '2' => 'Lokal usługowy',
                                                    '3' => 'Miejsce parkingowe naziemne',
                                                    '4' => 'Miejsce parkingowe podziemne'
                                                    ]
                                                ])
                                            </div>
                                            <div class="col-4 mb-3">
                                                @include('form-elements.html-select', [
                                                    'label' => 'Status',
                                                    'name' => 'status',
                                                    'selected' => $entry->status,
                                                    'select' => [
                                                        '1' => 'Na sprzedaż',
                                                        '2' => 'Rezerwacja',
                                                        '3' => 'Sprzedane',
                                                        '4' => 'Wynajęte'
                                                ]])
                                            </div>
                                            <div class="col-4 d-none" id="formSaleInput">
                                                @include('form-elements.html-input-date', ['label' => 'Data sprzedaży', 'sublabel'=> '', 'name' => 'saled_at', 'value' => $entry->saled_at ? \Illuminate\Support\Carbon::parse($entry->saled_at)->format('Y-m-d') : ''])
                                            </div>
                                            <div class="col-4 d-none" id="formReservationInput">
                                                @include('form-elements.html-input-date', ['label' => 'Data zakończenia rezerwacji', 'sublabel'=> '', 'name' => 'reservation_until', 'value' => $entry->reservation_until ? \Illuminate\Support\Carbon::parse($entry->reservation_until)->format('Y-m-d') : ''])
                                            </div>
                                            <div class="col-12" id="statusAlertPlaceholder"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="inputClient" class="col-3 col-form-label control-label required text-end">Klient</label>
                                                <div class="col-9 modal-ahead">
                                                    <input type="text"
                                                           class="validate[required] form-control @error('client') is-invalid @enderror"
                                                           id="inputClient"
                                                           name="client"
                                                           autocomplete="off">
                                                    <input type="hidden" name="client_id" value="{{ $entry->client_id }}" id="inputClientId">
                                                    <div id="selectedClientItem">

                                                        @if($entry->client_id != 0)
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h4>{{ $entry->client->name }} <button type="button" id="btn-confirm" class="btn btn-primary btn-sm action-button"><i class="las la-trash-alt"></i></button></h4>
                                                                    @if($entry->client->mail)<span>E: {{$entry->client->mail}}</span>@endif
                                                                    @if($entry->client->phone)<span>T: {{$entry->client->phone}}</span>@endif
                                                                </div>
                                                            </div>
                                                        @endif

                                                    </div>
                                                    @if($errors->first('client'))
                                                        <div class="invalid-feedback d-block">{{ $errors->first('client') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12">
                                                <h2>Przynależne powierzchnie</h2>
                                                <table class="table">
                                                    <tbody id="added">
                                                    @foreach($related as $r)
                                                        <tr>
                                                            <td class="pe-0 text-center">
                                                                <input type="checkbox" class="checkbox" name="property[]" id="{{ $r->id }}" value="{{ $r->id }}" style="display: none;">
                                                                <span id="1"><i class="las la-trash-alt"></i></span>
                                                            </td>
                                                            <td><a href="" target="_blank"><b>{{ $r->name }}</b></a></td>
                                                            <td class="text-center">Pow.: <b>{{ $r->area }}</b></td>
                                                            <td class="text-center">
                                                                @if($r->price)
                                                                    Cena: <b>@money($r->price)</b>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge room-list-status-{{ $r->status }}">{{ roomStatus($r->status) }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                <form id="related">
                                                    <div class="input-group mb-3">
                                                        <select class="form-select select-related selecpicker-noborder p-0" name="" id="related_property_id" aria-describedby="button-addon" data-live-search="true" data-size="5">
                                                            <option value="">Wybierz</option>
                                                            @foreach($others as $id => $name)
                                                                <option value="{{ $id }}">{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button class="btn btn-outline-secondary" type="button" id="button-addon">Dodaj</button>
                                                    </div>
                                                </form>
                                                <div id="liveAlertPlaceholder"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-6 mb-4">
                                                @include('form-elements.input-text', ['label' => 'Nazwa', 'sublabel'=> 'Pełna nazwa', 'name' => 'name', 'value' => $entry->name, 'required' => 1])
                                            </div>

                                            <div class="col-6 mb-4">
                                                @include('form-elements.input-text', ['label' => 'Nazwa na liście', 'sublabel'=> 'Mieszkanie, Apartament itp', 'name' => 'name_list', 'value' => $entry->name_list, 'required' => 1])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-6">
                                                @include('form-elements.input-text', ['label' => 'Numer', 'sublabel'=> 'Tylko numer, bez nazwy', 'name' => 'number', 'value' => $entry->number, 'required' => 1])
                                            </div>
                                            <div class="col-6">
                                                @include('form-elements.input-text', ['label' => 'Kolejność na liście', 'sublabel'=> 'Tylko liczby', 'name' => 'number_order', 'value' => $entry->number_order, 'required' => 1])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-4">
                                                @include('form-elements.html-select', ['label' => 'Pokoje', 'name' => 'rooms', 'selected' => $entry->rooms, 'select' => [
                                                  '1' => '1',
                                                  '2' => '2',
                                                  '3' => '3',
                                                  '4' => '4',
                                                  '5' => '5',
                                                  '6' => '6'
                                                  ]
                                              ])
                                            </div>
                                            <div class="col-4">
                                                @include('form-elements.input-text', ['label' => 'Powierzchnia', 'name' => 'area', 'value' => $entry->area, 'required' => 1])
                                            </div>
                                            <div class="col-4">
                                                @include('form-elements.input-text', ['label' => 'Powierzchnia (szukana)', 'name' => 'area_search', 'value' => $entry->area_search, 'required' => 1])
                                            </div>
                                            <div class="col-4">
                                                @include('form-elements.html-select-multiple', ['label' => 'Wystawa okienna', 'name' => 'window', 'selected' => multiselect($entry->window), 'select' => [
                                                    '1' => 'Północ',
                                                    '2' => 'Południe',
                                                    '3' => 'Wschód',
                                                    '4' => 'Zachód',
                                                    '5' => 'Północny wschód',
                                                    '6' => 'Północny zachód',
                                                    '7' => 'Południowy wschód',
                                                    '8' => 'Południowy zachód'
                                                    ]
                                                ])
                                            </div>
                                            <div class="col-4">
                                                @include('form-elements.html-select', ['label' => 'Rochaj kuchni', 'name' => 'kitchen', 'selected' => $entry->kitchen, 'select' => [
                                                  '0' => 'Brak',
                                                  '1' => 'Kuchnia',
                                                  '2' => 'Aneks kuchenny'
                                                  ]
                                              ])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-4">
                                                @include('form-elements.html-select', ['label' => 'Promocja', 'name' => 'highlighted', 'selected' => $entry->highlighted, 'select' => [
                                                  '1' => 'Tak',
                                                  '0' => 'Nie'
                                                  ]
                                                ])
                                            </div>
                                            <div class="col-4">
                                                @include('form-elements.html-input-date', ['label' => 'Data zakończenia promocji', 'sublabel'=> '', 'name' => 'promotion_end_date', 'value' => $entry->promotion_end_date])
                                            </div>
                                            <div class="col-4">
                                                @include('form-elements.html-input-text', ['label' => 'Cena promocyjna', 'sublabel'=> '', 'name' => 'promotion_price', 'value' => $entry->promotion_price])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-4">
                                                @include('form-elements.input-text', ['label' => 'Cena', 'sublabel'=> 'Tylko liczby', 'name' => 'price', 'value' => $entry->price])
                                            </div>
                                            <div class="col-4">
                                                @include('form-elements.input-text', ['label' => 'Cena (szukana)', 'sublabel'=> 'Tylko liczby', 'name' => 'price_search', 'value' => $entry->price_search])
                                            </div>
                                            <div class="col-4">
                                                @include('form-elements.input-text', ['label' => 'Cena (najniższa z 30 dni)', 'sublabel'=> 'Tylko liczby', 'name' => 'price_30', 'value' => $entry->price_30])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Wielkość działki', 'sublabel'=> 'Pow. w m<sup>2</sup>, tylko liczby', 'name' => 'plot_area', 'value' => $entry->plot_area])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Ogródek', 'sublabel'=> 'Pow. w m<sup>2</sup>, tylko liczby', 'name' => 'garden_area', 'value' => $entry->garden_area])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Balkon', 'sublabel'=> 'Pow. w m<sup>2</sup>, tylko liczby', 'name' => 'balcony_area', 'value' => $entry->balcony_area])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Balkon 2', 'sublabel'=> 'Pow. w m<sup>2</sup>, tylko liczby', 'name' => 'balcony_area_2', 'value' => $entry->balcony_area_2])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Taras', 'sublabel'=> 'Pow. w m<sup>2</sup>, tylko liczby', 'name' => 'terrace_area', 'value' => $entry->terrace_area])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.input-text', ['label' => 'Loggia', 'sublabel'=> 'Pow. w m<sup>2</sup>, tylko liczby', 'name' => 'loggia_area', 'value' => $entry->loggia_area])
                                </div>
                                <div class="row w-100 form-group">
                                    <h2 class="mb-3">Ustawienia SEO</h2>
                                    @include('form-elements.html-input-text-count', ['label' => 'Nagłówek strony', 'sublabel'=> 'Meta tag - title', 'name' => 'meta_title', 'value' => $entry->meta_title, 'maxlength' => 60])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text-count', ['label' => 'Opis strony', 'sublabel'=> 'Meta tag - description', 'name' => 'meta_description', 'value' => $entry->meta_description, 'maxlength' => 158])
                                </div>
                                <div class="row w-100 form-group">
                                    <h2 class="mb-3">Plany</h2>
                                    @include('form-elements.html-input-file', [
                                        'label' => 'Plan mieszkania',
                                        'sublabel' => '(wymiary: '.config('images.property_plan.width').'px / '.config('images.property_plan.height').'px)',
                                        'name' => 'file',
                                        'file' => $entry->file,
                                        'file_preview' => config('images.property.preview_file_path')
                                    ])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-file-pdf', [
                                        'label' => 'Plan .pdf',
                                        'sublabel' =>
                                        'Plan do pobrania',
                                        'name' => 'file_pdf',
                                        'file' => $entry->file_pdf,
                                        'file_preview' => config('images.property.preview_pdf_path')
                                    ])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.textarea', [
                                        'label' => 'Makieta 3D',
                                        'name' => 'model_3d',
                                        'value' => $entry->model_3d,
                                        'rows' => 5,
                                    ])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.textarea', [
                                        'label' => 'Wirtualny spacer',
                                        'name' => 'walk_3d',
                                        'value' => $entry->walk_3d,
                                        'rows' => 5,
                                    ])
                                </div>
                                <div class="row w-100 form-group">
                                    <h2 class="mb-3">Opis</h2>
                                    @include('form-elements.textarea-fullwidth', ['label' => 'Opis mieszkania', 'name' => 'text', 'value' => $entry->text, 'rows' => 21, 'class' => 'tinymce'])
                                </div>
                            </div>
                        </div>
                        @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                    </div>
                </form>
                @include('form-elements.tintmce')
                @endsection
@push('scripts')
<script src="{{ asset('/js/typeahead.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/plan/underscore.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/plan/mappa-backbone.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
<link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
<link href="{{ asset('/js/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
<script src="{{ asset('/js/bootstrap-select/bootstrap-select.min.js') }}" charset="utf-8"></script>
<script type="text/javascript">
    const map = {
        "name":"imagemap",
        "areas":[{!! $entry->cords !!}]
    };
    const added = document.getElementById('added');

    $(document).ready(function() {
        const mapview = new MapView({el: '.mappa'}, map);
        @if($floor->file)
        mapview.loadImage('/investment/floor/{{$floor->file}}');
        @endif
    });
    function roundAreaValue() {
        const areaInput = document.getElementById('form_area');
        const areaSearchInput = document.getElementById('form_area_search');
        const areaValue = parseFloat(areaInput.value);
        if (!isNaN(areaValue)) {
            areaSearchInput.value = Math.round(areaValue);
        }
    }
    document.getElementById('form_area').addEventListener('input', roundAreaValue);
    window.addEventListener('load', roundAreaValue);

    const users = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.nonword(['name', 'mail', 'phone']),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: {
                url: "/admin/rodo/clients"
            }
        }),
        inputClientId = $('#inputClientId'),
        inputClient = $('#inputClient'),
        clientItem = document.querySelector('#selectedClientItem');

    users.clearPrefetchCache();
    users.initialize();
    inputClient.typeahead({
            hint: true,
            highlight: true,
            minLength: 3
        },
        {
            name: 'users',
            templates: {
                suggestion: function (data) {
                    return '<div class="item">' +
                        '<div class="row">' +
                        '<div class="col-12"><h4>'+ data.name +'</h4></div>' +
                        '<div class="col-12">' + (data.mail ? '<span>E: ' + data.mail + '</span>' : '') + '</div>' +
                        '<div class="col-12">' + (data.phone ? '<span>T: ' + data.phone + '</span>' : '') + '</div>' +
                        '</div>' +
                        '</div>';
                }
            },
            display: 'value',
            source: users
        });
    inputClient.on('typeahead:select', function (ev, suggestion) {
        console.log('Selected suggestion:', suggestion);
        console.log('Before setting inputClient value:', inputClient.val());

        inputClientId.val(suggestion.id);
        inputClient.val(suggestion.name);

        inputClient.typeahead('val', suggestion.name)

        console.log('After setting inputClient value:', inputClient.val());

        clientItem.innerHTML = '<div class="row"><div class="col-12">' +
            '<h4>' + suggestion.name + '<button type="button" id="btn-confirm" class="btn btn-primary btn-sm action-button"><i class="las la-trash-alt"></i></button></h4>' +
            (suggestion.mail ? '<span>E: ' + suggestion.mail + '</span>\n' : '') +
            (suggestion.phone ? '<span>T: ' + suggestion.phone + '</span>\n' : '') +
            '</div></div>';

        $("#inputInvestment").focus();
    });

    clientItem.addEventListener('click', function(event) {
        if (event.target && (event.target.id === 'btn-confirm' || event.target.closest('#btn-confirm'))) {
            clientItem.innerHTML = '';
            inputClientId.val(0);
            inputClient.typeahead('val', '')
        }
    });

    document.getElementById('inputClient').addEventListener('input', () => {
        inputClientId.val(0);
    })

    const appendStatusAlert = (message, type, duration = 7000) => {
        const wrapper = document.createElement('div')
        wrapper.innerHTML = [
            `<div class="alert alert-${type} alert-dismissible" role="alert">`,
            `   <div>${message}</div>`,
            '</div>'
        ].join('')

        statusAlertPlaceholder.append(wrapper);

        if(duration > 0){
            setTimeout(() => {
                wrapper.remove();
            }, duration);
        }
    }
    function clearTextInputs(divElementId) {
        const textInputs = divElementId.getElementsByTagName('input');
        for (let i = 0; i < textInputs.length; i++) {
            if (textInputs[i].type === 'text') {
                textInputs[i].value = '';
            }
        }
    }
    $(document).ready(function() {
        $('.select-related').selectpicker();

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            language: "pl"
        });

        const statusSelect = document.getElementById('statusSelect');
        const formSaleInput = document.getElementById('formSaleInput');
        const formReservationInput = document.getElementById('formReservationInput');
        const addedTbody = document.getElementById('added');
        const statusAlertPlaceholder = document.getElementById('statusAlertPlaceholder');
        statusAlertPlaceholder.innerHTML = '';

        function toggleDivs() {
            const selectedValue = statusSelect.value;
            statusAlertPlaceholder.innerHTML = '';
            console.log(statusSelect.value);

            formSaleInput.classList.add('d-none');
            formSaleInput.classList.remove('d-block');
            formReservationInput.classList.add('d-none');
            formReservationInput.classList.remove('d-block');

            if (selectedValue === '3') {
                formSaleInput.classList.remove('d-none');
                formSaleInput.classList.add('d-block');

                // Check if tbody is empty
                if (addedTbody && addedTbody.children.length === 0) {
                    // console.log('The tbody#added is empty');
                    // Additional logic when tbody is empty
                } else {
                    appendStatusAlert('Do tego mieszkania są przypisane inne powierzchnie', 'warning', 0);
                }

            } else if (selectedValue === '2') {
                formReservationInput.classList.remove('d-none');
                formReservationInput.classList.add('d-block');

                // Check if tbody is empty
                if (addedTbody && addedTbody.children.length === 0) {
                    // console.log('The tbody#added is empty');
                    // Additional logic when tbody is empty
                } else {
                    appendStatusAlert('Do tego mieszkania są przypisane inne powierzchnie', 'warning', 0);
                }

            }
        }

        // Initial call to set the correct div visibility on page load
        toggleDivs();

        // Event listener for dropdown change
        statusSelect.addEventListener('change', toggleDivs);

        function clearDateInputs() {
            clearTextInputs(formSaleInput);
            clearTextInputs(formReservationInput);
        }

        statusSelect.addEventListener('change', clearDateInputs);

        attachSpanFunctionality();

        $('#button-addon').click(function(e) {
            e.preventDefault();

            const relatedPropertyId = $('#related_property_id').val();

            if (!relatedPropertyId) {
                alert('Please select a property to add.');
                return;
            }

            const data = {
                property: {{ $entry->id  }},
                related_property_id: relatedPropertyId,
                _token: '{{ csrf_token() }}'  // Include CSRF token if needed
            };

            $.ajax({
                url: '{{ route('admin.developro.investment.related.store', ['investment' => $investment, 'floor' => $floor, 'property' => $entry->id]) }}',
                method: 'POST',
                data: data,
                success: function(response) {
                    $('#added').append(response);
                    attachCheckboxFunctionality();
                    attachSpanFunctionality();

                    const lastTypeInputValue = $('#added input[name="type"]:last').val();

                    if (lastTypeInputValue === '1') {
                        appendAlert('Mieszkanie zostało przypisane poprawnie', 'success');
                    } else if (lastTypeInputValue === '2') {
                        appendAlert('Komórka lokatorska została przypisana poprawnie', 'success');
                    } else if (lastTypeInputValue === '3') {
                        appendAlert('Miejsce parkingowe zostało przypisane poprawnie', 'success');
                    } else {
                        appendAlert('Wybrana powierzchnia została przypisana poprawnie', 'success');
                    }
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON.error;

                    appendAlert(errorMessage, 'danger');
                }
            });
        });

        const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
        const appendAlert = (message, type, duration = 7000) => {
            const wrapper = document.createElement('div')
            wrapper.innerHTML = [
                `<div class="alert alert-${type} alert-dismissible" role="alert">`,
                `   <div>${message}</div>`,
                '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                '</div>'
            ].join('')

            alertPlaceholder.append(wrapper);

            setTimeout(() => {
                wrapper.remove();
            }, duration);
        }

        function attachSpanFunctionality(){
            const spans = added.querySelectorAll("span");
            spans.forEach(function(span) {
                span.addEventListener("click", function() {
                    const closestTr = this.closest("tr");
                    closestTr.remove();
                });
            });
        }

        function attachCheckboxFunctionality() {
            const checkboxes = document.querySelectorAll(".checkbox");
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {

                    console.log("change");

                    if (this.checked) {
                        const clickedCheckboxId = this.id;

                        $.ajax({
                            url: '',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                alert('Usunięto');
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                            }
                        });
                    } else {
                        // const clonedTr = document.querySelector("#selectedOffer tr#" + this.id);
                        // if (clonedTr) {
                        //     clonedTr.remove();
                        // }
                    }
                    //parentTr.remove();
                });
            });
        }
    });
</script>
@endpush
