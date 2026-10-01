<div class="form-group row">
    <label
            for="input{{ $name }}"
            class="col-3 col-form-label control-label @isset($required) required @endisset text-end">{{ $label }}
            @isset($required) <span class="text-danger d-inline w-auto ps-1">*</span> @endisset
    </label>
    <div class="col-9">
        <input
                type="text"
                class="@isset($required) validate[required] @endisset form-control @error('name') is-invalid @enderror"
                id="input{{ $name }}"
                name="{{ $name }}"
                @isset($value)
                    value="{{$value}}"
                @endisset
                @isset($required) required @endisset
        >
        @if($errors->first($name))
            <div class="invalid-feedback d-block">
                {{ $errors->first($name) }}
            </div>
        @endif
    </div>
</div>