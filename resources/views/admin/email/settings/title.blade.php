<div class="card">
    <div class="card-header">
        <i class="las la-heading"></i> Ustawienia tekstu <button type="button" class="btn-close" aria-label="Close"></button>
    </div>
    <div class="card-body">
        <label for="formControl" class="form-label">Treść</label>
        <input type="text" name="{{ $emailTemplateSection->uuid }}" value="{{ extract_title_content($emailTemplateSection->content) }}" class="form-control" id="formControl">

        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check" name="textAlign" id="textAlignLeft" value="left" autocomplete="off" @if(extract_title_align($emailTemplateSection->content) == "left") checked @endif>
            <label class="btn btn-outline-primary" for="textAlignLeft"><i class="las la-align-left"></i></label>

            <input type="radio" class="btn-check" name="textAlign" id="textAlignCenter" value="center" autocomplete="off" @if(extract_title_align($emailTemplateSection->content) == "center") checked @endif>
            <label class="btn btn-outline-primary" for="textAlignCenter"><i class="las la-align-center"></i></label>

            <input type="radio" class="btn-check" name="textAlign" id="textAlignRight" value="right" autocomplete="off" @if(extract_title_align($emailTemplateSection->content) == "right") checked @endif>
            <label class="btn btn-outline-primary" for="textAlignRight"><i class="las la-align-right"></i></label>

        </div>
    </div>
</div>