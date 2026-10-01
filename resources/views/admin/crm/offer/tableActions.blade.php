<div class="btn-group">
    <a href="{{ route('admin.crm.offer.create', ['id' => $row->id]) }}" class="btn action-edit-button action-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Edytuj zgłoszenie">
        <i class="fe-edit"></i>
    </a>
    <form method="POST" action="#" class="d-none">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button type="submit" class="btn action-button confirmForm" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Usuń zgłoszenie" data-id="{{ $row->id }}">
            <i class="fe-trash-2"></i>
        </button>
    </form>
</div>