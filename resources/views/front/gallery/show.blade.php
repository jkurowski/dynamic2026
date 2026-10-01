@extends('layouts.page')

@section('meta_title', $gallery->name)

@section('pageheader')
    @include('layouts.partials.developro-header', ['title' => $gallery->name, 'header_file' => 'gallery.jpg', 'items' => [['uri'=> 'galeria', 'title'=>'Galeria']]])
@stop

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center">
            @foreach($gallery->photos as $image)
            <div class="col-6 col-md-4 mb-4">
                <a href="{{asset('uploads/gallery/images/'.$image->file) }}" class="swipebox">
                    <picture>
                        <source type="image/webp" srcset="{{asset('uploads/gallery/images/thumbs/webp/'.$image->file_webp) }}">
                        <source type="image/jpeg" srcset="{{asset('uploads/gallery/images/thumbs/'.$image->file) }}">
                        <img src="{{asset('uploads/gallery/images/thumbs/'.$image->file) }}" alt="{{ $gallery->name }}">
                    </picture>
                </a>
            </div>
            @endforeach
        </div>
        @if($gallery->id == 2)
        <div class="row mt-5">
            <div class="col-12 text-center">
                <h2>WIRTUALNY SPACER</h2>
                <div id="fluidFrame" class="mt-4">
                    <div class="fluidMedia">
                        <iframe src="https://4dl.pl/Panosfery/Madey/Bliski-Olechow/" frameborder="0" width="100%" height="100%"></iframe>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

