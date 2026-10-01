@extends('admin.layout')
@section('content')
    @if(Route::is('admin.developro.investment.others.edit'))
        <form method="POST" action="{{route('admin.developro.investment.others.update', [$investment, $floor, $entry])}}" enctype="multipart/form-data" class="mappa">
            {{method_field('PUT')}}
            @else
                <form method="POST" action="{{route('admin.developro.investment.others.store', [$investment, $floor])}}" enctype="multipart/form-data" class="mappa">
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
                            @if($floor->file)
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
                            @endif
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
                                                @include('form-elements.html-select', ['label' => 'Typ powierzchni', 'name' => 'type', 'selected' => $entry->type, 'select' => \App\Helpers\PropertyAreaTypes::getOthers()])
                                            </div>
                                            <div class="col-4 mb-3">
                                                @include('form-elements.html-select', [
                                                    'label' => 'Status',
                                                    'name' => 'status',
                                                    'selected' => $entry->status,
                                                    'select' => \App\Helpers\RoomStatusMaper::getAll()
                                                ])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12">
                                                <h2>Parametry powierzchni</h2>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3">
                                                @include('form-elements.input-text', ['label' => 'Nazwa', 'sublabel'=> 'Pełna nazwa', 'name' => 'name', 'value' => $entry->name, 'required' => 1])
                                            </div>

                                            <div class="col-3">
                                                @include('form-elements.input-text', ['label' => 'Nazwa na liście', 'sublabel'=> 'Miejsce parkingowe, Komórka lokatorska itp', 'name' => 'name_list', 'value' => $entry->name_list, 'required' => 1])
                                            </div>

                                            <div class="col-3">
                                                @include('form-elements.input-text', ['label' => 'Numer', 'sublabel'=> 'Tylko numer, bez nazwy', 'name' => 'number', 'value' => $entry->number, 'required' => 1])
                                            </div>
                                            <div class="col-3">
                                                @include('form-elements.input-text', ['label' => 'Kolejność na liście', 'sublabel'=> 'Tylko liczby', 'name' => 'number_order', 'value' => $entry->number_order, 'required' => 1])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-3">
                                                @include('form-elements.input-text', ['label' => 'Powierzchnia', 'name' => 'area', 'value' => $entry->area, 'required' => 1])
                                            </div>
                                            <div class="col-3">
                                                @include('form-elements.input-text', ['label' => 'Powierzchnia (szukana)', 'name' => 'area_search', 'value' => $entry->area_search, 'required' => 1])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12">
                                                <h2>Promocje</h2>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-3">
                                                @include('form-elements.html-select', ['label' => 'Promocja', 'name' => 'highlighted', 'selected' => $entry->highlighted, 'select' => [
                                                  '1' => 'Tak',
                                                  '0' => 'Nie'
                                                  ]
                                                ])
                                            </div>
                                            <div class="col-3">
                                                @include('form-elements.html-input-date', ['label' => 'Data zakończenia promocji', 'sublabel'=> '', 'name' => 'promotion_end_date', 'value' => $entry->promotion_end_date])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="col-3">
                                        @include('form-elements.html-select', ['label' => 'Pokaż ceny przy promocji', 'name' => 'promotion_price_show', 'selected' => $entry->promotion_price_show, 'select' => [
                                          '1' => 'Tak',
                                          '0' => 'Nie'
                                          ]
                                        ])
                                    </div>
                                    <div class="col-3">
                                        @include('form-elements.html-input-text', ['label' => 'Cena promocyjna', 'sublabel'=> '', 'name' => 'promotion_price', 'value' => $entry->promotion_price])
                                    </div>
                                    <div class="col-3">
                                        @include('form-elements.html-input-text', ['label' => 'Cena 30 dni', 'sublabel'=> '', 'name' => 'price_30', 'value' => $entry->price_30])
                                    </div>
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="col-5">
                                        @include('form-elements.input-text', ['label' => 'Cena brutto', 'sublabel'=> 'Tylko liczby', 'name' => 'price_brutto', 'value' => $entry->price_brutto])
                                    </div>

                                    <div class="col-2">
                                        @include('form-elements.html-select', [
                                        'label' => 'Stawka VAT',
                                        'sublabel'=> 'Wybierz stawkę VAT',
                                        'name' => 'vat',
                                        'selected' => $entry->vat,
                                        'select' => [
                                            '8' => '8%',
                                            '23' => '23%',
                                            '0' => '0%'
                                    ]])
                                    </div>
                                    <div class="col-5">
                                        @include('form-elements.input-text', ['label' => 'Cena netto', 'sublabel'=> 'Tylko liczby', 'name' => 'price', 'value' => $entry->price])
                                    </div>
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text-count', ['label' => 'Nagłówek strony', 'sublabel'=> 'Meta tag - title', 'name' => 'meta_title', 'value' => $entry->meta_title, 'maxlength' => 60])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text-count', ['label' => 'Opis strony', 'sublabel'=> 'Meta tag - description', 'name' => 'meta_description', 'value' => $entry->meta_description, 'maxlength' => 158])
                                </div>
                                <div class="row w-100 form-group">
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
                            </div>
                        </div>
                        <input type="hidden" name="rooms" value="1">
                        <input type="hidden" name="visitor_related_type" value="0">
                        @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                    </div>
                </form>
                @endsection
@push('scripts')
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
        @if($floor->file)
        const mapview = new MapView({el: '.mappa'}, map);
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

        const priceBruttoInput = document.getElementById("form_price_brutto");
        const priceNettoInput = document.getElementById("form_price");
        const vatRateSelect = document.getElementById("vatSelect");

        function calculateNetto(brutto, vatRate) {
            const vatMultiplier = 1 + vatRate / 100;
            return brutto / vatMultiplier;
        }

        function updateNettoPrice() {
            const bruttoValue = parseFloat(priceBruttoInput.value) || 0;
            const vatRateValue = parseFloat(vatRateSelect.value) || 0;
            const nettoValue = calculateNetto(bruttoValue, vatRateValue);
            priceNettoInput.value = nettoValue.toFixed(2);
        }

        updateNettoPrice();
        priceBruttoInput.addEventListener("input", updateNettoPrice);
        vatRateSelect.addEventListener("change", updateNettoPrice);

        const statusSelect = document.getElementById('statusSelect');
        const formSaleInput = document.getElementById('formSaleInput');
        const formReservationInput = document.getElementById('formReservationInput');

        function clearDateInputs() {
            clearTextInputs(formSaleInput);
            clearTextInputs(formReservationInput);
        }

        statusSelect.addEventListener('change', clearDateInputs);
    });
</script>
@endpush
