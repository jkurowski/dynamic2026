@php
    $subLabel = isset($sublabel) ? '<span>' . $sublabel . '</span>' : '';
    $labelClass = 'col-12 col-form-label control-label pb-2';
    $divClass = $class ?? 'col-12 control-input position-relative d-flex align-items-center';
    $required = isset($required) && $required;
    $readonly = $readonly ?? false;

    $additionalAttributes = [];
    if (isset($pattern)) {
        $additionalAttributes['pattern'] = $pattern;
    }
@endphp

@if($label)
{!! Form::label(
    $name,
    '<div class="text-start">' . $label . ($required ? ' <span class="text-danger d-inline">*</span>' : '') . $subLabel . '</div>',
    ['class' => $labelClass . ($required ? ' required' : '')],
    false
) !!}
@endif

<div class="{{ $divClass }}">
    @foreach($options as $optionValue => $optionLabel)
        <div class="form-check form-check-label col-4">
            {!! Form::radio(
                $name,
                $optionValue,
                old($name, $value) == $optionValue,
                array_merge(
                    ['class' => 'form-check-input', 'id' => $name . '_' . $optionValue],
                    ($required ? ['required' => 'required'] : []),
                    $additionalAttributes,
                    (($readonly == true) ? ['disabled' => 'disabled'] : [])
                )
            ) !!}
            {!! Form::label($name . '_' . $optionValue, $optionLabel, ['class' => 'form-check-label']) !!}
        </div>
    @endforeach
</div>

@if($errors->first($name))
    <div class="col-12 control-input invalid-feedback d-block">{{ $errors->first($name) }}</div>
@endif
