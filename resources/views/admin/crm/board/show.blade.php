@extends('admin.layout')

@section('content')
    <div class="container-fluid h-100">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-clipboard"></i>{{ $board->first()->name }}</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit"></div>
                </div>
            </div>
        </div>

        <div class="card-header border-bottom card-nav">
            <nav class="nav">
                <a class="nav-link {{ Request::routeIs('admin.crm.statistics.index') ? 'active' : '' }}" href="{{ route('admin.crm.board.index') }}"><span class="fe-list"></span> Lista tablic</a>
            </nav>
        </div>

        <div id="board" class="card mt-3">
            <div class="container-fluid h-100 p-3">
                <div class="stages-container">
                    <div id="stages" class="stages-row">
                        @if($board->first()->stages->count() == 0)
                            <div class="stage" data-stage-id="0">
                                <a class="btn btn-primary dropdown-stage-create" href="#">Uwórz etap</a>
                            </div>
                        @endif
                        @foreach($board->first()->stages as $stage)
                        <div class="stage" data-stage-id="{{ $stage->id }}">
                            <div class="stage-title">
                                <span title="Ilość zadań: {{ $stage->tasks->count() }}">
                                    <span class="badge text-bg-secondary">{{ $stage->tasks->count() }}</span>
                                    <i>{{ $stage->name }}</i>
                                </span>
                                <a role="button" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-menu-dots"><i class="fe-more-horizontal-"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item dropdown-stage-create" href="#">Dodaj etap</a></li>
                                    <li><a class="dropdown-item dropdown-stage-edit" href="#">Edytuj etap</a></li>
                                    <li><a class="dropdown-item dropdown-stage-delete" href="#">Usuń etap</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item dropdown-item-create" href="#">Dodaj zadanie</a></li>
                                </ul>
                            </div>
                            <div class="stage-tasks" data-stage-id="{{ $stage->id }}">
                                @foreach($stage->tasks as $task)
                                    <div class="task" data-task-id="{{ $task->id }}">
                                        <div class="task-body">
                                            <div class="task-name w-100">{{ $task->name }}</div>
                                            @if($task->client)<div class="task-desc w-100"><a href="">{{ $task->client->name }}</a></div>@endif
                                            <div class="task-date w-50 d-flex align-items-center">{{ $task->created_at->diffForHumans() }}</div>
                                            <div class="task-action  d-flex align-items-center justify-content-end w-50">
                                                <a role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fe-more-horizontal-"></i></a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item dropdown-item-edit" href="#">Edytuj zadanie</a></li>
                                                    <li><a class="dropdown-item dropdown-item-delete" href="#">Usuń zadanie</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('style')
        <style>#content .content {height: 100%}</style>
    @endpush
    @routes('board')
    @push('scripts')
        <script src="{{ asset('/js/ui/jquery-ui.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/typeahead.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/board.min.js') }}" charset="utf-8"></script>

        <link href="{{ asset('/js/ui/jquery-ui.css') }}" rel="stylesheet">
        <script>
            const $stageTasks = $(".stage-tasks"), $stages = $("#stages");
            const token = '{{ csrf_token() }}';
            const board_id = {{ $board->first()->id }};
            let task_old_parent_id;

            sortableFunctions = {
                sortableStageTasks: function sortableStageTasks(element){
                    element.sortable({
                        cursor: "grabbing",
                        containment: $stages,
                        connectWith: ".stage-tasks",
                        placeholder: "ui-state-highlight",
                        start: function(event, ui){
                            ui.placeholder.height(ui.item.height());
                            ui.placeholder.html(ui.item[0].outerHTML);
                            task_old_parent_id = ui.item.parent().data('stage-id');
                        },
                        update: function(event, ui) {
                            const task_parent = ui.item.parent();
                            const task_parent_id = task_parent.data('stage-id');
                            const items = task_parent.sortable('toArray', {attribute: 'data-task-id'});

                            if (this === task_parent[0]) {
                                countTasksByStageId(task_old_parent_id);
                                countTasksByStageId(task_parent_id);

                                jQuery.ajax({
                                    type: 'POST',
                                    data: {
                                        '_token': token,
                                        'items': items,
                                        'stage_id': task_parent_id
                                    },
                                    url: route('admin.crm.board.task.sort'),
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
                },
                sortableStages: function sortableStages(element){
                    element.sortable({
                        cursor: "grabbing",
                        containment: "#board",
                        connectWith: $stages,
                        placeholder: "ui-state-highlight",
                        axis: "x",
                        update: function() {
                            const items = $(this).sortable('toArray', {attribute: 'data-stage-id'});
                            jQuery.ajax({
                                type: 'POST',
                                data: {
                                    '_token': token,
                                    'items': items
                                },
                                url: route('admin.crm.board.stage.sort'),
                                success: function () {
                                    toastr.options =
                                        {
                                            "closeButton" : true,
                                            "progressBar" : true,
                                            "preventDuplicates": true
                                        }
                                    toastr.success("Tablica została zaktualizowana");
                                },
                                error: function () {
                                    toastr.options =
                                        {
                                            "closeButton" : true,
                                            "progressBar" : true,
                                            "preventDuplicates": true
                                        }
                                    toastr.error("Wystąpił błąd podczas zapisu");
                                }
                            });
                        }
                    }).disableSelection();
                }
            }

            function updateStages(){
                sortableFunctions.sortableStageTasks($stageTasks);
                sortableFunctions.sortableStages($stages)
            }

            $(function() {
                updateStages();
            });
        </script>
    @endpush
@endsection
