@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

{{-- Miasta / biura sprzedaży: boksy na stronie Kontakt, zakładki biur w sekcji kontaktu (strona główna, Finansowanie,
     Wykończenie, Poznaj nas...) i pinezki na mapie biur. Adres, godziny i telefon - mini edytor (linie jako <br>). --}}

@section('content')
    @if(Route::is('admin.city.edit'))
        <form method="POST" action="{{route('admin.city.update', $entry->id)}}" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form method="POST" action="{{route('admin.city.store')}}" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="container">
                        <div class="card-head container">
                            <div class="row">
                                <div class="col-12 pl-0">
                                    <h4 class="page-title"><i class="fe-grid"></i><a href="{{route('admin.city.index')}}" class="p-0">Miasta / biura</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            @include('form-elements.back-route-button')

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="card-body control-col12">
                                @if(!Request::get('lang'))
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-select', ['label' => 'Aktywne', 'sublabel' => 'Widoczne na stronie (Kontakt, zakładki biur, mapa)', 'name' => 'active', 'selected' => $entry->exists ? $entry->active : 1, 'select' => ['1' => 'Tak', '0' => 'Nie']])
                                </div>
                                @endif

                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Nazwa', 'sublabel' => 'Np. Warszawa - nagłówek boksu i zakładka', 'name' => 'name', 'value' => $entry->name, 'required' => 1])
                                </div>

                                @if(!Request::get('lang'))
                                <div class="row w-100 form-group">
                                    @include('form-elements.textarea-fullwidth', ['label' => 'Adres', 'sublabel' => 'Każda linia w osobnym wierszu (Enter)', 'name' => 'address', 'value' => $entry->address, 'rows' => 3, 'class' => 'tinymce-mini'])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.textarea-fullwidth', ['label' => 'Godziny otwarcia', 'sublabel' => 'Bez nagłówka „Godziny otwarcia:” - dodaje go strona', 'name' => 'working_hours', 'value' => $entry->working_hours, 'rows' => 3, 'class' => 'tinymce-mini'])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.textarea-fullwidth', ['label' => 'Telefon', 'sublabel' => 'Numery stają się klikalne (tel:) w zakładkach biur', 'name' => 'phone', 'value' => $entry->phone, 'rows' => 2, 'class' => 'tinymce-mini'])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Link do Google Maps', 'sublabel' => 'Przycisk „Wyznacz trasę”, np. https://www.google.com/maps/dir/?api=1&destination=...', 'name' => 'map_link', 'value' => $entry->map_link])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-file', [
                                        'label' => 'Kod QR',
                                        'sublabel' => '(obrazek PNG/JPG/WebP; bez obrazka - kod z szablonu)',
                                        'name' => 'file',
                                        'file' => $entry->file,
                                        'file_preview' => \App\Models\City::KATALOG_QR,
                                        'file_preview_style' => 'max-width:151px'
                                    ])
                                    @if($entry->file)
                                        <div class="col-12 mt-2"><label><input type="checkbox" name="usun_file" value="1"> Usuń kod QR (wróci kod z szablonu)</label></div>
                                    @endif
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Szerokość geograficzna (lat)', 'sublabel' => 'Pinezka na mapie biur, np. 52.1970523', 'name' => 'lat', 'value' => $entry->lat])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Długość geograficzna (lng)', 'sublabel' => 'Np. 21.0463495', 'name' => 'lng', 'value' => $entry->lng])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Kolejność', 'sublabel' => 'Mniejsza liczba = wcześniej (pierwsze biuro to domyślna zakładka)', 'name' => 'sort', 'value' => $entry->sort ?? 0])
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="lang" value="{{$current_locale}}">
                    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                </form>

                {{-- Mini edytor: bez akapitów - Enter = nowa linia (<br>), jak w szablonie --}}
                <script src="{{ asset('/js/editor/tinymce.min.js') }}" charset="utf-8"></script>
                <script>
                    tinymce.init({
                        selector: '.tinymce-mini',
                        language: 'pl',
                        skin: 'oxide',
                        branding: false,
                        menubar: false,
                        statusbar: false,
                        height: 140,
                        forced_root_block: '',
                        plugins: 'link',
                        toolbar: 'bold italic | link | removeformat',
                        relative_urls: false,
                        entity_encoding: 'raw'
                    });
                </script>
        @endsection
