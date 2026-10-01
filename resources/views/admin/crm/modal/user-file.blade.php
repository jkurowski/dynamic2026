<div class="modal fade modal-form" id="portletModal" tabindex="-1" aria-labelledby="portletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="post" id="modalForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="portletModalLabel">Opis pliku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"><i class="fe-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="modal-form container">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="inputDescription" class="col-3 col-form-label control-label required text-end">Opis </label>
                                    <div class="col-9">
                                        <textarea name="note" cols="30" rows="5" class="form-control @error('description') is-invalid @enderror" id="inputDescription" style="resize: none">{{$entry->description}}</textarea>
                                        @if($errors->first('description'))<div class="invalid-feedback d-block">{{ $errors->first('description') }}</div>@endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="container p-0 m-0">
                        <div class="row m-0">
                            <div class="col-6 p-0">
                                @if($entry->description)
                                    <button type="button" class="btn btn-secondary m-0" data-file-id="{{$entry->id}}" id="deleteButton">Usuń opis</button>
                                @endif
                            </div>
                            <div class="col-6 p-0 d-flex justify-content-end">
                                <input type="hidden" name="file_id" value="{{$entry->id}}" id="inputFileId">
                                <button type="button" class="btn btn-secondary me-1" data-bs-dismiss="modal">Zamknij</button>
                                <button type="submit" class="btn btn-primary">Zapisz</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const modal = document.getElementById('portletModal'),
        userFileModal = new bootstrap.Modal(modal),
        form = document.getElementById('modalForm'),
        files = document.getElementById('files'),
        inputDescription = $('#inputDescription'),
        inputFileId = $('#inputFileId'),
        alert = $('.alert-danger');

    userFileModal.show();
    modal.addEventListener('hidden.bs.modal', event => {
        modal.remove();
    })

    form.addEventListener('submit', (e)=> {
        e.preventDefault();
        $.ajax({
            url: route('admin.crm.clients.file.desc.store', inputFileId.val()),
            method: 'POST',
            async: false,
            data: {
                '_token': '{{ csrf_token() }}',
                'description': inputDescription.val()
            },
            success: function(result) {
                if (result.action === 'updated') {

                    const parent = files.querySelector('.file[data-file-id="' + result.id + '"]')
                    const p = parent.querySelector(".noteItemText p");
                    const button = parent.querySelector(".dropdown-item-addtext");

                    p.innerHTML = result.description;
                    button.innerHTML = 'Edytuj opis';

                    toastr.options =
                        {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                    toastr.info("Zaktualizowano opis pliku");

                }
                userFileModal.hide();
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

    @if($entry->description)
    $('.modal-footer').on('click', '#deleteButton', function(event){
        const id = event.target.dataset.fileId;
        $.confirm({
            title: "Potwierdzenie usunięcia",
            message: "Czy na pewno chcesz usunąć?",
            buttons: {
                Tak: {
                    "class": "btn btn-primary",
                    action: function() {
                        $.ajax({
                            url: route('admin.crm.clients.file.desc.destroy', id),
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {

                                const parent = files.querySelector('.file[data-file-id="' + id + '"]')
                                const p = parent.querySelector(".noteItemText p");
                                const button = parent.querySelector(".dropdown-item-addtext");

                                p.innerHTML = '';
                                button.innerHTML = 'Dodaj opis';

                                toastr.options =
                                    {
                                        "closeButton" : true,
                                        "progressBar" : true
                                    }
                                toastr.success("Opis pliku został poprawnie usunięty");

                                userFileModal.hide();
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
    @endif
</script>
