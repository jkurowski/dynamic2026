<link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('/css/less-partials/modal-table.min.css') }}">
<div class="card">
    <table id="sortable" class="table mb-0">
        <thead class="thead-default">
        <tr>
            <th style="width: 25px"></th>
            <th>Nazwa</th>
            <th>Opis</th>
            <th class="text-center">Rozmiar</th>
            <th class="text-center">Pobrania</th>
            <th>Data dodania</th>
            <th>Data modyfikacji</th>
        </tr>
        </thead>
        <tbody class="content">
        @foreach ($list as $item)
            <tr id="recordsArray_{{ $item->id }}">
                <td><i class="mime-icon {{ mime2icon($item->mime) }}"></i></td>
                <td>
                    @if($item->type == 1)
                        {{ $item->name }}
                    @else
                        <a href="{{route('admin.file-catalog.show', $item->id)}}?type=modal"><b>{{ $item->name }}</b></a>
                    @endif
                </td>
                <td>{{ $item->description }}</td>
                <td class="text-center">
                    @if($item->type == 1)
                        {{ parseFilesize($item->size) }}
                    @endif
                </td>
                <td class="text-center">
                    @if($item->type == 1)
                        {{ $item->download }}
                    @endif
                </td>
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->updated_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

