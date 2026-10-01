@if($list->count() > 0)
    <div id="photos-list" class="container">
        <div class="row justify-content-center">
            @foreach ($list as $p)
                <div class="col-6 col-md-4 col-xl-3 p-0">
                    <div class="col-gallery-thumb m-2">
                        <a href="/uploads/gallery/images/{{$p->file}}" class="glightbox" rel="gallery-1" title="">
                            <picture>
                                <source type="image/jpeg" srcset="{{asset('uploads/gallery/images/thumbs/'.$p->file) }}">
                                <img src="{{asset('uploads/gallery/images/thumbs/'.$p->file) }}" alt="{{ $p->name }}" class="img-fluid">
                            </picture>
                            <div><i class="las la-search-plus"></i></div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
