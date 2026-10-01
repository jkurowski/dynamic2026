<div class="btn-group">
    <form class='me-1'  method="POST" action="{{ route('admin.crm.inbox.destroy', $row->id) }}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button type="submit" class="btn action-button confirm" data-bs-toggle="tooltip" data-placement="top"
            data-bs-title="Usuń" data-id="{{ $row->id }}"><i class="fe-trash-2"></i></button>
    </form>
    <button type="button" class="btn action-button confirm position-relative" data-bs-toggle="modal" data-msg-id="{{ $row->id }}"
        data-bs-target="#actionsModal">
        <span data-bs-toggle="tooltip" data-placement="top" data-bs-title="Przypisz do inwestycji lub sprzedawcy"
            class="position-absolute w-100 h-100 start-0 top-0">
        </span>
        <i class="fe-user-plus"></i>
    </button>
</div>
