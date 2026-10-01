<div class="modal fade modal-form" id="portletModal" tabindex="-1" aria-labelledby="portletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="post" id="modalForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="portletModalLabel">Zadanie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fe-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="modal-form container">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="inputName" class="col-3 col-form-label control-label required text-end">Tytuł</label>
                                    <div class="col-9">
                                        <input type="text" value="{{$task->name}}" class="validate[required] form-control @error('name') is-invalid @enderror" id="inputName" name="name">
                                        @if($errors->first('name'))<div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>@endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputClient" class="col-3 col-form-label control-label required text-end">Klient</label>
                                    <div class="col-9">
                                        <input type="text" class="validate[required] form-control @error('client') is-invalid @enderror" id="inputClient" name="client" autocomplete="off">
                                        @if($task->id)
                                            <input type="hidden" name="task_id" value="{{$task->id}}" id="inputTaskId">
                                        @endif
                                        <input type="hidden" name="stage_id" value="{{$stage_id}}" id="inputStageId">
                                        <input type="hidden" name="client_id" value="0" id="inputClientId">
                                        @if($errors->first('client'))<div class="invalid-feedback d-block">{{ $errors->first('client') }}</div>@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </form>
        </div>
    </div>
</div>