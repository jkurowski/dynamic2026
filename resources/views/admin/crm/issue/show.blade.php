@extends('admin.layout')

@section('content')
    <div class="container-fluid h-100">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-alert-triangle"></i><a href="{{ route('admin.crm.issue.index') }}">Zgłoszenia</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $issue->title }}</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit"></div>
                </div>
            </div>
        </div>
        <div id="board" class="card mt-3 h-auto">
            <div class="container-fluid">
                <div class="board-title">Aktualny etap zgłoszenia</div>
                <div class="stages-container issue-stage-container">
                    <div id="stages" class="stages-row h-auto">
                        @php
                            $stages = [
                                1 => 'Nowe',
                                2 => 'W toku',
                                3 => 'Zakończone',
                                4 => 'Odrzucone',
                                5 => 'Zawieszone',
                            ];
                        @endphp

                        @foreach($stages as $status => $stageTitle)
                            <div class="stage issue-stage" data-status="{{ $status }}">
                                <div class="stage-title issue-status-{{ $status }}">{{ $stageTitle }}</div>
                                <div class="stage-tasks" data-stage-id="{{ $status }}">
                                    @if($issue->status == $status)
                                        <div class="task" data-task-id="{{ $issue->id }}">
                                            <div class="task-body">
                                                <div class="task-name w-100">{{ $issue->title }}</div>
                                                <div class="container p-0">
                                                    <div class="row issue-stage-desc no-gutters">
                                                        <div class="col-6">Klient:</div><div class="col-6"><a href="">{{ $issue->client->name }}</a></div>
                                                        <div class="col-6">Dział:</div><div class="col-6">{{ $issue->department->name }}</div>
                                                        <div class="col-6">Inwestycja:</div><div class="col-6">{{ $issue->investment->name }}</div>
                                                        <div class="col-6">Lokal:</div><div class="col-6">{{ $issue->property->name }}</div>
                                                    </div>
                                                </div>
                                                <div class="task-date d-flex align-items-center mt-2 w-100">Data utworzenia: {{ $issue->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div id="portlets" class="card mt-3 bg-transparent shadow-none">
            <div class="card-body card-body-rem p-0">
                <div class="row">
                    <!-- widget data-group list-group- -->
                    <div class="col-5">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Załączniki</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="jquery-wrapped-fine-uploader"></div>

                                <div id="files">
                                    <div class="note">
                                        <div class="noteItemIcon"><i class="fe-hard-drive"></i></div>
                                        <div class="noteItemContent p-0">
                                            @foreach($issue->issueFiles as $file)
                                                <div class="file" data-file-id="{{$file->id}}">
                                                    <div class="noteItemType"><i class="{{mime2icon($file->mime)}}"></i></div>
                                                    <div class="noteItemText">
                                                        <a href="{{ asset('/uploads/issue_files/'.$file->file) }}" target="_blank">{{$file->name}}</a>
                                                        <p>{{$file->description}}</p>
                                                    </div>
                                                    <div class="noteItemDate">{{$file->created_at->diffForHumans()}}<span class="separator">·</span>{{$file->user()->first()->name}} {{$file->user()->first()->surname}}<span class="separator">·</span>{{parseFilesize($file->size)}}</div>
                                                    <div class="noteItemButtons">
                                                        <a role="button" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-menu-dots"><i class="fe-more-horizontal-"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item dropdown-item-download" href="{{ asset('/uploads/issue_files/'.$file->file) }}" download>Pobierz</a></li>
                                                            <li><a class="dropdown-item dropdown-item-delete" href="#">Usuń</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="note-start">
                                        <div class="noteItemDate">{{$issue->created_at}}</div>
                                        <div class="noteItemClient"><strong>Zgłoszenie dodane do systemu</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end of widget -->

                    <!-- widget data-group list-group- -->
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Notatki</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                    @include('shared.forms.form-note', ['notes' => $notes, 'model' => $issue])

                                    <div class="note-start">
                                        <div class="noteItemDate">{{$issue->created_at}}</div>
                                        <div class="noteItemClient"><strong>Zgłoszenie dodane do systemu</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end of widget -->

                    <!-- widget data-group list-group- -->
                    <div class="col-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Historia zmian</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="history">
                                    <div class="task-history mt-2">
                                        @foreach (collect(json_decode($issue->history, true))->sortByDesc('datetime') as $change)
                                            <div class="note">
                                                <div class="noteItemIcon"><i class="fe-book"></i></div>
                                                <div class="noteItemContent">
                                                    <p>Zmiana statusu z <b>{{ issueStatus($change['old_status']) }}</b> na <b>{{ issueStatus($change['new_status']) }}</b> w dniu: {{ \Carbon\Carbon::parse($change['datetime'])->format('Y-m-d H:i:s') }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="note-start">
                                        <div class="noteItemDate">{{$issue->created_at}}</div>
                                        <div class="noteItemClient"><strong>Zgłoszenie dodane do systemu</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end of widget -->
                </div>
            </div>
        </div>
    </div>
    @push('style')
        <style>#content .content {height: 100%}</style>
    @endpush
    @routes('issue_file')
    @push('scripts')
        <link href="{{ asset('/js/ui/jquery-ui.css') }}" rel="stylesheet">
        <script src="{{ asset('/js/ui/jquery-ui.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/fineuploader.js') }}" charset="utf-8"></script>

        <script>
            sortableFunctions = {
                sortableStageTasks: function sortableStageTasks(element){
                    element.sortable({
                        cursor: "grabbing",
                        containment: "#stages",
                        connectWith: ".stage-tasks",
                        placeholder: "ui-state-highlight",
                        start: function(event, ui){
                            ui.placeholder.height(ui.item.height());
                            ui.placeholder.html(ui.item[0].outerHTML);
                        },
                        update: function(event, ui) {
                            const task_parent = ui.item.parent();
                            const task_parent_id = task_parent.data('stage-id');
                            const items = task_parent.sortable('toArray', {attribute: 'data-task-id'});

                            if (this === task_parent[0]) {
                                jQuery.ajax({
                                    type: 'POST',
                                    data: {
                                        '_token': '{{ csrf_token() }}',
                                        'status': task_parent_id
                                    },
                                    url: '{{ route('admin.crm.issue.update-status', $issue) }}',
                                    success: function () {
                                        toastr.options =
                                            {
                                                "closeButton": true,
                                                "progressBar": true
                                            }
                                        toastr.success("Zmiana pozycji zapisana");
                                    },
                                    error: function () {
                                        toastr.options =
                                            {
                                                "closeButton": true,
                                                "progressBar": true
                                            }
                                        toastr.error("Wystąpił błąd");
                                    }
                                });
                            }
                        }
                    }).disableSelection();
                }
            }

            $( function() {
                const $stageTasks = $( ".stage-tasks" );
                sortableFunctions.sortableStageTasks($stageTasks);
            });
        </script>

        <script>
            const UploadedFile = ({ id, icon, file, name, user, created_at, file_size }) => `<div class="file" data-file-id="${id}"><div class="noteItemType"><i class="${icon}"></i></div><div class="noteItemText"><a href="/uploads/issue_files/${file}" target="_blank">${name}</a><p></p></div><div class="noteItemDate">${created_at}<span class="separator">·</span>${user}<span class="separator">·</span>${file_size}</div><div class="noteItemButtons"><a role="button" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-menu-dots"><i class="fe-more-horizontal-"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item dropdown-item-download" href="/uploads/issue_files/${file}" download>Pobierz</a></li><li><a class="dropdown-item dropdown-item-delete" href="#">Usuń</a></li></ul></div></div>`;


            const filesList = $("#files .noteItemContent.p-0");
            let fileCount = 0;

            $('#jquery-wrapped-fine-uploader').fineUploader({
                debug: false,
                multiple: true,
                text: {
                    uploadButton: "Wybierz plik",
                    dragZone: "Przeciągnij i upuść plik tutaj"
                },
                request: {
                    endpoint: '{{ route('admin.crm.issue.file.upload', $issue) }}',
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
                                $.ajax({
                                    url: route('admin.crm.issue.file.destroy', [{{ $issue->id }}, parent.dataset.fileId]),
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
        </script>
    @endpush
@endsection
