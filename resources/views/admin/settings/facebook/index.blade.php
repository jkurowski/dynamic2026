@extends('admin.settings.index')

@section('settings')
    <form id="facebook" method="POST" action="{{route('admin.settings.facebook.store')}}">
        @csrf
        <div class="container-fluid p-0">
            <div class="card p-4">
                <div class="card-body p-3 control-col12">

                    <div class="row w-100 form-group">
                        @include('form-elements.html-input-text', ['label' => 'Facebook App ID', 'name' => 'fb_app_id', 'value' => settings()->get("fb_app_id")])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.html-input-password', ['label' => 'Facebook App Secret', 'name' => 'fb_app_secret', 'value' => settings()->get("fb_app_secret")])
                    </div>

                    <div class="row w-100 form-group">
                        <label for="fb_app_secret" class="col-12 col-form-label control-label pb-2">
                            <div class="text-right">Podpięte aplikacje</div>
                        </label>
                        <div class="col-12 control-input">
                            @if($list->count() == 0)
                                <a href="{{ route('redirectToProvider') }}" class="btn btn-primary">Podłącz aplikację</a>
                            @else
                                <ul class="list-group list-group-flush">
                                    @foreach($list as $page)
                                        <li class="list-group-item pe-0 ps-0 d-flex">
                                            <div class="fbpage-photo">
                                                <img src="{{asset('uploads/facebook/'.$page->page_id.'.jpg') }}" alt="{{ $page->name }}">
                                            </div>
                                            <div class="fbpage-option">
                                                <p class="mb-2"><b>{{ $page->name }}</b> (id: {{ $page->page_id }})</p>
                                                <input type="text" class="form-control mb-2" value="{{ $page->access_token }}" readonly>
                                                <a href="https://www.facebook.com/{{ $page->page_id }}" target="_blank"><i class="fe-external-link"></i></a>
                                                <a href="{{ route('facebook.page.delete', $page->access_token) }}"><i class="fe-trash"></i></a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
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
    @push('scripts')
        <script>
            @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-right",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif
        </script>
    @endpush
@endsection

