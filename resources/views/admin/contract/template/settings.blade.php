@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    <form method="POST" action="{{ route('admin.contract.template.save-settings', $entry) }}" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="card-head container">
                <div class="row">
                    <div class="col-12 pl-0">
                        <h4 class="page-title"><i class="fe-book-open"></i><a href="{{route('admin.contract.index')}}" class="p-0">Generator dokumentów</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                @include('form-elements.back-route-button')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @foreach ($tagsArray as $tagName => $tagData)
                                @if (array_key_exists($tagName, $placeholdersArray))
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="{{ $placeholdersArray[$tagName]['placeholder'] }}[placeholder]" class="col-3 col-form-label control-label required">
                                                <div class="text-right">Nazwa w pliku</div>
                                            </label>
                                            <div class="col-4">
                                                <input class="form-control" name="{{ $placeholdersArray[$tagName]['placeholder'] }}[placeholder]" type="text" id="{{ $placeholdersArray[$tagName]['placeholder'] }}[placeholder]" value="{{ $placeholdersArray[$tagName]['placeholder'] }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <label for="{{ $placeholdersArray[$tagName]['placeholder'] }}[form]" class="col-3 col-form-label control-label required">
                                                <div class="text-right">Nazwa w formularzu</div>
                                            </label>
                                            <div class="col-4">
                                                <input class="form-control" name="{{ $placeholdersArray[$tagName]['placeholder'] }}[form]" type="text" id="{{ $placeholdersArray[$tagName]['placeholder'] }}[form]" value="@isset($placeholdersArray[$tagName]['form']){{ $placeholdersArray[$tagName]['form'] }}@endisset" required>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <label for="{{ $placeholdersArray[$tagName]['placeholder'] }}[type]" class="col-3 col-form-label control-label required">
                                                <div class="text-right">Typ pola w formularzu</div>
                                            </label>
                                            <div class="col-4">
                                                <select class="form-select" id="{{ $placeholdersArray[$tagName]['placeholder'] }}[type]" name="{{ $placeholdersArray[$tagName]['placeholder'] }}[type]">
                                                    <option value="text" @if(isset($placeholdersArray[$tagName]['type']) && $placeholdersArray[$tagName]['type'] === 'text') selected @endif>Pole tekstowe - krótkie</option>
                                                    <option value="textarea" @if(isset($placeholdersArray[$tagName]['type']) && $placeholdersArray[$tagName]['type'] === 'textarea') selected @endif>Pole tekstowe - długie</option>
                                                    <option value="date" @if(isset($placeholdersArray[$tagName]['type']) && $placeholdersArray[$tagName]['type'] === 'date') selected @endif>Pole z datą</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <label for="{{ $placeholdersArray[$tagName]['placeholder'] }}[required]" class="col-3 col-form-label control-label required">
                                                <div class="text-right">Pole wymagane</div>
                                            </label>
                                            <div class="col-4">
                                                <select class="form-select" id="{{ $placeholdersArray[$tagName]['placeholder'] }}[required]" name="{{ $placeholdersArray[$tagName]['placeholder'] }}[required]">
                                                    <option value="1" @if(isset($placeholdersArray[$tagName]['required']) && $placeholdersArray[$tagName]['required'] === '1') selected @endif>Tak</option>
                                                    <option value="0" @if(isset($placeholdersArray[$tagName]['required']) && $placeholdersArray[$tagName]['required'] === '0') selected @endif>Nie</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="{{ $tagName }}[placeholder]" class="col-3 col-form-label control-label required">
                                                <div class="text-right">Nazwa w pliku</div>
                                            </label>
                                            <div class="col-4">
                                                <input class="form-control" name="{{ $tagName }}[placeholder]" type="text" id="{{ $tagName }}[placeholder]" value="{{ $tagName }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <label for="{{ $tagName }}[form]" class="col-3 col-form-label control-label required">
                                                <div class="text-right">Nazwa w formularzu</div>
                                            </label>
                                            <div class="col-4">
                                                <input class="form-control" name="{{ $tagName }}[form]" type="text" id="{{ $tagName }}[form]" value="" required>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <label for="{{ $tagName }}[type]" class="col-3 col-form-label control-label required">
                                                <div class="text-right">Typ pola w formularzu</div>
                                            </label>
                                            <div class="col-4">
                                                <select class="form-select" id="{{ $tagName }}[type]" name="{{ $tagName }}[type]">
                                                    <option value="text">Pole tekstowe - krótkie</option>
                                                    <option value="textarea">Pole tekstowe - długie</option>
                                                    <option value="date">Pole z datą</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <label for="{{ $tagName }}[required]" class="col-3 col-form-label control-label required">
                                                <div class="text-right">Pole wymagane</div>
                                            </label>
                                            <div class="col-4">
                                                <select class="form-select" id="{{ $tagName }}[required]" name="{{ $tagName }}[required]">
                                                    <option value="1">Tak</option>
                                                    <option value="0">Nie</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz dokument'])
    </form>
@endsection
