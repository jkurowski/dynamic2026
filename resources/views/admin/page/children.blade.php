@foreach ($pages as $page)
<tr>
    <td><i class="fe-corner-down-right" style="margin-left: {{$page->depth * 10}}px;margin-right: 15px"></i> {{$page->title}}</td>
    <td class="text-center">{!! status($page->active) !!}</td>
    <td class="text-center">{!! page_type($page->type) !!}</td>
    <td>
        @if($page->type == 1)
            @foreach($page->ancestors->pluck('title') as $path){{$path}} <i class="fe-chevrons-right"></i> @endforeach{{$page->title}}
        @else
            {{$page->url}}@if($page->url_target) ({{$page->url_target}})@endif
        @endif
    </td>
    <td class="text-center">{{$page->created_at->format('Y-m-d H:i')}}</td>
    <td class="option-120">
        <div class="btn-group">
            @if($page->type == 1)
                <a href="{{ settings()->get("page_url") }}{{$page->uri}}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Otwórz stronę" target="_blank"><i class="fe-link"></i></a>
                <a href="{{route('admin.page.edit', $page->id)}}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Edytuj"><i class="fe-edit"></i></a>
            @else
                <a href="{{$page->url}}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Otwórz stronę" target="_blank"><i class="fe-link"></i></a>
                <a href="{{route('admin.url.edit', $page->id)}}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Edytuj"><i class="fe-edit"></i></a>
            @endif
            <form method="POST" action="{{route('admin.page.destroy', $page->id)}}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn action-button confirm" data-toggle="tooltip" data-placement="top" title="Usuń" data-id="{{ $page->id }}"><i class="fe-trash-2"></i></button>
            </form>
        </div>
    </td>
</tr>
@if($page->children->count() > 0)
    @include('admin.page.children', array('pages' => $page->children))
@endif
@endforeach
