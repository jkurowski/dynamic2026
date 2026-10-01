<div class="card">
    <div class="card-header">
        <i class="las la-align-left"></i> Ustawienia treści <button type="button" class="btn-close" aria-label="Close"></button>
    </div>
    <div class="card-body">
        <label for="formControl" class="form-label">Treść</label>
        <textarea name="{{ $emailTemplateSection->uuid }}" class="form-control tinymce" id="formControl" style="height: 400px">{!! extract_text_content($emailTemplateSection->content) !!}</textarea>
    </div>
</div>
