@extends('layouts.page', ['body_class' => 'homepage'])

@section('meta_title', '$page->title')
@section('seo_title','$page->meta_title')
@section('seo_description', '$page->meta_description')
@section('seo_robots', '$page->meta_robots')

@section('content')
    <main class="with-bigger-section-spacing">

    @include('layouts.partials.page-header', ['h1' => 'Podstrona', 'desc' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor.', 'header' => 'img/kredyty_bg.webp'])



    </main>
@endsection
@push('scripts')

@endpush
