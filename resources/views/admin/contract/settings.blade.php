@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    <form method="POST" action="{{ route('admin.contract.save-settings', $entry) }}" enctype="multipart/form-data">
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

                            @foreach ($placeholders as $placeholderName => $placeholderData)
                                @php
                                    if (is_array($placeholderData)) {
                                        $placeholderValue = $placeholdersData[$placeholderName]['placeholder'] ?? $placeholderData['placeholder'];
                                    } else {
                                        $placeholderValue = $placeholderData;
                                    }
                                @endphp

                               <div class="form-group">
                                    <div class="row">
                                        <label for="{{ $placeholderValue }}[placeholder]" class="col-3 col-form-label control-label required">
                                            <div class="text-right">Nazwa w pliku</div>
                                        </label>
                                        <div class="col-4">
                                            <input class="form-control" name="{{ $placeholderValue }}[placeholder]" type="text" id="{{ $placeholderValue }}[placeholder]" value="{{ $placeholderValue }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label for="{{ $placeholderValue }}[form]" class="col-3 col-form-label control-label required">
                                            <div class="text-right">Nazwa w formularzu</div>
                                        </label>
                                        <div class="col-4">
                                            <input class="form-control" name="{{ $placeholderValue }}[form]" type="text" id="{{ $placeholderValue }}[form]" value="@isset($placeholderData['form']){{ $placeholderData['form'] }}@endisset" required>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label for="{{ $placeholderValue }}[type]" class="col-3 col-form-label control-label required">
                                            <div class="text-right">Typ pola w formularzu</div>
                                        </label>
                                        <div class="col-4">
                                            <select class="form-select" id="{{ $placeholderValue }}[type]" name="{{ $placeholderValue }}[type]">
                                                <option value="text" @if(isset($placeholderData['type']) && $placeholderData['type'] === 'text') selected @endif>Pole tekstowe - krótkie</option>
                                                <option value="textarea" @if(isset($placeholderData['type']) && $placeholderData['type'] === 'textarea') selected @endif>Pole tekstowe - długie</option>
                                                <option value="date" @if(isset($placeholderData['type']) && $placeholderData['type'] === 'date') selected @endif>Pole z datą</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label for="{{ $placeholderValue }}[required]" class="col-3 col-form-label control-label required">
                                            <div class="text-right">Pole wymagane</div>
                                        </label>
                                        <div class="col-4">
                                            <select class="form-select" id="{{ $placeholderValue }}[required]" name="{{ $placeholderValue }}[required]">
                                                <option value="1" @if(isset($placeholderData['required']) && $placeholderData['required'] === '1') selected @endif>Tak</option>
                                                <option value="0" @if(isset($placeholderData['required']) && $placeholderData['required'] === '0') selected @endif>Nie</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz dokument'])
    </form>
@endsection
