<ul class="mb-0 list-unstyled side-menu">
    @foreach($pages as $page)
        <li @if($page->children->count() > 0) class="gotsub" @endif>
            @if($page->url)
                <a href="{{$page->url}}"@if($page->url_target) target="{{$page->url_target}}"@endif class="{{ (request()->is($page->uri)) ? 'active' : '' }}">{{$page->title}}</a>
            @else
                <a href="/{{$page->uri}}"@if($page->url_target) target="{{$page->url_target}}"@endif class="{{ (request()->is($page->uri)) ? 'active' : '' }}">{{$page->title}}</a>
            @endif
            @if($page->children->count() > 0)
                @include('layouts.partials.submenu', array('pages' => $page->children))
            @endif
        </li>
    @endforeach
</ul>
