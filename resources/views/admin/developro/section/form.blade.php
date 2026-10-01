@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    @if(Route::is('admin.developro.investment.section.edit'))
        <form method="POST" action="{{route('admin.developro.investment.section.update', [$investment, $entry])}}" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form method="POST" action="{{route('admin.developro.investment.section.store', $investment)}}" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="container">
                        <div class="card-head container">
                            <div class="row">
                                <div class="col-12 pl-0">
                                    <h4 class="page-title"><i class="fe-home"></i><a href="{{route('admin.developro.investment.index')}}">Inwestycje</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{route('admin.developro.investment.section.index', $investment)}}">{{$investment->name}}: Sekcje opisu inwestycji</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            @include('form-elements.back-route-button')
                            <div class="card-body control-col12">

                            @php
                                // Use the fields from the model unless you're editing the section, in which case, use a default set
                                $visibleFields = Route::is('admin.developro.investment.section.edit')
                                                ? $entry->fields
                                                : ['title', 'subtitle', 'file', 'file_alt', 'content', 'content_editor', 'code'];
                            @endphp

                            @if(!$entry->lock)
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-select', ['label' => 'Blokada', 'name' => 'lock', 'selected' => $entry->lock, 'select' => ['1' => 'Tak', '0' => 'Nie']])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-checkbox-multi', [
                                        'name' => 'fields',
                                        'label' => 'Dostępne sekcje',
                                        'options' => [
                                            'title' => 'Tytuł',
                                            'subtitle' => 'Sub-tytul',
                                            'file' => 'Plik',
                                            'file_alt' => 'Alt dla obrazka',
                                            'content' => 'Treść',
                                            'content_editor' => 'Edytor treść',
                                            'code' => 'Kod HTML',
                                        ],
                                        'value' => Route::is('admin.developro.investment.section.edit')
                                                ? $entry->fields
                                                : [],
                                        'required' => true,
                                    ])
                                </div>
                                @endif

                                @if(in_array('title', $visibleFields))
                                    <div class="row w-100 form-group">
                                        @include('form-elements.html-input-text', ['label' => 'Tytuł', 'name' => 'title', 'value' => $entry->title])
                                    </div>
                                @endif

                                @if(in_array('subtitle', $visibleFields))
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Sub-tytuł', 'name' => 'subtitle', 'value' => $entry->subtitle])
                                </div>
                                @endif

                                @if(in_array('file', $visibleFields))
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-file', [
                                        'label' => 'Zdjęcie',
                                        'name' => 'file',
                                        'file' => $entry->file,
                                        'file_preview' => config('images.investment.section_preview_file_path')
                                    ])
                                </div>
                                @endif

                                @if(in_array('file', $visibleFields))
                                    <div class="row w-100 form-group">
                                        @include('form-elements.html-input-text', ['label' => 'Alt dla obrazka', 'name' => 'file_alt', 'value' => $entry->file_alt])
                                    </div>
                                @endif

                                @if(in_array('content', $visibleFields))
                                    <div class="row w-100 form-group">
                                        @include('form-elements.textarea-fullwidth', ['label' => 'Treść', 'name' => 'content', 'value' => $entry->content, 'rows' => 11, 'class' => 'tinymce'])
                                    </div>
                                @endif

                                @if(in_array('code', $visibleFields))
                                    <div class="row w-100 form-group">
                                        @include('form-elements.textarea-fullwidth', ['label' => 'Treść lub kod html', 'name' => 'code', 'value' => $entry->code, 'rows' => 11])
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="investment_id" value="{{$investment->id}}">
                    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                </form>
        @if(in_array('content_editor', $visibleFields))
            @include('form-elements.tintmce')
        @endif
        @endsection
