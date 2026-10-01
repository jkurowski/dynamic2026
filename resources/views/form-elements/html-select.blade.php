<label for="form_{{ $name }}" class="col-12 col-form-label control-label pb-2">
    <div class="text-start w-100">{!! $label !!}
        @isset($required)
            <span class="text-danger d-inline">*</span>
        @endisset
        @isset($sublabel)
            <br><span>{!! $sublabel !!}</span>
        @endisset
    </div>
</label>

<div class="col-12 control-input position-relative d-flex align-items-center flex-column">
    @if(isset($selected))
        {!! Form::select($name, $select, $selected, array('class' => 'form-select', 'id' => $name.'Select', $disabled ?? null)) !!}
    @else
        {!! Form::select($name, $select, [], array('class' => 'form-select', 'id' => $name.'Select', $disabled ?? null)) !!}
    @endif
    @if($errors->first($name))<div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>@endif
</div>