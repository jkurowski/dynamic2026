@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-file"></i><a href="{{route('admin.crm.clients.index')}}">Klienci</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{ route('admin.crm.clients.show', $client->id) }}">{{$client->name}}</a><span class="d-inline-flex me-2 ms-2">/</span>Pliki</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit"></div>
            </div>
        </div>
        @include('admin.crm.client.client_shared.menu')
        <div class="row">
            <div class="col-4">
                @include('admin.crm.client.client_shared.aside')
            </div>
            <div class="col-8">
                <div class="card mt-3">
                    <div class="card-border-header"><i class="fe-file"></i> Dodaj pliki</div>
                    <div class="card-body card-body-rem">
                        <div id="jquery-wrapped-fine-uploader"></div>
                    </div>
                </div>

                <div class="card mt-3 d-none">
                    <div class="card-border-header"><i class="fe-file"></i> Przeglądaj pliki</div>
                    <div class="card-body card-body-rem">
                        <form method="POST" action="" id="tinyMceForm">
                            <div class="row">
                                <div class="col-9">
                                    @include('form-elements.html-input-browser', ['label' => 'Wybierz plik', 'name' => 'file', 'class' => 'col-9'])
                                </div>
                                <div class="col-3">
                                    <input id="submit" value="Dodaj wybrany plik" class="btn btn-primary w-100" type="submit">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body card-body-rem">
                        <div id="files">
                            <div class="note">
                                <div class="noteItemIcon"><i class="fe-hard-drive"></i></div>
                                <div class="noteItemContent">
                                    @foreach($files as $file)
                                    <div class="file" data-file-id="{{$file->id}}">
                                        <div class="noteItemType"><i class="{{mime2icon($file->mime)}}"></i></div>
                                        <div class="noteItemText">
                                            <a href="{{ asset('/uploads/user_files/'.$file->file) }}" target="_blank">{{$file->name}}</a>
                                            <p>{{$file->description}}</p>
                                        </div>
                                        <div class="noteItemDate">{{$file->created_at->diffForHumans()}}<span class="separator">·</span>{{$file->user()->first()->name}} {{$file->user()->first()->surname}}<span class="separator">·</span>{{parseFilesize($file->size)}}</div>
                                        <div class="noteItemButtons">
                                            <a role="button" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-menu-dots"><i class="fe-more-horizontal-"></i></a>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item dropdown-item-download" href="{{ asset('/uploads/user_files/'.$file->file) }}" download>Pobierz</a></li>
                                                <li><a class="dropdown-item dropdown-item-addtext" href="#">@if(!$file->description) Dodaj opis @else Edytuj opis @endif</a></li>
                                                <li><a class="dropdown-item dropdown-item-delete" href="#">Usuń</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="note-start">
                                <div class="noteItemDate">{{$client->created_at}}</div>
                                <div class="noteItemClient"><strong>Klient dodany do systemu</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @routes('client_file')
    @push('scripts')
        <script src="{{ asset('/js/fineuploader.js') }}" charset="utf-8"></script>
        <script type="text/javascript">
            const UploadedFile = ({ id, icon, file, name, user, created_at, file_size }) => `<div class="file" data-file-id="${id}"><div class="noteItemType"><i class="${icon}"></i></div><div class="noteItemText"><a href="/uploads/storage/${file}" target="_blank">${name}</a><p></p></div><div class="noteItemDate">${created_at}<span class="separator">·</span>${user}<span class="separator">·</span>${file_size}</div><div class="noteItemButtons"><a role="button" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-menu-dots"><i class="fe-more-horizontal-"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item dropdown-item-download" href="/uploads/user_files/${file}" download>Pobierz</a></li><li><a class="dropdown-item dropdown-item-addtext" href="#">Dodaj opis</a></li><li><a class="dropdown-item dropdown-item-delete" href="#">Usuń</a></li></ul></div></div>`;

            const filesList = $(".noteItemContent"),
                form = document.getElementById('tinyMceForm'),
                fileInput = document.getElementById('file');
            let fileCount = 0;
            fileInput.value = '';

            $('#jquery-wrapped-fine-uploader').fineUploader({
                debug: false,
                multiple: true,
                text: {
                    uploadButton: "Wybierz plik",
                    dragZone: "Przeciągnij i upuść plik tutaj"
                },
                request: {
                    endpoint: '{{ route('admin.crm.clients.files.store', $client->id) }}',
                    customHeaders: {
                        "X-CSRF-Token": $("meta[name='csrf-token']").attr("content")
                    }
                }
            }).on('error', function (event, id, name, reason) {
                console.log(reason);
            }).on('submit', function () {
                fileCount++;
            }).on('complete', function (event, id, name, response) {
                if (response.success === true) {
                    fileCount--;
                    if (fileCount === 0) {
                        filesList.prepend([
                            {
                                id: response.file.id,
                                icon: response.file.icon,
                                file: response.file.file,
                                name: response.file.name,
                                user: response.file.user.name +' '+ response.file.user.surname,
                                created_at: response.file.created_at,
                                file_size: response.file.size
                            },
                        ].map(UploadedFile).join(''));
                    }
                }
            });

            filesList.on('click', '.dropdown-item-delete', function(event){
                const target = event.target;
                const parent = target.closest(".file");
                $.confirm({
                    title: "Potwierdzenie usunięcia",
                    message: "Czy na pewno chcesz usunąć?",
                    buttons: {
                        Tak: {
                            "class": "btn btn-primary",
                            action: function() {

                                console.log(parent.dataset.fileId);

                                $.ajax({
                                    url: route('admin.crm.clients.file.destroy', [{{ $client->id }}, parent.dataset.fileId]),
                                    type: "DELETE",
                                    data: {
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function() {
                                        toastr.options =
                                            {
                                                "closeButton" : true,
                                                "progressBar" : true
                                            }
                                        toastr.success("Plik został poprawnie usunięty");
                                        parent.style.height = "0px"
                                        parent.remove();
                                    }
                                })
                            }
                        },
                        Nie: {
                            "class": "btn btn-secondary",
                            action: function() {}
                        }
                    }
                })
            });

            filesList.on('click', '.dropdown-item-addtext', function(event){
                const target = event.target;
                const parent = target.closest(".file");
                jQuery.ajax({
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    url: route('admin.crm.clients.file.desc.form', parent.dataset.fileId),
                    success: function(response) {
                        if(response) {
                            $('body').append(response);
                        } else {
                            alert('Error');
                        }
                    }
                });
            });

            form.addEventListener('submit', (e)=> {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.crm.clients.files.create', $client->id) }}",
                    method: 'POST',
                    async: false,
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'file': fileInput.value
                    },
                    success: function(response) {
                        if (response.success === true) {

                            filesList.prepend([
                                {
                                    id: response.file.id,
                                    icon: response.file.icon,
                                    file: response.file.file,
                                    name: response.file.name,
                                    user: response.file.user.name +' '+ response.file.user.surname,
                                    created_at: response.file.created_at,
                                    file_size: response.file.size
                                },
                            ].map(UploadedFile).join(''));
                            fileInput.value = '';
                        }
                    },
                    error : function(result){
                        if(result.responseJSON.errors)
                        {
                            alert.html('');
                            $.each(result.responseJSON.errors, function(key, value){
                                alert.show();
                                alert.append('<span>'+value+'</span>');
                            });
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
