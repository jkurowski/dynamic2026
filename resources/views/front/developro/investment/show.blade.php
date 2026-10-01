@extends('layouts.page', ['body_class' => 'investments'])

@section('meta_title', 'Inwestycje - '.$investment->name)
@if($page)
    @section('seo_title', $page->meta_title)
    @section('seo_description', $page->meta_description)
    @section('pageheader')
        @include('layouts.partials.page-header', ['page' => $page, 'header_file' => 'rooms.jpg', 'heading' => $investment->name])
    @stop
@endif

@section('content')
<div class="container">
    <div class="row">
        <div class="col-2">
            @include('front.developro.investment_shared.menu')
        </div>
        <div class="col-10">
            {!! $investment->content !!}
        </div>
    </div>
</div>
@endsection
@push('scripts')

@endpush
