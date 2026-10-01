@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    @if(Route::is('admin.contract.template.edit'))
        <form method="POST" action="{{route('admin.contract.template.update', $entry)}}" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form method="POST" action="{{route('admin.contract.template.store')}}" enctype="multipart/form-data">
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
                                    <div class="col-4">
                                        <label for="usedVariables" class="col-form-label control-label">Tagi w dokumencie</label>
                                        <div id="usedVariables"></div>
                                    </div>
                                    <div class="col-8">
                                        @include('form-elements.textarea-fullwidth', ['label' => 'Treść dokumentu', 'name' => 'text', 'value' => $entry->text, 'rows' => 21, 'class' => 'tinymce', 'required' => 1])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="hiddenUniqueMatches" name="tags">
                    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz szablon'])
                </form>
                @endsection
                @push('scripts')
                    <script src="{{ asset('/js/editor/tinymce.min.js') }}" charset="utf-8"></script>
                    <script>
                        tinymce.init({
                            selector: ".tinymce",
                            language: "pl",
                            skin: "oxide",
                            content_css: 'default',
                            branding: false,
                            height: 400,
                            plugins: [
                                "code advlist autolink link image lists charmap print preview hr anchor pagebreak",
                                "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                                "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
                            ],
                            toolbar1: "formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat | gallery | responsivefilemanager | code",
                            relative_urls: false,
                            image_advtab: true,
                            external_filemanager_path:"/js/editor/plugins/filemanager/",
                            filemanager_title:"kCMS Filemanager" ,
                            external_plugins: { "filemanager" : "{{ asset('/js/editor/plugins/filemanager/plugin.min.js') }}"},
                            setup: function (editor) {

                                let uniqueMatches = [];
                                function updateUsedVariables() {
                                    document.getElementById('usedVariables').innerHTML = '';
                                    uniqueMatches.forEach(function (word) {
                                        const link = document.createElement('a');
                                        link.href = '#' + word;
                                        link.textContent = word;
                                        link.classList.add('btn', 'btn-primary');

                                        link.addEventListener('click', function () {
                                            const cursorPosition = editor.selection.getRng();
                                            editor.selection.setRng(cursorPosition);
                                            editor.insertContent('[' + word + ']');
                                        });

                                        document.getElementById('usedVariables').appendChild(link);
                                        document.getElementById('usedVariables').appendChild(document.createTextNode(' '));
                                    });

                                    document.getElementById('hiddenUniqueMatches').value = JSON.stringify(uniqueMatches);
                                }

                                editor.on('change input', function () {
                                    const content = editor.getContent();
                                    const matches = content.match(/\[(.*?)\]/g);
                                    if (matches) {
                                        uniqueMatches = [...new Set(matches.map(match => match.replace(/[\[\]]/g, '')))];
                                    } else {
                                        uniqueMatches = [];
                                    }
                                    updateUsedVariables();
                                });

                                editor.on('init', function () {
                                    editor.fire('change');
                                });
                            }
                        });
                    </script>
        @endpush
