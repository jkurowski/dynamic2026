{{--[
    'label' => 'Label',
    'name' => 'input_name',
    'value' => $form->value,
    'rows' => $rows,
    'cols' => $cols,
    'class' => $class,
    'sublabel' => 'Sub-label'
]--}}
<label for="form_{{$name}}" class="col-12 col-form-label control-label pb-2">
    <div class="text-start w-100">
        {!! $label !!} @isset($required) <span class="text-danger d-inline">*</span>@endisset @isset($sublabel)<br><span>{!! $sublabel !!}</span>@endisset
    </div>
</label>
<div class="col-12 control-input d-flex align-items-center">
    <textarea class="form-control @isset($class){{$class}}@endisset @error($name) is-invalid @enderror w-100" id="form_{{$name}}" name="{{$name}}" @isset($rows)rows="{{$rows}}"@endisset @isset($cols)cols="{{$cols}}"@endisset>{{ old($name, $value) }}</textarea>
    @if($errors->first($name))<div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>@endif
</div>