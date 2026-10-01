<div class="row">
    @php
        if(isset($sublabel)){
            $sublabel = '<span>'.$sublabel.'</span>';
        } else {
            $sublabel = '';
        }
    @endphp
    {!! Form::label($name, '<div class="text-right">'.$label.' <span class="text-danger d-inline">*</span>'.$sublabel.'</div>', ['class' => 'col-3 col-form-label control-label required'], false) !!}
    <div class="@isset($class) {{ $class }} @else {{ 'col-4' }} @endisset">
        <div class="input-group">
            <button class="btn btn-primary" type="button" id="button-browser" data-bs-toggle="modal" data-bs-target="#{{$name}}FileModal">Pliki</button>
            @isset($value)
                {!! Form::text($name, old($name, $value), ['class' => 'form-control']) !!}
            @else
                {!! Form::text($name, null, ['class' => 'form-control']) !!}
            @endisset
            @if($errors->first($name))<div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>@endif
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="{{$name}}FileModal" tabindex="-1" aria-labelledby="{{$name}}FileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{$name}}FileModalLabel">Przeglądaj pliki</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"><i class="fe-x"></i></button>
            </div>
            <div class="modal-body">
                <iframe
                    width="100%"
                    height="550"
                    style="border:0"
                    src="/js/editor/plugins/filemanager/dialog.php?type=2&field_id={{$name}}&relative_url=1&lang=pl_PL"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        window.closeModal = function(){
            $('.modal').modal('hide');
            console.log('closeModal');
        };
    </script>
@endpush

