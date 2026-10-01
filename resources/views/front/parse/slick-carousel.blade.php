@if($list->count() > 0)
    <div class="slick-carousel slick-carousel-text">
        @foreach ($list as $p)
            <div class="col-gallery-thumb mb-0">
                <a href="/uploads/gallery/images/{{$p->file}}" class="glightbox" rel="gallery-1" title="">
                    <picture>
                        <source type="image/jpeg" srcset="{{asset('uploads/gallery/images/'.$p->file) }}">
                        <img src="{{asset('uploads/gallery/images/'.$p->file) }}" alt="{{ $p->name }}" width="520" height="293">
                    </picture>
                    <div><i class="las la-search-plus"></i></div>
                </a>
            </div>
        @endforeach
    </div>
@endif
@push('scripts')
    <script src="{{ asset('js/slick.min.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".slick-carousel").slick({
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                centerMode: true,
                centerPadding: '90px',
                arrows: false,
                dots: true,
                autoplay: true,
                autoplaySpeed: 3000,
                responsive: [
                    {
                        breakpoint: 990,
                        settings: {
                            slidesToShow: 2,
                            centerPadding: '20px',
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            centerMode: false,
                            centerPadding: '0px',
                        }
                    }
                ]
            });
        });
    </script>
@endpush