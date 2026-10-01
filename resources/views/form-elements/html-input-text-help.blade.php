@php
    if(isset($sublabel)){
        $sublabel = '<span>'.$sublabel.'</span>';
    } else {
        $sublabel = '';
    }
@endphp
@isset($required)
    {!! Form::label($name, '<div class="text-right">'.$label.' <span class="text-danger d-inline">*</span>'.$sublabel.'</div>', ['class' => 'col-12 col-form-label control-label pb-2 required'], false) !!}
@else
    {!! Form::label($name, '<div class="text-right">'.$label.''.$sublabel.'</div>', ['class' => 'col-12 col-form-label control-label pb-2'], false) !!}
@endisset
<div class="@isset($class) {{ $class }} @else {{ 'col-12 control-input position-relative' }} @endisset">
    @isset($value)
        {!! Form::text($name, old($name, $value), ['class' => 'form-control']) !!}
    @else
        {!! Form::text($name, null, ['class' => 'form-control']) !!}
    @endisset
    @isset($help)<div>{!! $help !!}</div>@endisset
    @if($errors->first($name))<div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>@endif
</div>