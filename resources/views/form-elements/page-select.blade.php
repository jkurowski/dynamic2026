{{--[
    'label' => 'Label',
    'name' => 'input_name',
    'sublabel' => 'Sub-label'
    'selected' => '$selected'
    'options' => 'array['key'=>'value, 'key'=>'value']'
]--}}
<div class="form-group row">
    <label for="form_{{$name}}" class="col-2 col-form-label control-label">
        {{$label}}
        @isset($sublabel)<br>
        <span>{{$sublabel}}</span>
        @endisset
    </label>
    <div class="col-10">
        <select id="form_{{$name}}" class="form-control" name="{{$name}}">
            <option value="0" @if ($selected == 0) selected @endif>Brak</option>
            @foreach ($options as $key => $value)
                <option value="{{ $key }}" @if ($selected == $key) selected @endif>{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>
