@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    @if(Route::is('admin.developro.investment.payments.edit'))
        <form method="POST" action="{{route('admin.developro.investment.payments.update', [$investment, $entry])}}" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form method="POST" action="{{route('admin.developro.investment.payments.store', $investment)}}" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="container">
                        <div class="card-head container">
                            <div class="row">
                                <div class="col-12 pl-0">
                                    <h4 class="page-title"><i class="fe-home"></i><a href="{{route('admin.developro.investment.index')}}">Inwestycje</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{route('admin.developro.investment.payments', $investment)}}">{{$investment->name}}: Harmonogram</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            @include('form-elements.back-route-button')
                            <div class="card-body control-col12">
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Wartość w %', 'name' => 'amount', 'value' => $entry->amount, 'required' => 1])
                                </div>
                                 <div class="row w-100 form-group">
                                    @include('form-elements.html-input-date', ['label' => 'Termin', 'sublabel'=> '', 'name' => 'due_date', 'value' => $entry->due_date])
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="investment_id" value="{{$investment->id}}">
                    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                </form>
                @include('form-elements.tintmce')
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
