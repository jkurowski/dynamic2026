<label for="{{$name}}" class="col-12 col-form-label control-label pb-2">{{$label}}@isset($sublabel)<br><span>{{$sublabel}}</span>@endisset</label>
<div class="col-12 control-input position-relative d-flex align-items-center">
    <textarea class="form-control @isset($class){{$class}}@endisset" id="{{$name}}" name="{{$name}}" @isset($rows)rows="{{$rows}}"@endisset @isset($cols)cols="{{$cols}}"@endisset>{{ $value }}</textarea>
</div>
