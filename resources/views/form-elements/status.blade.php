{{--[
    'label' => 'Label',
    'name' => 'input_name',
    'sublabel' => 'Sub-label'
    'selected' => '$selected'
    'options' => 'array['key'=>'value, 'key'=>'value']'
]--}}
<div class="form-group row">
    <label for="form_{{$name}}" class="col-2 col-form-label control-label">{{$label}}@isset($sublabel)<br><span>{{$sublabel}}</span>@endisset</label>
    <div class="col-10">
        <select id="form_{{$name}}" class="form-control" name="{{$name}}">
            <option value="1">Pokaż na liście</option>
            <option value="0" @if ($selected && $selected == 0) selected @endif>Ukryj na liście</option>
        </select>
    </div>
</div>
