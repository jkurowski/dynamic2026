@php
    $subLabel = isset($sublabel) ? '<span>' . $sublabel . '</span>' : '';
    $labelClass = 'col-12 col-form-label control-label pb-2';
    $inputClass = 'form-control';
    $divClass = $class ?? 'col-12 control-input position-relative d-flex align-items-center';
    $required = isset($required) && $required;
@endphp

{!! Form::label(
    $name,
    '<div class="text-right">' . $label . ($required ? ' <span class="text-danger d-inline">*</span>' : '') . $subLabel . '</div>',
    ['class' => $labelClass . ($required ? ' required' : '')],
    false
) !!}

<div class="{{ $divClass }}">
    {!! Form::number($name, old($name, $value), ['class' => $inputClass, 'step' => 100]) !!}
    @if($errors->first($name))
        <div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>
    @endif
</div>