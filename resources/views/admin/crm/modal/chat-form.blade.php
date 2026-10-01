<div class="modal fade modal-form" id="portletModal" tabindex="-1" aria-labelledby="portletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form action="" method="post" id="modalForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="portletModalLabel">Dodaj odpowiedź</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"><i class="fe-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="modal-form container p-0">
                        <div class="alert alert-danger" style="display:none"></div>
                        @include('form-elements.textarea-fullwidth', ['label' => 'Treść', 'name' => 'message', 'value' => '', 'rows' => 11, 'class' => 'tinymce', 'required' => 1])

                        <div class="row">
                            <label for="attachment" class="col-3 col-form-label control-label required">
                                <div class="text-right">Wybierz załącznik <span class="text-danger d-inline">*</span></div>
                            </label>
                            <div class="col-9">
                                <div class="d-flex h-100 align-items-center">
                                    <button class="btn btn-primary pe-4 ps-4" type="button" id="button-browser" data-dismiss="modal" data-bs-toggle="modal" data-bs-target="#AttachmentFileModal">Przeglądaj pliki</button>
                                    <input class="form-control" name="attachment" type="hidden" id="attachment">
                                </div>
                            </div>
                            <div class="col-9 offset-3">
                                <ul id="fileList" class="list-unstyled mb-0"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                    <button type="submit" class="btn btn-primary" id="submit">Wyślij</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="AttachmentFileModal" tabindex="-1" aria-labelledby="AttachmentFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="AttachmentFileModalLabel">Przeglądaj pliki</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"><i class="fe-x"></i></button>
            </div>
            <div class="modal-body">
                <iframe
                    id="filemanager"
                    width="100%"
                    height="550"
                    style="border:0"
                    src="/js/editor/plugins/filemanager/dialog.php?type=2&field_id=attachment&lang=pl_PL"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/js/editor/tinymce.min.js') }}" charset="utf-8" class="modal-script"></script>
<script class="modal-script">
    function initTinyMCE(selector){
        tinymce.init({
            selector: selector,
            language: "pl",
            skin: "oxide",
            content_css: 'default',
            branding: false,
            menubar:false,
            statusbar: false,
            toolbar_location: 'bottom',
            height: 400,
            toolbar1: "bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | superscript subscript | numlist bullist"
        });
    }
    const modal = document.getElementById('portletModal'),
        attachment = document.getElementById('AttachmentFileModal'),
        attachmentInput = document.getElementById('attachment'),
        form = document.getElementById('modalForm'),
        button = document.getElementById('submit'),
        message = document.getElementById('form_message'),
        alert = $('.alert-danger');


    const fileModal = new bootstrap.Modal(modal);
    fileModal.show();

    modal.addEventListener('shown.bs.modal', event => {
        initTinyMCE(".tinymce");
        console.log('shown.bs.modal');
    })

    attachment.addEventListener('shown.bs.modal', event => {
        jQuery("#multiple-selection").hide(300);

        const frame = document.getElementById('filemanager');
        const checkboxes = frame.contentWindow.document.getElementsByClassName('selection');
        const menu = frame.contentWindow.document.getElementById('multiple-selection');
        for (let i = 0; i < checkboxes.length; i++)
        {
            checkboxes[i].checked = false;
        }
        menu.style.display = 'none';

        console.log('attachment.show.bs.modal');
    })

    attachment.addEventListener('hidden.bs.modal', event => {
        fileModal.show();
        console.log('attachment.hide.bs.modal');
    })

    function tryParseJSONObject(jsonString){
        try {
            const o = JSON.parse(jsonString);
            if (o && typeof o === "object") {
                return o;
            }
        }
        catch (e) { }

        return JSON.parse('["'+jsonString+'"]');
    }

    function responsive_filemanager_callback(field_id){
        const url = jQuery('#' + field_id).val();
        const array = tryParseJSONObject(url);
        const ul = document.getElementById("fileList");
        array.forEach((file) => {
            const filename = file.substring(file.lastIndexOf('/') + 1);
            ul.innerHTML += '<li>'+ filename +'</li>';
        });
    }

    form.addEventListener('submit', (e)=> {
        e.preventDefault();
        e.stopImmediatePropagation();
        tinymce.triggerSave();

        button.disabled = true;
        button.classList.add('btn-loading');
        button.innerHTML = '<span class="spinner-button"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></span> Wysyłam';

        $.ajax({
            url: '{{ route('admin.crm.clients.chat.show', $client) }}',
            method: 'POST',
            async: false,
            data: {
                '_token': '{{ csrf_token() }}',
                'message': message.value,
                'id': {{ $id }}
            },
            success: function() {
                fileModal.hide();
                location.reload()
            },
            error : function(result){
                if(result.responseJSON.errors)
                {
                    alert.html('');
                    $.each(result.responseJSON.errors, function(key, value){
                        alert.show();
                        alert.append('<span>'+value+'</span>');
                    });
                    button.disabled = false;
                    button.classList.remove('btn-loading');
                    button.innerHTML = 'Wyślij';
                }
            }
        });
    });
</script>
