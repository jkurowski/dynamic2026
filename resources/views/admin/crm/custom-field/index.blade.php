@extends('admin.settings.index')

@section('settings')
    <div class="container-fluid p-0">
        <div id="portlets" class="card p-3">
            <div class="card-body card-body-rem p-0">
                <div class="row">
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Miasta</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="portlet-action d-flex justify-content-end">
                                            <button type="button" class="btn btn-primary text-end" data-bs-toggle="modal" data-bs-target="#portletModal" data-group="1">Dodaj</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-1">
                                    @foreach($list as $item)
                                        @if($item->group_id == 1)
                                            <li class="list-group-item">
                                                {{ $item->value }}
                                                <div class="list-group-action">
                                                    <button type="submit" class="btn btn-sm btn-primary action-button" data-toggle="tooltip" data-placement="top" title="Usuń wpis" data-id="{{ $item->id }}"><i class="las la-trash-alt"></i></button>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Status klienta</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="portlet-action d-flex justify-content-end">
                                            <button type="button" class="btn btn-primary text-end" data-bs-toggle="modal" data-bs-target="#portletModal" data-group="2">Dodaj</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-2">
                                    @foreach($list as $item)
                                        @if($item->group_id == 2)
                                            <li class="list-group-item">
                                                {{ $item->value }}
                                                <div class="list-group-action">
                                                    <button type="submit" class="btn btn-sm action-button" data-toggle="tooltip" data-placement="top" title="Usuń wpis" data-id="{{ $item->id }}"><i class="las la-trash-alt"></i></button>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- widget -->
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Źródła kontaktu</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="portlet-action d-flex justify-content-end">
                                            <button type="button" class="btn btn-primary text-end" data-bs-toggle="modal" data-bs-target="#portletModal" data-group="3">Dodaj</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-3">
                                    @foreach($list as $item)
                                        @if($item->group_id == 3)
                                            <li class="list-group-item">
                                                {{ $item->value }}
                                                <div class="list-group-action">
                                                    <button type="submit" class="btn btn-sm action-button" data-toggle="tooltip" data-placement="top" title="Usuń wpis" data-id="{{ $item->id }}"><i class="las la-trash-alt"></i></button>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- end of widget -->
                    <!-- widget -->
                    <div class="col-4 mt-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Stanowisko w firmie</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="portlet-action d-flex justify-content-end">
                                            <button type="button" class="btn btn-primary text-end" data-bs-toggle="modal" data-bs-target="#portletModal" data-group="4">Dodaj</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-4">
                                    @foreach($list as $item)
                                        @if($item->group_id == 4)
                                            <li class="list-group-item">
                                                {{ $item->value }}
                                                <div class="list-group-action">
                                                    <button type="submit" class="btn btn-sm action-button" data-toggle="tooltip" data-placement="top" title="Usuń wpis" data-id="{{ $item->id }}"><i class="las la-trash-alt"></i></button>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- end of widget -->
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="portletModal" tabindex="-1" aria-labelledby="portletModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="portletModalLabel">Dodaj wpis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="">
                        <label for="value" class="form-label">Wartość</label>
                        <input type="text" class="form-control" id="value" name="value">
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="group_id" value="">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                    <button type="button" class="btn btn-primary" id="ajaxSubmit">Zapisz</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const portletModal = document.getElementById('portletModal');
            const $value = $('#value'), $alert = $('.alert-danger');
            const $parent = $('.list-group');

            portletModal.addEventListener('show.bs.modal', event => {
                const groupId = $(event.relatedTarget).data('group');
                const groupValue = $('input[name="group_id"]');
                groupValue.val(groupId);

                console.log('po otworzeniu modala');
                console.log(groupId);

                $('#ajaxSubmit').click(function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    console.log('po kliku');
                    console.log(groupValue.val());

                    $.ajax({
                        url: "{{ route('admin.crm.custom-fields.store') }}",
                        method: 'post',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            value: $value.val(),
                            group_id: groupValue.val()
                        },
                        success: function(data){
                            $alert.hide().html('');
                            $value.val('');

                            console.log('po success');
                            console.log(groupValue.val());

                            const groupList = $('.list-group-' + groupValue.val());
                            groupList.html('').addClass('list-group-disabled');
                            $.each(data.list, function(key, value){
                                groupList.append('<li class="list-group-item">'+value.value+'<div class="list-group-action"><button type="submit" class="btn btn-primary btn-sm action-button" data-toggle="tooltip" data-placement="top" title="Usuń wpis" data-id="'+value.id+'"><i class="las la-trash-alt"></i></button></div></li>');
                            });
                            groupList.removeClass('list-group-disabled');

                            const modal = bootstrap.Modal.getInstance(portletModal);
                            modal.hide();
                        },
                        error : function(result){
                            console.log(result)
                            if(result.responseJSON.errors)
                            {
                                $alert.html('');
                                $.each(result.responseJSON.errors, function(key, value){
                                    $alert.show();
                                    $alert.append('<span>'+value+'</span>');
                                });
                            }
                        }
                    });
                });
            })
            portletModal.addEventListener('hide.bs.modal', event => {
                $alert.hide().html('');
                $value.val('');
            })

            $parent.on('click', '.action-button', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                const id = $(this).data("id");
                const parent = $(this).parents(".list-group-item");
                let url = '{{ route("admin.crm.custom-fields.destroy", ":custom_field") }}';
                url = url.replace(':custom_field', id );
                $.ajax({
                    url: url,
                    method: 'delete',
                    cache: false,
                    async: false,
                    data: {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(){
                        parent.remove();
                    },
                    error : function(result){
                        console.log('error');
                        console.log(result);
                    }
                });
            });
        </script>
    @endpush
@endsection
