<tr>
    <td class="pe-0 text-center">
        <input type="checkbox" class="checkbox" name="property[]" id="{{ $property->id }}" value="{{ $property->id }}" style="display: none;">
        <input type="hidden" name="related_type" value="{{ $property->type }}">
        <span data-property="{{ $property->id }}" class="remove-related"><i class="las la-trash-alt"></i></span>
    </td>
    <td><a href="" target="_blank"><b>{{ $property->name }}</b></a></td>
    <td class="text-center"><b>{{ $property->getLocation() }}</b></td>
    <td class="text-center">Pow.: <b>{{ $property->area }}</b></td>
    <td class="text-center">
        @if($property->price)
            Cena: <b>@money($property->price)</b>
        @endif
    </td>
    <td>
        <span class="badge room-list-status-{{ $property->status }}">{{ roomStatus($property->status) }}</span>
    </td>
</tr>
