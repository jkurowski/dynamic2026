@php
    $sublabel = isset($sublabel) ? '<span>' . $sublabel . '</span>' : '';
    $labelClass = 'col-12 col-form-label control-label pb-2';
    $inputClass = 'form-control noresize';
    $divClass = $class ?? 'col-12 control-input position-relative d-flex align-items-center';
    $required = isset($required) && $required;
@endphp

{!! Form::label(
    $name,
    '<div class="text-right">' . $label . ($required ? ' <span class="text-danger d-inline">*</span>' : '') . $sublabel . '</div>',
    ['class' => $labelClass . ($required ? ' required' : '')],
    false
) !!}

<div class="{{ $divClass }}">
    {!! Form::textarea($name, old($name, $value), ['class' => $inputClass, 'rows' => $rows, 'cols' => $cols]) !!}
    @if($errors->first($name))
        <div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>
    @endif
</div>