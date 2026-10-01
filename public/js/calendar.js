function initEventEditModal(t = null){

    const modal = document.getElementById('portletModal'),
        calendarEventModal = new bootstrap.Modal(modal),
        form = document.getElementById('modalForm'),
        inputInvestment = $('#inputInvestment'),
        inputDepartment = $('#inputDepartment'),
        inputProperty = $('#inputProperty'),
        inputStorage = $('#inputStorage'),
        inputParking = $('#inputParking'),
        inputDate = $('#inputDate'),
        inputTime = $('#inputTime'),
        inputActivity = $('#inputActivity'),
        inputClient = $('#inputClient'),
        inputClientId = $('#inputClientId'),
        inputNote = $('#inputNote'),
        inputAllDay = $('#inputAllDay'),
        inputEventId = $('#inputEventId'),
        clientItem = document.querySelector('#selectedClientItem');

    const users = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.nonword(['name', 'mail', 'phone']),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: "/admin/rodo/clients"
        }
    });

    calendarEventModal.show();
    modal.addEventListener('shown.bs.modal', function () {
        fetchInvestmentProperties();

        inputDate.datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            language: "pl"
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
            });
        inputClient.on('typeahead:select', function (ev, suggestion) {
            console.log('Selected suggestion:', suggestion);
            console.log('Before setting inputClient value:', inputClient.val());

            inputClientId.val(suggestion.id);
            inputClient.val(suggestion.name);

            inputClient.typeahead('val', suggestion.name)

            console.log('After setting inputClient value:', inputClient.val());

            clientItem.innerHTML = '<div class="row"><div class="col-12">' +
                '<h4>' + suggestion.name + '<button type="button" id="btn-confirm" class="btn btn-primary btn-sm action-button"><i class="las la-trash-alt"></i></button></h4>' +
                (suggestion.mail ? '<span>E: ' + suggestion.mail + '</span>\n' : '') +
                (suggestion.phone ? '<span>T: ' + suggestion.phone + '</span>\n' : '') +
                '</div></div>';

            $("#inputInvestment").focus();
        });

        // TODO: Wgrać dane klienta

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

    modal.addEventListener('hidden.bs.modal', function () {
        $('#portletModal').remove();
        users.clearPrefetchCache();

        // TODO: Odswiezanie widoku w zaleznosci od rodzaju kalendarza
        if (t === null) {
            refreshCalendar();
        } else {
            t.ajax.reload(null, false);
        }
    })

    const alert = $('.alert-danger');
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        jQuery.ajax({
            url: route('admin.crm.calendar.event.update', { id: inputEventId.val() }),
            method: 'PUT',
            data: {
                '_token': token,
                'investment_id': inputInvestment.val(),
                'property_id': inputProperty.val(),
                'parking_id': inputParking.val(),
                'storage_id': inputStorage.val(),
                'department_id': inputDepartment.val(),
                'start': inputDate.val(),
                'end': inputDate.val(),
                'time': inputTime.val(),
                'name': inputActivity.val(),
                'note': inputNote.val(),
                'client_id': inputClientId.val(),
                'allday': inputAllDay.val(),
                'type': $('input[name="type"]:checked').val(),
                'active': $('input[name="active"]:checked').val() ? 1 : 0
            },
            success: function () {
                calendarEventModal.hide();
                toastr.options =
                    {
                        "closeButton": true,
                        "progressBar": true
                    }
                toastr.success("Wpis został zaktualizowany");
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

    clientItem.addEventListener('click', function(event) {
        if (event.target && (event.target.id === 'btn-confirm' || event.target.closest('#btn-confirm'))) {
            clientItem.innerHTML = '';
            inputClientId.val(0);
            inputClient.typeahead('val', '')
        }
    });
}


function initEventModal(t = null){
    const modal = document.getElementById('portletModal'),
        calendarEventModal = new bootstrap.Modal(modal),
        form = document.getElementById('modalForm'),
        inputInvestment = $('#inputInvestment'),
        inputDepartment = $('#inputDepartment'),

        inputProperty = $('#inputProperty'),
        inputStorage = $('#inputStorage'),
        inputParking = $('#inputParking'),

        inputDate = $('#inputDate'),
        inputTime = $('#inputTime'),
        inputActivity = $('#inputActivity'),
        inputClient = $('#inputClient'),
        inputClientId = $('#inputClientId'),
        inputNote = $('#inputNote'),
        inputAllDay = $('#inputAllDay');

    const users = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.nonword(['name', 'mail', 'phone']),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: "/admin/rodo/clients"
        }
    });

    calendarEventModal.show();
    modal.addEventListener('shown.bs.modal', function () {

        inputDate.datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            language: "pl"
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
    modal.addEventListener('hidden.bs.modal', function () {
        $('#portletModal').remove();
        users.clearPrefetchCache();

        // TODO: Odswiezanie widoku w zaleznosci od rodzaju kalendarza
        if (t === null) {
            refreshCalendar();
        } else {
            t.ajax.reload(null, false);
        }
    })

    // Init modal form
    const alert = $('.alert-danger');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        $.ajax({
            url: "/admin/crm/calendar",
            method: 'POST',
            data: {
                '_token': token,
                'investment_id': inputInvestment.val(),
                'department_id': inputDepartment.val(),
                'property_id': inputProperty.val(),
                'parking_id': inputParking.val(),
                'storage_id': inputStorage.val(),
                'start': inputDate.val(),
                'end': inputDate.val(),
                'time': inputTime.val(),
                'name': inputActivity.val(),
                'note': inputNote.val(),
                'client_id': inputClientId.val(),
                'allday': inputAllDay.val(),
                'type': $('input[name="type"]:checked').val()
            },
            success: function () {
                calendarEventModal.hide();
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