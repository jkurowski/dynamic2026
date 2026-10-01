<div class="modal fade modal-form" id="portletModal" tabindex="-1" aria-labelledby="portletModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="post" id="modalForm">
                @csrf
                <div class="modal-header">
                    @isset($entry->name)
                    <h5 class="modal-title" id="portletModalLabel">Edytuj kontakt - {{ $entry->name }} {{ $entry->surname }}</h5>
                    @else
                    <h5 class="modal-title" id="portletModalLabel">Dodaj kontakt</h5>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fe-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="modal-form container">
                        <div class="row">
                            <div class="col-12">

                                @include('form-elements.modal.modal-input-text', ['label' => 'Imię', 'sublabel'=> '', 'name' => 'name', 'required' => 1, 'value' => $entry->name])
                                @include('form-elements.modal.modal-input-text', ['label' => 'Nazwisko', 'sublabel'=> '', 'name' => 'surname', 'required' => 1, 'value' => $entry->surname])
                                @include('form-elements.modal.modal-input-text', ['label' => 'E-mail', 'sublabel'=> '', 'name' => 'email', 'required' => 1, 'value' => $entry->email])
                                @include('form-elements.modal.modal-input-text', ['label' => 'Telefon 1', 'sublabel'=> '', 'name' => 'phone_1', 'required' => 1, 'value' => $entry->phone_1])
                                @include('form-elements.modal.modal-input-text', ['label' => 'Telefon 2', 'sublabel'=> '', 'name' => 'phone_2', 'value' => $entry->phone_2])

                                <div class="form-group row">
                                    <label for="inputNote" class="col-3 col-form-label control-label required text-end">Notatka</label>
                                    <div class="col-9">
                                        <textarea name="note" cols="30" rows="5"
                                                  class="form-control @error('note') is-invalid @enderror"
                                                  id="inputNote" style="resize: none">@isset($entry->note) {{$entry->note}} @endisset</textarea>
                                        @if($errors->first('note'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('note') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="" id="inputEntryId" value="{{ $entry->id }}">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </form>
        </div>
    </div>
</div>
