@extends('admin.layout')
@section('content')
    @if(Route::is('admin.developro.investment.floors.edit'))
    <form method="POST" action="{{route('admin.developro.investment.floors.update', [$investment, $entry])}}" enctype="multipart/form-data" class="mappa">
        {{method_field('PUT')}}
    @else
    <form method="POST" action="{{route('admin.developro.investment.floors.store', $investment)}}" enctype="multipart/form-data" class="mappa">
    @endif
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-head container">
                <div class="row">
                    <div class="col-12 pl-0">
                        <h4 class="page-title"><i class="fe-home"></i><a href="{{route('admin.developro.investment.index')}}">Inwestycje</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{route('admin.developro.investment.floors.index', $investment)}}">{{$investment->name}}</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
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
                    @include('form-elements.html-select', ['label' => 'Widoczne', 'name' => 'active', 'selected' => $entry->active, 'select' => [
                        '1' => 'Tak',
                        '0' => 'Nie'
                        ]
                    ])
                </div>
                <div class="row w-100 form-group">
                    @include('form-elements.html-select', [
                        'label' => 'Typ piętra',
                        'name' => 'type',
                        'selected' => $entry->type,
                        'select' => [
                            '1' => 'Piętro mieszkalne',
                            '2' => 'Piętro usługowe',
                            '3' => 'Parking naziemny',
                            '4' => 'Parking podziemny'
                    ]])
                </div>
                <div class="row w-100 form-group">
                    @include('form-elements.input-text', ['label' => 'Nazwa piętra', 'name' => 'name', 'value' => $entry->name, 'required' => 1])
                </div>
                <div class="row w-100 form-group">
                    @include('form-elements.html-input-text-count', ['label' => 'Nagłówek strony', 'sublabel'=> 'Meta tag - title', 'name' => 'meta_title', 'value' => $entry->meta_title, 'maxlength' => 60])
                </div>
                <div class="row w-100 form-group">
                    @include('form-elements.html-input-text-count', ['label' => 'Opis strony', 'sublabel'=> 'Meta tag - description', 'name' => 'meta_description', 'value' => $entry->meta_description, 'maxlength' => 158])
                </div>
                <div class="row w-100 form-group">
                    @include('form-elements.input-text', ['label' => 'Numer piętra', 'name' => 'number', 'value' => $entry->number, 'required' => 1])
                </div>
                <div class="row w-100 form-group">
                    @include('form-elements.input-text', ['label' => 'Kolejność', 'name' => 'position', 'value' => $entry->position, 'required' => 1])
                </div>
                <div class="row w-100 form-group">
                    @include('form-elements.input-text', ['label' => 'Zakres pow. w wyszukiwarce xx-xx', 'sublabel' => '(zakresy oddzielone przecinkiem)', 'name' => 'area_range', 'value' => $entry->area_range])
                </div>
                <div class="row w-100 form-group">
                    @include('form-elements.input-text', ['label' => 'Zakres cen w wyszukiwarce xx-xx', 'sublabel' => '(zakresy oddzielone przecinkiem)', 'name' => 'price_range', 'value' => $entry->price_range])
                </div>
                <div class="row w-100 form-group">
                    @include('form-elements.html-input-file', [
                        'label' => 'Plan piętra',
                        'sublabel' => '(wymiary: '.config('images.floor.plan_width').'px / '.config('images.floor.plan_height').'px)',
                        'name' => 'file',
                        'file' => $entry->file_webp,
                        'file_preview' => config('images.floor.preview_file_path')
                    ])
                </div>
            </div>
        </div>
        <input type="hidden" name="investment_id" value="{{$investment->id}}">
        @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz piętro'])
    </div>
</form>
@endsection
@push('scripts')
<script src="{{ asset('/js/plan/underscore.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/plan/mappa-backbone.js') }}" charset="utf-8"></script>
<script type="text/javascript">
    const map = {
        "name":"imagemap",
        "areas":[{!! $entry->cords !!}]
    };
    $(document).ready(function() {
        const mapview = new MapView({el: '.mappa'}, map);
        @if($investment->plan)
        mapview.loadImage('/investment/plan/{{$investment->plan->file}}');
        @endif
    });
</script>
@endpush
