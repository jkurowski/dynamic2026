<div class="modal fade modal-form" id="portletModal" tabindex="-1" aria-labelledby="portletModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="" method="post" id="modalForm">
                @csrf
                <div class="modal-header">
                    @isset($entry->title)
                        <h5 class="modal-title" id="portletModalLabel">Edytuj zgłoszenie - {{ $entry->title }}</h5>
                    @else
                        <h5 class="modal-title" id="portletModalLabel">Dodaj zgłoszenie</h5>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fe-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="modal-form container">

                        <div class="form-group row">
                            <div class="col-4">
                                <div class="row">
                                    <label for="inputType" class="col-12 col-form-label control-label required mb-2 justify-content-start">Kategoria</label>
                                    <div class="col-12">
                                        <select class="form-select" id="inputType" name="type">
                                            <option value="5" @if($entry->type == 5) selected @endif>Zmiana lokatorska</option>
                                            <option value="7" @if($entry->type == 7) selected @endif>Usterka</option>
                                        </select>
                                        @if($errors->first('type'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('type') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    <label for="inputStatus" class="col-12 col-form-label control-label required mb-2 justify-content-start">Status</label>
                                    <div class="col-12">
                                        <select class="form-select" id="inputStatus" name="status">
                                            <option value="1" @if($entry->status == 1) selected @endif>Nowe</option>
                                            <option value="2" @if($entry->status == 2) selected @endif>W toku</option>
                                            <option value="3" @if($entry->status == 3) selected @endif>Zakończone</option>
                                            <option value="4" @if($entry->status == 4) selected @endif>Odrzucone</option>
                                            <option value="5" @if($entry->status == 5) selected @endif>Zawieszone</option>
                                        </select>
                                        @if($errors->first('status'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('status') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    <label for="inputDate" class="col-12 col-form-label control-label required mb-2 justify-content-start">Data</label>
                                    <div class="col-12">
                                        <input type="text" value="{{$entry->start}}"
                                               class="validate[required] form-control @error('start') is-invalid @enderror"
                                               id="inputDate" name="start">
                                        @if($errors->first('start'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('start') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <div class="row">
                                    <label for="inputTitle" class="col-12 col-form-label control-label required mb-2 justify-content-start">Nazwa<span class="text-danger d-inline w-auto ps-1">*</span>
                                    </label>
                                    <div class="col-12">
                                        <input type="text" class="validate[required] form-control @error('title') is-invalid @enderror" id="inputTitle" name="title" value="{{$entry->title}}" required>
                                        @if($errors->first('title'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('title') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-6">
                                <div class="row">
                                    <label for="inputClient" class="col-12 col-form-label control-label required mb-2 justify-content-start">Zgłaszający<span class="text-danger d-inline w-auto ps-1">*</span></label>
                                    <div class="col-12">
                                        <input type="text"
                                               class="validate[required] form-control w-100 @error('client') is-invalid @enderror"
                                               id="inputClient"
                                               name="client"
                                               autocomplete="off"
                                        >
                                        <input type="hidden" name="client_id" value="{{ $entry->contact_id }}" id="inputClientId">
                                        <div id="selectedClientItem">
                                            @if($entry->contact_id != 0)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h4>{{ $entry->client->name }} <button type="button" id="btn-confirm" class="btn btn-primary btn-sm action-button"><i class="las la-trash-alt"></i></button></h4>
                                                        @if($entry->client->mail)<span>E: {{$entry->client->mail}}</span>@endif
                                                        @if($entry->client->phone)<span>T: {{$entry->client->phone}}</span>@endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        @if($errors->first('client'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('client') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <label for="inputDepartment"
                                           class="col-123 col-form-label control-label required mb-2 justify-content-start">Dział<span class="text-danger d-inline w-auto ps-1">*</span></label>
                                    <div class="col-12">
                                        <select class="form-select" id="inputDepartment" name="department_id">
                                            @foreach($departments as $i)
                                                <option value="{{ $i->id }}" @if($entry->department_id == $i->id) selected @endif>{{ $i->name }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->first('department_id'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('department_id') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-6">
                                <div class="row">
                                    <label for="inputInvestment"
                                           class="col-123 col-form-label control-label required mb-2 justify-content-start">Inwestycja<span class="text-danger d-inline w-auto ps-1">*</span></label>
                                    <div class="col-12">
                                        <select class="form-select" id="inputInvestment" name="investment_id" onchange="fetchInvestmentProperties()">
                                            <option value="0">Inwestycja</option>
                                            @foreach($investments as $i)
                                                <option value="{{ $i->id }}" @if($entry->investment_id == $i->id) selected @endif>{{ $i->name }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->first('investment_id'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('investment_id') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="row dynamic-property-row d-none">
                                    <label for="inputProperty" class="col-12 col-form-label control-label required mb-2 justify-content-start">Mieszkanie</label>
                                    <div class="col-12">
                                        <select class="form-control selectpicker" data-live-search="true" name="property_id" id="inputProperty">
                                            <option value="0">Wybierz opcje</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="row dynamic-storage-row d-none mt-3">
                                    <label for="inputStorage" class="col-12 col-form-label control-label required mb-2 justify-content-start">Komórka lokatorska</label>
                                    <div class="col-12">
                                        <select class="form-control selectpicker" data-live-search="true" name="storage_id" id="inputStorage">
                                            <option value="0">Wybierz opcje</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="row dynamic-parking-row d-none mt-3">
                                    <label for="inputParking" class="col-12 col-form-label control-label required mb-2 justify-content-start">Miejsce parkingowe</label>
                                    <div class="col-12">
                                        <select class="form-control selectpicker" data-live-search="true" name="parking_id" id="inputParking">
                                            <option value="0">Wybierz opcje</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <div class="row">
                                    <label for="inputNote" class="col-12 col-form-label control-label required mb-2 justify-content-start">Notatka</label>
                                    <div class="col-12">
                                        <textarea name="note" cols="30" rows="5"
                                                  class="form-control @error('note') is-invalid @enderror"
                                                  id="inputNote" style="resize: none">{{ $entry->note }}</textarea>
                                        @if($errors->first('note'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('note') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('/js/typeahead.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/bootstrap-select/bootstrap-select.min.js') }}" charset="utf-8"></script>
<link href="{{ asset('/js/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
<script>
    function initModal()
    {

        const modalElement = document.getElementById('portletModal'),
            bootstrapModal = new bootstrap.Modal(modalElement),
            form = document.getElementById('modalForm'),
            inputInvestment = $('#inputInvestment'),
            inputDepartment = $('#inputDepartment'),

            inputProperty = $('#inputProperty'),
            inputStorage = $('#inputStorage'),
            inputParking = $('#inputParking'),

            inputDate = $('#inputDate'),
            inputClient = $('#inputClient'),
            inputClientId = $('#inputClientId'),
            inputNote = $('#inputNote'),
            inputTitle = $('#inputTitle');
        inputStatus = $('#inputStatus');
        inputType = $('#inputType');

        function fetchInvestmentProperties() {
            const investmentId = document.getElementById('inputInvestment').value;
            const selectPropertyRow = document.querySelector('.dynamic-property-row');
            const selectPropertyElement = document.getElementById('inputProperty');
            const selectStorageRow = document.querySelector('.dynamic-storage-row');
            const selectStorageElement = document.getElementById('inputStorage');
            const selectParkingRow = document.querySelector('.dynamic-parking-row');
            const selectParkingElement = document.getElementById('inputParking');

            if (investmentId !== '0') {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/developro/investment/' + investmentId + '/properties', true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        const properties = JSON.parse(xhr.responseText);

                        if (Object.keys(properties).length > 0) {
                            console.log('properties exist');

                            const propertyTypes = {
                                property: 1,
                                storage: 2,
                                parking: 3
                            };

                            Object.entries(propertyTypes).forEach(([type, value]) => {
                                const selectRow = document.querySelector(`.dynamic-${type}-row`);
                                const selectElement = document.getElementById(`input${type.charAt(0).toUpperCase() + type.slice(1)}`);

                                if (properties[value] && properties[value].length > 0) {
                                    console.log(`properties type:${type} exist`);

                                    selectRow.classList.remove('d-none');

                                    const firstOption = selectElement.options[0];
                                    selectElement.innerHTML = '';
                                    selectElement.appendChild(firstOption);

                                    properties[value].forEach(property => {
                                        const option = document.createElement('option');
                                        option.value = property.id;
                                        option.textContent = property.name;
                                        selectElement.appendChild(option);
                                    });
                                    $(selectElement).selectpicker();
                                } else {
                                    selectRow.classList.add('d-none');
                                    selectElement.innerHTML = '';
                                    $(selectElement).selectpicker('destroy');
                                }
                            });

                            @if($entry->property_id)
                            console.log("Jest mieszkanie: " + {{ $entry->property_id }});
                            $('.dynamic-property-row .selectpicker').selectpicker('val', '{{ $entry->property_id }}');
                            @endif

                            @if($entry->parking_id)
                            console.log("Jest parking: " + {{ $entry->parking_id }});
                            $('.dynamic-parking-row .selectpicker').selectpicker('val', '{{ $entry->parking_id }}');
                            @endif

                            @if($entry->storage_id)
                            console.log("Jest komórka lokatorska" + {{ $entry->storage_id }});
                            $('.dynamic-storage-row .selectpicker').selectpicker('val', '{{ $entry->storage_id }}');
                            @endif

                        } else {
                            const elementsToReset = [selectPropertyRow, selectStorageRow, selectParkingRow];
                            const selectElementsToReset = [selectPropertyElement, selectStorageElement, selectParkingElement];

                            resetSelectElements(elementsToReset, selectElementsToReset);
                        }
                    }
                };
                xhr.send();
            } else {
                const elementsToReset = [selectPropertyRow, selectStorageRow, selectParkingRow];
                const selectElementsToReset = [selectPropertyElement, selectStorageElement, selectParkingElement];

                resetSelectElements(elementsToReset, selectElementsToReset);
            }
        }

        function resetSelectElements(selectRows, selectElements) {
            selectRows.forEach(selectRow => selectRow.classList.add('d-none'));
            selectElements.forEach(selectElement => {
                const firstOption = selectElement.options[0];
                selectElement.innerHTML = '';
                selectElement.appendChild(firstOption);
                $(selectElement).selectpicker('destroy');
            });
        }

        const users = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.nonword(['name', 'mail', 'phone']),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: {
                url: "/admin/rodo/clients"
            }
        });

        bootstrapModal.show();
        modalElement.addEventListener('shown.bs.modal', function () {
            fetchInvestmentProperties();

            inputDate.datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                language: "pl",
                autoclose: true
            });

            users.clearPrefetchCache();
            users.initialize();
            inputClient.typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 3
                },
                {
                    name: 'users',
                    templates: {
                        suggestion: function (data) {
                            return '<div class="item">' +
                                '<div class="row">' +
                                '<div class="col-12"><h4>'+ data.name +'</h4></div>' +
                                '<div class="col-12">' + (data.mail ? '<span>E: ' + data.mail + '</span>' : '') + '</div>' +
                                '<div class="col-12">' + (data.phone ? '<span>T: ' + data.phone + '</span>' : '') + '</div>' +
                                '</div>' +
                                '</div>';
                        }
                    },
                    display: 'value',
                    source: users
                }).on('typeahead:select', function (ev, suggestion) {
                console.log('Selected suggestion:', suggestion);
                console.log('Before setting inputClient value:', inputClient.val());

                inputClientId.val(suggestion.id);
                inputClient.val(suggestion.name);

                inputClient.typeahead('val', suggestion.name)

                console.log('After setting inputClient value:', inputClient.val());

                const clientItem = document.querySelector('#selectedClientItem');
                if (clientItem) {
                    clientItem.innerHTML = '<div class="row"><div class="col-12">' +
                        '<h4>' + suggestion.name + '<button type="button" id="btn-confirm" class="btn btn-primary btn-sm action-button"><i class="las la-trash-alt"></i></button></h4>' +
                        (suggestion.mail ? '<span>E: ' + suggestion.mail + '</span>\n' : '') +
                        (suggestion.phone ? '<span>T: ' + suggestion.phone + '</span>\n' : '') +
                        '</div></div>';

                    clientItem.addEventListener('click', function(event) {
                        if (event.target && (event.target.id === 'btn-confirm' || event.target.closest('#btn-confirm'))) {
                            clientItem.innerHTML = '';
                            inputClientId.val(0);
                            inputClient.typeahead('val', '')
                        }
                    });
                }
                $("#inputInvestment").focus();
            });

            @if($entry->contact_id != 0)
            inputClient.typeahead('val', '{{ $entry->client->name }}');
            @endif

            const elements = document.querySelectorAll(".btn-group label");
            for (let i = 0; i < elements.length; i++) {
                elements[i].addEventListener("click", function(e) {
                    const input = document.getElementById("inputActivity");
                    input.placeholder = e.currentTarget.dataset.bsTitle;
                });
            }
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger : 'hover'
                })
            });
        })
        document.getElementById('inputClient').addEventListener('input', () => {
            inputClientId.val(0);
        })
        modalElement.addEventListener('hidden.bs.modal', function () {
            $('#portletModal').remove();
            users.clearPrefetchCache();
        })

        const alert = $('.alert-danger');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            $.ajax({
                @isset($entry->title)
                url: route('admin.crm.issue.update', {{ $entry->id }}),
                method: 'PUT',
                @else
                url: route('admin.crm.issue.store'),
                method: 'POST',
                @endif
                data: {
                    '_token': '{{ csrf_token() }}',
                    'investment_id': inputInvestment.val() !== 0 ? inputInvestment.val() : null,
                    'property_id': inputProperty.val() !== 0 ? inputProperty.val() : null,
                    'parking_id': inputParking.val() !== 0 ? inputParking.val() : null,
                    'storage_id': inputStorage.val() !== 0 ? inputStorage.val() : null,
                    'department_id': inputDepartment.val() !== 0 ? inputDepartment.val() : null,
                    'title': inputTitle.val(),
                    'start': inputDate.val(),
                    'note': inputNote.val(),
                    'status': inputStatus.val(),
                    'type': inputType.val(),
                    'contact_id': inputClientId.val()
                },
                success: function () {
                    bootstrapModal.hide();
                    toastr.options =
                        {
                            "closeButton": true,
                            "progressBar": true
                        }
                    toastr.success("Wpis został poprawnie dodany");
                },
                error: function (result) {
                    if (result.responseJSON.data) {
                        alert.html('');
                        $.each(result.responseJSON.data, function (key, value) {
                            alert.show();
                            alert.append('<span>' + value + '</span>');
                        });
                    }
                }
            });
        });
    }
</script>
