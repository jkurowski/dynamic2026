<div class="modal fade modal-form" id="portletModal" tabindex="-1" aria-labelledby="portletModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="" method="post" id="modalForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="portletModalLabel">Historia zmian: {{ $property->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fe-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="modal-form container">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-center">Status przed</th>
                                <th class="text-center pe-0 ps-0"></th>
                                <th class="text-center">Status po</th>
                                <th class="text-center">Data</th>
                                <th class="text-center">Użytkownik</th>
                                <th class="text-center">Klient</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($historyData as $history)
                                <tr>
                                    <td><span class="badge room-list-status-{{ $history['previous_status'] }}">{{ roomStatus($history['previous_status']) }}</span></td>
                                    <td class="pe-0 ps-0"><i class="fe-arrow-right"></i></td>
                                    <td><span class="badge room-list-status-{{ $history['new_status'] }}">{{ roomStatus($history['new_status']) }}</span></td>
                                    <td class="text-center">{{ $history['timestamp'] }}</td>
                                    <td class="text-center">{{ $history['user']->name }}</td>
                                    <td class="text-center">
                                        @if($history['client'])
                                            <a href="{{ route('admin.crm.clients.show', $history['client']->id) }}">{{ $history['client']->name }} {{ $history['client']->lastname }}</a>
                                        @else - @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const modalElement = document.getElementById('portletModal'), bootstrapModal = new bootstrap.Modal(modalElement)
    bootstrapModal.show();
    modalElement.addEventListener('hidden.bs.modal', function () {
        $('#portletModal').remove();
    });
</script>
