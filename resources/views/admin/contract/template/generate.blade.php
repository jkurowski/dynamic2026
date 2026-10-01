@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    <form method="POST" action="{{ route('admin.contract.template.generate', $entry) }}" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="card-head container">
                <div class="row">
                    <div class="col-12 pl-0">
                        <h4 class="page-title"><i class="fe-book-open"></i><a href="{{route('admin.contract.index')}}" class="p-0">Generator dokumentów</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                @include('form-elements.back-route-button')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @include('form-elements.html-input-text', ['label' => 'Numer dokumentu', 'name' => 'number', 'value' => convertRegex2Date($entry->numbering), 'required' => 1])
                            @foreach($placeholders as $p => $pp)
                                @include('form-elements.html-input-'.$pp['type'], ['label' => $pp['form'], 'name' => $pp['placeholder'], 'value' => '', 'required' => $pp['required'] ? 1 : 0])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('form-elements.submit', ['name' => 'submit', 'value' => 'Generuj'])
    </form>
@endsection
@push('scripts')
    <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
    <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <script>
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            language: "pl"
        });
    </script>
@endpush
