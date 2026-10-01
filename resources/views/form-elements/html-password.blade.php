@isset($required)
    {!! Form::label($name, '<div class="text-right">'.$label.' <span class="text-danger d-inline">*</span></div>', ['class' => 'col-12 col-form-label control-label pb-2 required'], false) !!}
@else
    {!! Form::label($name, '<div class="text-right">'.$label.' <span class="text-danger d-inline">*</span></div>', ['class' => 'col-12 col-form-label control-label pb-2'], false) !!}
@endisset
<div class="@isset($class) {{ $class }} @else {{ 'col-12 control-input position-relative d-flex align-items-center' }} @endisset">
    {!! Form::password($name, array('class' => 'form-control', 'autocomplete' => 'new-password')) !!}
    @if($errors->first($name))<div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>@endif
</div>
