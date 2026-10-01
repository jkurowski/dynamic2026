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
            <div class="card-body card-body-rem">
                <div class="alert alert-info" role="alert">Rzut planu inwestycji: {{ config('images.plan.width') }}px szerokości / {{ config('images.plan.height') }}px wysokości</div>
                @if($investment->plan)
                    <img class="img-fluid rounded" src="/investment/plan/{{$investment->plan->file}}" alt="{{ $investment->name }}">
                @endif
            </div>
        </div>
    </div>
    <div class="form-group form-group-submit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bootstrapmodal">Zmień plan inwestycji</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bootstrapmodal" tabindex="-1" role="dialog" aria-labelledby="uploadlabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadlabel">Dodaj plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fe-x"></i></button>
                </div>
                <div class="modal-body">
                    <div id="jquery-wrapped-fine-uploader"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('/js/fineuploader.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
        $(window).on('shown.bs.modal', function () {
            $('#bootstrapmodal').modal('show');
            $('#jquery-wrapped-fine-uploader').fineUploader({
                debug: true,
                multiple: false,
                text: {
                    uploadButton: "Wybierz plik",
                    dragZone: "Przeciągnij i upuść plik tutaj"
                },
                request: {
                    endpoint: '{{route('admin.developro.investment.plan.index', $investment)}}',
                    customHeaders: {
                        "X-CSRF-Token": $("meta[name='csrf-token']").attr("content")
                    }
                }
            }).on('error', function (event, id, name, reason) {
            }).on('submit', function () {
                fileCount++;
            }).on('complete', function (event, id, name, response) {
                if (response.success === true) {
                    location.reload();
                }
            });
        });
    </script>
    @endpush
@endsection
