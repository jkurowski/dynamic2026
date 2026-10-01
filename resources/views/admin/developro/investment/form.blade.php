@extends('admin.layout')

@section('content')
    @if (Route::is('admin.developro.investment.edit'))
        <form method="POST" action="{{ route('admin.developro.investment.update', $entry->id) }}"
            enctype="multipart/form-data">
            @method('PUT')
        @else
            <form method="POST" action="{{ route('admin.developro.investment.store') }}" enctype="multipart/form-data">
    @endif
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-head container">
                <div class="row">
                    <div class="col-12 pl-0">
                        <h4 class="page-title"><i class="fe-book-open"></i><a href="{{ route('admin.developro.investment.index') }}" class="p-0">Inwestycje</a><span class="d-inline-flex ms-2 me-2">/</span>{{ $cardTitle }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            @include('form-elements.back-route-button')
            <div class="card-body control-col12">

                <div class="row w-100 mb-4">
                    <div class="col-12">
                        @include('form-elements.html-input-text', [
                            'label' => 'Nazwa inwestycji',
                            'name' => 'name',
                            'value' => $entry->name,
                            'required' => 1,
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    <div class="col-4">
                        @include('form-elements.html-select', [
                            'label' => 'Typ inwestycji',
                            'name' => 'type',
                            'selected' => $entry->type,
                            'select' => [
                                '1' => 'Inwestycja osiedlowa',
                                '2' => 'Inwestycja budynkowa',
                                '3' => 'Inwestycja z domami',
                            ],
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-select', [
                            'label' => 'Status inwestycji',
                            'name' => 'status',
                            'selected' => $entry->status,
                            'select' => [
                                '1' => 'Inwestycja w sprzedaży',
                                '2' => 'Inwestycja zakończona',
                                '3' => 'Inwestycja planowana',
                                '4' => 'Inwestycja ukryta',
                            ],
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-select', [
                            'label' => 'Postęp inwestycji',
                            'name' => 'progress',
                            'selected' => $entry->progress,
                            'select' => [
                                '1' => 'Przedsprzedaż',
                                '2' => 'Realizacja',
                                '7' => 'Gotowe do odbioru',
                                '8' => 'Planowana',
                            ],
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    <div class="col-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Adres inwestycji',
                            'name' => 'address',
                            'value' => $entry->address,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-select', [
                            'label' => 'Miasto inwestycji',
                            'name' => 'city_id',
                            'selected' => $entry->city_id,
                            'select' => $cities_form,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-select', [
                            'label' => 'Lokale usługowe',
                            'name' => 'commercial',
                            'selected' => $entry->commercial,
                            'select' => [
                                '2' => 'Nie',
                                '1' => 'Tak'
                            ],
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4 form-group">
                    <div class="col-12">
                        <h2>Szczegółowe informacje o inwestycji</h2>
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-select', [
                            'label' => 'Województwo lokalizacji',
                            'name' => 'inv_province',
                            'selected' => $entry->inv_province,
                            'select' => $provinces,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Powiat lokalizacji',
                            'name' => 'inv_county',
                            'value' => $entry->inv_county,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Gmina lokalizacji',
                            'name' => 'inv_municipality',
                            'value' => $entry->inv_municipality,
                        ])
                    </div>
                    <div class="col-4 mt-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Miejscowość lokalizacji',
                            'name' => 'inv_city',
                            'value' => $entry->inv_city,
                        ])
                    </div>
                    <div class="col-4 mt-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Ulica lokalizacji',
                            'name' => 'inv_street',
                            'value' => $entry->inv_street,
                        ])
                    </div>
                    <div class="col-4 mt-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Nr nieruchomości lokalizacji',
                            'name' => 'inv_property_number',
                            'value' => $entry->inv_property_number,
                        ])
                    </div>
                    <div class="col-4 mt-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Kod pocztowy lokalizacji',
                            'name' => 'inv_postal_code',
                            'value' => $entry->inv_postal_code,
                        ])
                    </div>
                    <div class="col-4 mt-4">
                        @include('form-elements.html-select', [
                            'label' => 'Spółka celowa',
                            'name' => 'company_id',
                            'selected' => $entry->company_id,
                            'select' => $companies,
                        ])
                    </div>
                    <div class="col-4 mt-4">
                        @include('form-elements.html-select', [
                            'label' => 'Punkt sprzedaży',
                            'name' => 'sale_point_id',
                            'selected' => $entry->sale_point_id,
                            'select' => $salePoints,
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    <div class="col-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Termin rozpoczęcia inwestycji',
                            'name' => 'date_start',
                            'value' => $entry->date_start,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Termin zakończenia inwestycji',
                            'name' => 'date_end',
                            'value' => $entry->date_end,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-select', [
                            'label' => 'Galeria',
                            'name' => 'gallery_id',
                            'selected' => $entry->gallery_id,
                            'select' => $galeries_form,
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    <div class="col-4">
                        @include('form-elements.input-text', [
                            'label' => 'Zakres powierzchni w wyszukiwarce xx-xx',
                            'sublabel' => '(zakresy oddzielone przecinkiem)',
                            'name' => 'area_range',
                            'value' => $entry->area_range,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Zakres pokoi w wyszukiwarce',
                            'sublabel' => '(liczby oddzielone przecinkiem)',
                            'name' => 'room_range',
                            'value' => $entry->room_range
                        ])
                    </div>
                    <div class="col-4 d-none">
                        @include('form-elements.html-input-text', [
                            'label' => 'Zakres pięter w wyszukiwarce',
                            'sublabel' => '(liczby oddzielone przecinkiem)',
                            'name' => 'floor_range',
                            'value' => $entry->floor_range
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Ilość lokali',
                            'sublabel' => '(tylko liczby)',
                            'name' => 'areas_amount',
                            'value' => $entry->areas_amount,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-select', [
                            'label' => 'Pokaż ceny',
                            'sublabel' => '(ceny wszystkich powierzchni)',
                            'name' => 'show_prices',
                            'selected' => $entry->show_prices,
                            'select' => [
                                '1' => 'Tak',
                                '0' => 'Nie',
                            ],
                        ])
                    </div>
                </div>
                <div class="row w-100 mb-5">
                    <div class="col-4">
                        @include('form-elements.html-input-text-count', [
                            'label' => 'Nagłówek strony',
                            'sublabel' => 'Meta tag - title',
                            'name' => 'meta_title',
                            'value' => $entry->meta_title,
                            'maxlength' => 60,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-input-text-count', [
                            'label' => 'Opis strony',
                            'sublabel' => 'Meta tag - description',
                            'name' => 'meta_description',
                            'value' => $entry->meta_description,
                            'maxlength' => 158,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Indeksowanie',
                            'sublabel' => 'Meta tag - robots',
                            'name' => 'meta_robots',
                            'value' => $entry->meta_robots,
                        ])
                    </div>
                </div>
                <div class="row w-100 mb-5">
                    <div class="col-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Wyciemnienie miniaturki',
                            'sublabel' => 'wartość: 0% - 100%',
                            'name' => 'gradient_thumb',
                            'value' => $entry->gradient_thumb,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Wyciemnienie nagłowka',
                            'sublabel' => 'wartość: 0% - 100%',
                            'name' => 'gradient_header',
                            'value' => $entry->gradient_header,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-select-multiple', ['label' => 'Podstrony inwestycji', 'sublabel' => 'Menu inwestycji','name' => 'menu', 'selected' => multiselect($entry->menu), 'select' => [
                            '1' => 'Opis inwestycji',
                            '2' => 'Wyszukaj z rzutu',
                            '3' => 'Wyszukaj z modelu 3D',
                            '4' => 'Lokalizacja',
                            '5' => 'Atuty',
                            '6' => 'Dziennik inwestycji',
                            '7' => 'Kontakt',
                            '8' => 'Galeria'
                            ]
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    @include('form-elements.html-input-text', [
                        'label' => 'E-mail do powiadomień',
                        'sublabel' => 'Może być kilka',
                        'name' => 'office_emails',
                        'value' => $entry->office_emails,
                    ])
                </div>

                <div class="row w-100 mb-4">
                    @include('form-elements.html-input-text', [
                        'label' => 'Krótki opis na liście',
                        'name' => 'entry_content',
                        'value' => $entry->entry_content,
                    ])
                </div>

                <div class="row w-100 mb-4">
                    @include('form-elements.html-input-file', [
                        'label' => 'Miniaturka',
                        'sublabel' =>
                            '(wymiary: ' .
                            config('images.investment.thumb_width') .
                            'px / ' .
                            config('images.investment.thumb_height') .
                            'px)',
                        'name' => 'file',
                        'file' => $entry->file_thumb,
                        'file_preview' => config('images.investment.preview_file_path'),
                    ])
                </div>
                <div class="row w-100 mb-4">
                    @include('form-elements.html-input-file', [
                        'label' => 'Nagłówek',
                        'sublabel' =>
                            '(wymiary: ' .
                            config('images.investment.header_width') .
                            'px / ' .
                            config('images.investment.header_height') .
                            'px)',
                        'name' => 'file_header',
                        'file' => $entry->file_header,
                        'file_preview' => config('images.investment.header_preview_file_path'),
                    ])
                </div>

                <div class="row w-100 mb-4">
                    @include('form-elements.html-input-file', [
                        'label' => 'Logo',
                        'sublabel' =>
                            '(wymiary: ' .
                            config('images.investment.logo_width') .
                            'px / ' .
                            config('images.investment.logo_height') .
                            'px)',
                        'name' => 'file_logo',
                        'file' => $entry->file_logo,
                        'file_preview' => config('images.investment.logo_preview_file_path'),
                    ])
                </div>

                <div class="row w-100 mb-4">
                    @include('form-elements.html-input-file-pdf', [
                        'label' => 'Prospekt informacyjny',
                        'name' => 'file_brochure',
                        'file' => $entry->file_brochure,
                        'file_preview' => config('images.investment.brochure_file_path')
                    ])
                </div>

                <div class="row w-100 mb-4">
                    @include('form-elements.textarea-fullwidth', [
                        'label' => 'Opis inwestycji',
                        'name' => 'content',
                        'value' => $entry->content,
                        'rows' => 11,
                        'class' => 'tinymce',
                        'required' => 1,
                    ])
                </div>

                <div class="row w-100 mb-4">
                    @include('form-elements.textarea-fullwidth', [
                        'label' => 'Opis inwestycji po zakończeniu',
                        'name' => 'end_content',
                        'value' => $entry->end_content,
                        'rows' => 11,
                        'class' => 'tinymce',
                    ])
                </div>

                <div class="row w-100 mb-4">
                    @include('form-elements.textarea-fullwidth', [
                        'label' => 'Dane przy formularzu',
                        'name' => 'contact_content',
                        'value' => $entry->contact_content,
                        'rows' => 11,
                        'class' => 'tinymce',
                    ])
                </div>
                <div class="row w-100 form-group">
                    @include('form-elements.textarea-fullwidth', [
                        'label' => 'Kod makiety 3D',
                        'name' => 'mockup',
                        'value' => $entry->mockup,
                        'rows' => 11,
                    ])
                </div>
            </div>
        </div>
    </div>
    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
    </form>
    @include('form-elements.tintmce')
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('[name="supervisors"], [name="office_emails"]').tagify({
                'autoComplete.enabled': false
            });
        });
    </script>
@endpush
