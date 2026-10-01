@extends('layouts.page', ['body_class' => 'homepage'])

@section('meta_title', $page->title)
@section('seo_title',$page->meta_title)
@section('seo_description', $page->meta_description)
@section('seo_robots', $page->meta_robots)

@section('content')
    <main class="with-bigger-section-spacing page-{{ $uri }}">

        @include('layouts.partials.page-header', ['h1' => $page->title, 'desc' => $page->title_text,  'header' => 'img/kredyty_bg.webp'])

        {!! parse_text($page->content, true) !!}
    </main>
@endsection
@push('scripts')

@endpush
