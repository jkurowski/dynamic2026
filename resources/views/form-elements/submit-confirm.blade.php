{{--[
    'name' => 'input_name',
    'value' => 'Button name'
]--}}
<div class="form-group form-group-submit">
    <div class="container">
        <div class="row">
            <div class="col-12 d-flex justify-content-end align-items-center">
                <div class="alert alert-danger mb-0 me-2" role="alert">
                    Jestem świadomy obowiązku poinformowania klientów o zmianie treści regułki
                </div>
                <input type="checkbox" name="agree" id="" class="validate[required] me-5" data-prompt-position="topLeft:0">
                <input name="{{$name}}" id="submit" value="{{$value}}" class="btn btn-primary" data-id="{{ $entry->id }}" type="submit">
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('js/validation.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/pl.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".validateForm").validationEngine({
                validateNonVisibleFields: true,
                updatePromptsPosition:true,
                promptPosition : "topRight:-137px",
                autoPositionUpdate: false
            });
        });
    </script>
@endpush
