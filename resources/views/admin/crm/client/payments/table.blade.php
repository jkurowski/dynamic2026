@php
    $totalAmount = 0;
    $totalCash = 0;
@endphp
@foreach ($property->payments as $item)
    <tr id="recordsArray_{{ $item->id }}">
        <td>{{ $item->due_date }}</td>
        <td class="text-center">
            {{ $item->percent }}%
            @php
                $totalAmount += $item->percent;
            @endphp
        </td>
        <td class="text-center">
            <b>{{ number_format($item->amount, 2, '.', ' ') }} zł</b>
            @php
                $totalCash += $item->amount;
            @endphp
        </td>
        <td class="text-center">{{ $item->updated_at }}</td>
        <td class="text-center">
            @php
                $dueDate = \Carbon\Carbon::parse($item->due_date);
                $currentDate = \Carbon\Carbon::now();
            @endphp

            @if($currentDate->gt($dueDate) && $item->status == 0)
                <div class="paid-status paid-status-failed">
                    <i class="fe-calendar"></i>
                </div>
            @elseif($currentDate->gt($dueDate) && $item->status == 1)
                <div class="paid-status paid-status-paid">
                    <i class="fe-check-square"></i>
                </div>
            @else
                <div class="paid-status paid-status-pending">
                    <i class="fe-clock"></i>
                </div>
            @endif
        </td>
        <td class="option-120">
            <div class="btn-group">
                <button type="button" class="btn action-button edit-button me-1"
                        data-id="{{ $item->id }}" data-bs-toggle="tooltip"
                        data-placement="top" data-bs-title="Edytuj wpis">
                    <i class="fe-edit"></i>
                </button>

                <button type="button" class="btn action-button confirm-delete-button"
                        data-id="{{ $item->id }}" data-bs-toggle="tooltip"
                        data-placement="top" data-bs-title="Usuń wpis">
                    <i class="fe-trash-2"></i>
                </button>
            </div>
        </td>
    </tr>
@endforeach
@if($totalAmount > 100)
    <tr>
        <td colspan="6">
            <div class="alert alert-warning m-0" role="alert">
                Suma procentów jest większa niż 100%
            </div>
        </td>
    </tr>
@endif
@if($totalCash <> $property->price )
    <tr>
        <td colspan="6">
            <div class="alert alert-warning m-0" role="alert">
                Suma cen jest inna niż cena mieszkania
            </div>
        </td>
    </tr>
@endif
<tr>
    <td></td>
    <td class="text-center">Łącznie: {{ $totalAmount }}%</td>
    <td class="text-center">Kwota: {{ number_format($totalCash, 2, '.', ' ') }} zł</td>
    <td class="text-center"></td>
    <td class="option-120" colspan="2">
        <button type="button"
                id="addPayment"
                class="btn btn-primary btn-icon float-end"
                data-bs-toggle="tooltip"
                data-placement="top"
                data-bs-title="Dodaj wpis">
            <i class="fe-plus-square"></i> DODAJ TERMIN
        </button>
    </td>
</tr>
