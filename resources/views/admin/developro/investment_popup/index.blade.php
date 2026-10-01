@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-home"></i><a href="{{route('admin.developro.investment.index')}}">Inwestycje</a><span class="d-inline-flex ms-2 me-2">/</span>{{$investment->name}}<span class="d-inline-flex me-2 ms-2">-</span>Dodaj plan inwestycji</h4>
                </div>
            </div>
        </div>
        @include('admin.developro.investment_shared.menu')
        <div class="card mt-3">
            <form method="POST" action="{{route('admin.developro.investment.popup.update', ['investment' => $investment])}}" enctype="multipart/form-data">
                @csrf
                <div class="container-fluid p-4">
                    <div class="row">
                       <div class="col-4">
                           @include('form-elements.html-select', ['label' => 'Status', 'sublabel'=> 'Włącz / wyłącz popup', 'name' => 'popup_status', 'selected' => settings()->get("popup_status"), 'select' => ['0' => 'Wyłączony', '1' => 'Włączony']])
                       </div>
                        <div class="col-4">
                            @include('form-elements.html-select', ['label' => 'Uruchom baner', 'sublabel'=> 'Kiedy popup ma się wyświetlać', 'name' => 'popup_mode', 'selected' => settings()->get("popup_mode"), 'select' => ['0' => 'Tylko raz', '1' => 'Za każdym razem']])
                        </div>
                        <div class="col-4">
                            @include('form-elements.html-input-number', ['label' => 'Czas wyświetlenia', 'sublabel'=> '1000 = 1 sekunda', 'name' => 'popup_timeout', 'value' => settings()->get("popup_timeout")])
                        </div>
                    </div>
                    <div class="row mt-4">
                        @include('form-elements.textarea-fullwidth', ['label' => 'Wprowadź tekst', 'name' => 'popup_text', 'value' => settings()->get("popup_text"), 'rows' => 11, 'class' => 'tinymce'])
                    </div>
                    <div class="form-group form-group-submit">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <input name="submit" id="submit" value="Zapisz" class="btn btn-primary" type="submit">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @include('form-elements.tintmce')
            @push('scripts')
                <script>
                    @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-right",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif
                </script>
            @endpush
        </div>
    </div>
@endsection
