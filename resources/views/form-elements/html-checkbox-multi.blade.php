@php
    $subLabel = isset($sublabel) ? '<span>' . $sublabel . '</span>' : '';
    $labelClass = 'col-12 col-form-label control-label pb-2';
    $inputClass = 'form-check-input me-2'; // Bootstrap style for checkboxes
    $divClass = $class ?? 'col-12 control-input position-relative';
    $required = isset($required) && $required;
    $readonly = isset($readonly) && $readonly;

    $selected = old($name, $value ?? []);
    $selected = is_array($selected) ? $selected : explode(',', $selected); // Ensure it's an array
@endphp

{!! Form::label(
    $name,
    '<div class="text-start">' . $label . ($required ? ' <span class="text-danger d-inline">*</span>' : '') . $subLabel . '</div>',
    ['class' => $labelClass . ($required ? ' required' : '')],
    false
) !!}

<div class="{{ $divClass }}">
    @foreach($options as $optionValue => $optionLabel)
        <div class="form-check form-check-inline align-items-center mb-0 d-flex align-items-center">
            <label class="form-check-label">
                {!! Form::checkbox(
                    $name . '[]',
                    $optionValue,
                    in_array($optionValue, $selected),
                    [
                        'class' => $inputClass,
                        $readonly ? 'disabled' : null,
                    ]
                ) !!}
                {{ $optionLabel }}
            </label>
        </div>
    @endforeach
</div>

@if($errors->first($name))
    <div class="col-12 col-form-label control-label pb-2"></div>
    <div class="col-12 control-input invalid-feedback d-block">{{ $errors->first($name) }}</div>
@endif
