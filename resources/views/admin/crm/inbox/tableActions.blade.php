<div class="btn-group">
    <form method="POST" action="{{route('admin.crm.inbox.destroy', $row->id)}}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button type="submit" class="btn action-button confirm" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Usuń" data-id="{{ $row->id }}"><i class="fe-trash-2"></i></button>
    </form>
</div>