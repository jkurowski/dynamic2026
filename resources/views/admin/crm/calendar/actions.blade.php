<div class="btn-group">
    <a href="#" class="btn action-button edit-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Edytuj wpis" data-bs-id="{{$row->id}}"><i class="fe-edit"></i></a>
    <form method="POST" action="{{route('admin.crm.calendar.event.destroy', $row->id)}}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button type="submit" class="btn action-button confirmForm" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Usuń wpis" data-id="{{ $row->id }}"><i class="fe-trash-2"></i></button>
    </form>
</div>