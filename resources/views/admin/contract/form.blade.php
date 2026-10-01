@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    @if(Route::is('admin.contract.edit'))
        <form method="POST" action="{{route('admin.contract.update', $entry)}}" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form method="POST" action="{{route('admin.contract.store')}}" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="container">
                        <div class="card-head container">
                            <div class="row">
                                <div class="col-12 pl-0">
                                    <h4 class="page-title"><i class="fe-book-open"></i><a href="{{route('admin.contract.index')}}" class="p-0">Generator dokumentów</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                                </div>
                            </div>
                        </div>

                        @php
                            $help = '<p class="regex-legenda">Kliknij w opcję na liście, aby wstawić znacznik:</p><ul class="regex-list mb-0 list-unstyled" data-input="numbering">
                                      <li class="regex-item" data-regex="[n]"><span>n</span>numer</li>
                                      <li class="regex-item" data-regex="[/]"><span>/</span>rozdzielenie</li>
                                      <li class="regex-item" data-regex="[D]"><span>D</span>dzień 01-31</li>
                                      <li class="regex-item" data-regex="[d]"><span>d</span>dzień 1-31</li>
                                      <li class="regex-item" data-regex="[M]"><span>M</span>miesiąc 01-12</li>
                                      <li class="regex-item" data-regex="[m]"><span>m</span>miesiąc 1-12</li>
                                      <li class="regex-item" data-regex="[Y]"><span>Y</span>rok cztery znaki</li>
                                      <li class="regex-item" data-regex="[y]"><span>y</span>rok dwa znaki</li>
                                    </ul>';
                        @endphp

                        <div class="card mt-3">
                            @include('form-elements.back-route-button')
                            <div class="card-body control-col12">

                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Nazwa dokumentu', 'name' => 'name', 'value' => $entry->name, 'required' => 1])
                                </div>

                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Opis dokumentu', 'name' => 'description', 'value' => $entry->description, 'required' => 1])
                                </div>

                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text-help', ['label' => 'Format numerowania', 'name' => 'numbering', 'value' => $entry->numbering, 'help' => $help])
                                </div>

                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-file', [
                                        'label' => 'Szablon dokumentu',
                                        'sublabel' => 'Plik .docx',
                                        'name' => 'file',
                                        'file' => $entry->template
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz szablon'])
                </form>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const inputField = document.getElementById("numbering");
                        const listItems = document.querySelectorAll(".regex-item");
                        listItems.forEach(item => {
                            item.addEventListener("click", function() {
                                const dataRegexValue = this.getAttribute("data-regex");
                                this.parentElement.setAttribute("data-input", inputField.name);
                                const startPos = inputField.selectionStart;
                                const endPos = inputField.selectionEnd;
                                const currentValue = inputField.value;
                                inputField.value = currentValue.slice(0, startPos) + dataRegexValue + currentValue.slice(endPos);
                                const newCursorPos = startPos + dataRegexValue.length;
                                inputField.setSelectionRange(newCursorPos, newCursorPos);
                            });
                        });
                    });
                </script>
        @endsection
