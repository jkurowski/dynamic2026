@extends('layouts.page', ['body_class' => 'gallery'])

@section('meta_title', $page->title)
@section('seo_title', $page->meta_title)
@section('seo_description', $page->meta_description)

@section('pageheader')
    @include('layouts.partials.page-header', ['page' => $page, 'header_file' => 'gallery.jpg'])
@stop

@section('content')
<div id="gallery">
    <div class="gallery-carousel">
        @foreach($galleries as $gallery)
        <div class="gallery-item">
            <div class="gallery-catalog">
                <a href="{{ route('front.gallery.show', $gallery->slug) }}">
                    <div>
                        <h2>{{ $gallery->name }}</h2>
                        <span class="bttn">ZOBACZ</span>
                    </div>
                    <picture>
                        <source type="image/webp" srcset="{{asset('uploads/gallery/webp/'.$gallery->file_webp) }}">
                        <source type="image/jpeg" srcset="{{asset('uploads/gallery/'.$gallery->file) }}">
                        <img src="{{asset('uploads/gallery/'.$gallery->file) }}" alt="{{ $gallery->name }}">
                    </picture>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('/js/slick.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.gallery-carousel').slick({
                centerPadding: '300px',
                centerMode: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1380,
                        settings: {
                            centerPadding: '200px',
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            centerPadding: '100px',
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            centerPadding: '0px',
                            centerMode: false,
                        }
                    },
                ]
            })
        });
    </script>
@endpush
