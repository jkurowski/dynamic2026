@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-image"></i><a href="{{route('admin.gallery.index')}}">Galeria</a><span class="d-inline-flex me-2 ms-2">/</span>{{$gallery->name}}</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                    <button data-bs-toggle="modal" data-bs-target="#bootstrapmodal" class="btn btn-primary">Dodaj zdjęcie</button>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="table-overflow">
                <div id="jqalert"></div>
                @if (session('success'))
                    <div class="alert alert-success border-0 mb-0">
                        {{ session('success') }}
                        <script>setTimeout(function(){$(".alert").slideUp(500,function(){$(this).remove()})},3000)</script>
                    </div>
                @endif
                @if($gallery->photos->count() > 0)
                <div class="container-fluid container-gallery">
                    <ul id="sortable" class="mb-0 list-unstyled clearfix">
                        @foreach ($gallery->photos as $index => $p)
                            <li id="recordsArray_{{ $p->id }}">
                                <div class="card thumb-card">
                                    <img class="img-fluid" src="/uploads/gallery/images/thumbs/{{$p->file}}" alt="{{ $p->name }}">
                                    <div class="card-body">
                                        <div class="btn-group">
                                            <a href="{{route('admin.image.edit', $p->id)}}" class="btn action-button action-small me-1" data-toggle="tooltip" data-placement="top" title="Edytuj zdjęcie"><i class="fe-edit"></i></a>
                                            <a href="" class="btn action-button move-button action-small me-1"><i class="fe-move"></i></a>
                                            <form method="POST" action="{{route('admin.image.destroy', $p->id)}}">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn action-button action-small confirm" data-toggle="tooltip" data-placement="top" data-id="{{ $p->id }}" title="Usuń zdjęcie"><i class="fe-trash-2"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="form-group form-group-submit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <button data-bs-toggle="modal" data-bs-target="#bootstrapmodal" class="btn btn-primary">Dodaj zdjęcie</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bootstrapmodal" tabindex="-1" role="dialog" aria-labelledby="uploadlabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadlabel">Dodaj zdjęcia</h5>
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
@endsection
@push('scripts')
    <script src="{{ asset('/js/fineuploader.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
        //<![CDATA[
        $(document).ready(function(){
            $("#sortable").sortujGal('{{route('admin.image.sort')}}');
        });
        //]]>
        $(window).on('shown.bs.modal', function () {
            $('#bootstrapmodal').modal('show');
            let fileCount = 0;
            $('#jquery-wrapped-fine-uploader').fineUploader({
                debug: true,
                multiple: true,
                text: {
                    uploadButton: "Wybierz plik",
                    dragZone: "Przeciągnij i upuść plik tutaj"
                },
                request: {
                    endpoint: '{{route('admin.image.store')}}',
                    customHeaders: {
                        "X-CSRF-Token": $("meta[name='csrf-token']").attr("content")
                    },
                    params: {
                        gallery: "{{$gallery->id}}"
                    }
                }
            }).on('error', function (event, id, name, reason) {
            }).on('submit', function () {
                fileCount++;
            }).on('complete', function (event, id, name, response) {
                if (response.success === true) {
                    fileCount--;
                    if (fileCount === 0) {
                        location.reload();
                    }
                }
            });
        });
    </script>
@endpush
