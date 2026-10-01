@if($list->count() > 0)
<div class="textSlider container pt-5 pb-5">
    <div class="row">
        <ul class="list-unstyled mb-0">
    @foreach ($list as $p)
            <li>
                <picture>
                    <source type="image/jpeg" srcset="{{asset('uploads/gallery/images/'.$p->file) }}">
                    <img src="{{asset('uploads/gallery/images/'.$p->file) }}" alt="{{ $p->name }}">
                </picture>
            </li>
    @endforeach
        </ul>
    </div>
</div>
@endif
