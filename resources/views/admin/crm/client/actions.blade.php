<a
        href="{{ route('admin.crm.clients.show', $row->id) }}"
        class="btn action-button mr-1"
        data-bs-toggle="tooltip"
        data-bs-title="Profil">
    <i class="fe-user"></i>
</a>
<a
    href="{{ route('admin.crm.clients.chat.show', $row->id) }}"
    class="btn action-button mr-1"
    data-bs-toggle="tooltip"
    data-bs-title="Chat">
    <i class="fe-mail"></i>
</a>
<a
    href="{{ route('admin.crm.clients.calendar', $row->id) }}"
   class="btn action-button mr-1"
   data-bs-toggle="tooltip"
   data-bs-title="Kalendarz">
    <i class="fe-calendar"></i>
</a>
<a
    href="{{ route('admin.crm.clients.notes', $row->id) }}"
    class="btn action-button mr-1"
    data-bs-toggle="tooltip"
    data-bs-title="Notatki">
    <i class="fe-book"></i>
</a>
<a
    href="{{ route('admin.crm.clients.files', $row->id) }}"
    class="btn action-button mr-1"
    data-bs-toggle="tooltip"
    data-bs-title="Pliki">
    <i class="fe-file"></i>
</a>
<a
    href="{{ route('admin.crm.clients.rodo', $row->id) }}"
    class="btn action-button mr-1"
    data-bs-toggle="tooltip"
    data-bs-title="Zgody RODO">
    <i class="fe-check-circle"></i>
</a>
