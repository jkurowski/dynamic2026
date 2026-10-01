@php
    $subLabel = '';
    if(isset($sublabel)){
        $subLabel = '<span>'.$sublabel.'</span>';
    }
@endphp
@isset($required)
    {!! Form::label($name, '<div class="text-start">'.$label.' <span class="text-danger d-inline">*</span>'.$subLabel.'</div>', ['class' => 'col-12 col-form-label control-label pb-2 required'], false) !!}
@else
    {!! Form::label($name, '<div class="text-start">'.$label.$subLabel.'</div>', ['class' => 'col-12 col-form-label control-label pb-2'], false) !!}
@endisset
<div class="col-12 control-input position-relative d-flex align-items-center">
    <div class="input-group">
        <span class="input-group-text" id="basic-addon1"><i class="las la-calendar"></i></span>
        @isset($value)
            {!! Form::text($name, old($name, $value), ['class' => 'form-control datepicker']) !!}
        @else
            {!! Form::text($name, null, ['class' => 'form-control datepicker']) !!}
        @endisset
        @if($errors->first($name))
            <div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>
        @endif
    </div>
</div>