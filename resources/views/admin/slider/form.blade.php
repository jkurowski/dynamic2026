@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    @if(Route::is('admin.slider.edit'))
        <form method="POST" action="{{route('admin.slider.update', $entry->id)}}" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form method="POST" action="{{route('admin.slider.store')}}" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="container">

                        <div class="card-head container">
                            <div class="row">
                                <div class="col-12 pl-0">
                                    <h4 class="page-title"><i class="fe-airplay"></i><a href="{{route('admin.slider.index')}}" class="p-0">Slider</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3">
                            @include('form-elements.back-route-button')
                            <div class="card-body control-col12">
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-select', ['label' => 'Status', 'name' => 'active', 'selected' => $entry->active, 'select' => ['1' => 'Aktywny', '2' => 'Nieaktywny']])
                                </div>
                                <div class="row w-100 form-group d-none">
                                    @include('form-elements.html-select', ['label' => 'Wyciemnienie zdjęcia', 'name' => 'opacity', 'selected' => $entry->opacity, 'select' => [
                                        '0' => '0',
                                        '0.2' => '0.2',
                                        '0.4' => '0.4',
                                        '0.6' => '0.6',
                                        '0.8' => '0.8',
                                        '1.0' => '1',
                                    ]])
                                </div>
                                <div class="row w-100 form-group d-none">
                                    @include('form-elements.html-color', ['label' => 'Kolor wyciemnienia', 'name' => 'color', 'value' => $entry->color])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Tytuł', 'sublabel' => 'Duży napis na zdjęciu, np. nazwa inwestycji', 'name' => 'title', 'value' => $entry->title, 'required' => 1])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Lokalizacja', 'sublabel' => 'Mały napis nad tytułem, np. WARSZAWA · MOKOTÓW', 'name' => 'text', 'value' => $entry->text])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Przycisk: adres', 'sublabel' => 'Puste = lista inwestycji', 'name' => 'link', 'value' => $entry->link])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Przycisk: napis', 'sublabel' => 'Puste = „Zobacz inwestycję”', 'name' => 'link_button', 'value' => $entry->link_button])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-select', ['label' => 'Przycisk: otwieranie', 'name' => 'link_target', 'selected' => $entry->link_target, 'select' => [
                                        '_self' => 'To samo okno',
                                        '_blank' => 'Nowe okno'
                                    ]])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-file', ['label' => 'Zdjęcie', 'sublabel' => '(przycinane do kadru '.config('images.slider.big_width').' x '.config('images.slider.big_height').' px oraz 1280 x 768 i 768 x 461 dla mniejszych ekranów; zapis JPG + WebP)', 'name' => 'file'])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Atrybut ALT zdjęcia', 'name' => 'file_alt', 'value' => $entry->file_alt])
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                </form>
        @endsection
