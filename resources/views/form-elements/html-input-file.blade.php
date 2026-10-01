@php
    if(isset($sublabel)){
        $sublabel = '<span>'.$sublabel.'</span>';
    } else {
        $sublabel = '';
    }
@endphp
@isset($required)
    {!! Form::label($name, '<div class="text-right">'.$label.' <span class="text-danger d-inline">*</span><span>'.$sublabel.'</span></div>', ['class' => 'col-12 col-form-label control-label pb-2 required'], false) !!}
@else
    {!! Form::label($name, '<div class="text-right">'.$label.$sublabel.'</div>', ['class' => 'col-12 col-form-label control-label pb-2'], false) !!}
@endisset
<div class="@isset($class) {{ $class }} @else {{ 'col-12 control-input position-relative d-flex align-items-center' }} @endisset">
    @isset($required)
        {!! Form::file($name, ['required' => true, 'class' => 'form-control']) !!}
    @else
        {!! Form::file($name, ['class' => 'form-control']) !!}
    @endisset
    @if($errors->first($name))<div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>@endif
</div>
@isset($file, $file_preview)
    <div class="col-4 offset-3">
        <div class="file-preview">
            <img src="{{ asset($file_preview.$file) }}" @isset($file_preview_style) style="{{ $file_preview_style }}" @endisset alt="Podgląd obrazka">
        </div>
    </div>
@endisset