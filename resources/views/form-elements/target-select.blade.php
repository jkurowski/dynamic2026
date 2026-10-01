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
            <option value="" @if (!$selected) selected @endif>Ta samo okno</option>
            <option value="_blank" @if ($selected == '_blank') selected @endif>Nowe okno</option>
            <option value="_self" @if ($selected == '_self') selected @endif>Link w domenie</option>
        </select>
    </div>
</div>
