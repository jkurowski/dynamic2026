@if($properties->count() > 0)
    @foreach($properties as $room)
        <tr>
            <td class="pe-0 text-center">
                <input type="checkbox" class="checkbox" name="property[]" id="{{$room->id}}" value="{{$room->id}}">
            </td>
            <td><a href="{{$room->url}}" target="_blank">{{$room->name_list}} {{$room->number}}</a></td>
            <td class="text-center">{{$room->rooms}}</td>
            <td class="text-center">{{$room->area}} m<sup>2</sup></td>
            <td class="text-center">
                @if($room->price)
                    @money($room->price)
                @endif
            </td>
            <td>
                <span class="badge room-list-status-{{ $room->status }}">{{ roomStatus($room->status) }}</span>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="6">
            <div class="row">
                <div class="col-12 text-center">
                    <b>Brak wyników</b>
                </div>
            </div>
        </td>
    </tr>
@endif
