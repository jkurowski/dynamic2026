@if($properties->count() > 0)
    <table style="width:100%;border-collapse:collapse" border="1" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th style="padding: 5px 10px">Nazwa</th>
            <th style="text-align: center;padding: 5px 10px">Pokoje</th>
            <th style="text-align: center;padding: 5px 10px">Powierzchnia</th>
            <th style="text-align: center;padding: 5px 10px">Cena</th>
            <th style="padding: 5px 10px">Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($properties as $room)
            <tr>
                <td style="padding: 5px 10px">{{$room->name_list}} {{$room->number}}</td>
                <td style="text-align: center;padding: 5px 10px">{{$room->rooms}}</td>
                <td style="text-align: center;padding: 5px 10px">{{$room->area}} m<sup>2</sup></td>
                <td style="text-align: center;padding: 5px 10px">
                    @if($room->price)
                        @money($room->price)
                    @endif
                </td>
                <td style="padding: 5px 10px">
                    <b>{{ roomStatus($room->status) }}</b>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
