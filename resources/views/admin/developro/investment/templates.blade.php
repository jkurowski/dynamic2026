@extends('admin.layout')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-head container">
                <div class="row">
                    <div class="col-12 pl-0">
                        <h4 class="page-title"><i class="fe-book-open"></i><a
                                href="{{ route('admin.developro.investment.index') }}" class="p-0">Inwestycje</a><span
                                class="d-inline-flex ms-2 me-2">/</span>{{ $cardTitle }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <table class='table'>
                <thead>
                    <tr>
                        <th>
                            Nazwa szablonu
                        </th>
                        <th>
                            Przypisany
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Akcje
                        </th>
                    </tr>
                </thead>
                <tbody>
              
                    @foreach ($tableRows as $key => $value)
                        <tr>
                            <td>
                                {{ $value['name'] }}
                            </td>
                            <td>
                                {{ $value['template_name'] ?? '-' }}
                            </td>
                            <td>
                                @if ($value['template_status'] == 1)
                                    <span class="badge bg-success">Aktywny</span>
                                @else
                                    <span class="badge bg-danger">Nieaktywny</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn action-button position-relative"
                                    data-field="{{ $value['field'] }}" data-template-id="{{ $templateId }}"
                                    
                                    data-bs-toggle="modal" data-bs-target="#actionsTemplatesModal">
                                    <span data-bs-toggle="tooltip" data-placement="top" data-bs-title="Edytuj"
                                        class="position-absolute w-100 h-100 start-0 top-0">
                                    </span>
                                    <i class="fe-edit"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


            {{-- Old --}}
            {{-- <div class="my-4 control-col12 p-4">
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Podziękowanie za wysłanie formularza',
                        'name' => 'investmentTemplates[template_send_thanks]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_send_thanks,
                    ])
                </div>

                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Z ofertą',
                        'name' => 'investmentTemplates[template_offer_mail]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_offer_mail,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Przypomnienie o przesłanej ofercie',
                        'name' => 'investmentTemplates[template_offer_reminder]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_offer_reminder,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Zaproszenie na dzień otwarty',
                        'name' => 'investmentTemplates[template_open_day]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_open_day,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Zaproszenie na podpisanie umowy przedwstępnej',
                        'name' => 'investmentTemplates[template_preliminary_agreement]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_preliminary_agreement,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Zaproszenie na przegląd lokalu',
                        'name' => 'investmentTemplates[template_local_review]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_local_review,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Zaproszenie na odbiór lokalu',
                        'name' => 'investmentTemplates[template_local_pickup]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_local_pickup,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Zaproszenie na podpisanie umowy przeniesienia własności',
                        'name' => 'investmentTemplates[template_transfer_of_ownership]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_transfer_of_ownership,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Informacja o zmianach lokatorskich',
                        'name' => 'investmentTemplates[template_tenant_changes]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_tenant_changes,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Informacja o dokumentach potrzebnych do uzyskania kredytu',
                        'name' => 'investmentTemplates[template_documents_for_credit]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_documents_for_credit,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Faktury i rozliczenia',
                        'name' => 'investmentTemplates[template_invoices]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_invoices,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Przesyłane dokumenty np po odbiorze, umowie',
                        'name' => 'investmentTemplates[template_documents]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_documents,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Oferty specjalne',
                        'name' => 'investmentTemplates[template_special_offers]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_special_offers,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Zaproszenie na spotkanie',
                        'name' => 'investmentTemplates[template_meeting_invitation]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_meeting_invitation,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Przypominajka o spotkaniu',
                        'name' => 'investmentTemplates[template_meeting_reminder]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_meeting_reminder,
                    ])
                </div>
                <div class='row w-100 form-group mb-4'>
                    @include('form-elements.html-select', [
                        'label' => 'Email - Wygaśnięcie oferty',
                        'name' => 'investmentTemplates[template_offer_expiration]',
                        'select' => $emailTemplates,
                        'selected' => $investmentTemplates->template_offer_expiration,
                    ])
                </div>
            </div> --}}
            {{-- Old --}}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="actionsTemplatesModal" tabindex="-1" aria-labelledby="actionsTemplatesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-white" id="actionsTemplatesModalLabel">Edytuj</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class='fe-x'></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.developro.investment.updateTemplates') }}" method="POST"
                        id="edit-templates-form">
                        @csrf
                        <input type="hidden" name="investment_template_id" id="investment-template-id"
                            value="{{ $templateId }}">
                        <input type="hidden" name="field_name" id="field-name" value="">
                        <div class="row w-100 form-group" id="template-name-select-wrapper">
                            @include('form-elements.html-select', [
                                'label' => 'Nazwa szablonu',
                                'name' => 'template_id',
                                'selected' => 0,
                                'select' => $emailTemplates,
                            ])
                        </div>
                        <div class="row w-100 form-group" id="template-status-select-wrapper">
                            @include('form-elements.html-select', [
                                'label' => 'Status',
                                'name' => 'template_status',
                                'selected' => 0,
                                'select' => [0 => 'Nieaktywny', 1 => 'Aktywny'],
                            ])
                        </div>

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-primary" form="edit-templates-form">Zapisz</button>
                </div>
            </div>
        </div>
    </div>

    <script defer>
        document.addEventListener('DOMContentLoaded', function() {

            async function fetchData(body, uri) {
                const response = await fetch(uri, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify(body)
                });

                const data = await response.json();

                return data;

            }



            const modal = document.getElementById('actionsTemplatesModal');
            const modalBody = modal.querySelector('.modal-body');

            modal.addEventListener('shown.bs.modal', async (e) => {
                const clickedButton = e.relatedTarget;
                const templateId = clickedButton.getAttribute('data-template-id');
                const field = clickedButton.getAttribute('data-field');
                const submitButton = modal.querySelector('button[type="submit"]');

                const fieldHiddenInput = document.getElementById('field-name');
                fieldHiddenInput.value = field;

                const templateNameSelectWrapper = document.getElementById(
                    'template-name-select-wrapper');
                const templateNameSelect = templateNameSelectWrapper.querySelector('select');

                const templateStatusSelectWrapper = document.getElementById(
                    'template-status-select-wrapper');
                const templateStatusSelect = templateStatusSelectWrapper.querySelector('select');



                const getTemplatesBody = {
                    investment_template_id: templateId,
                    field_name: field
                }

                try {
                    templateNameSelect.disabled = true;
                    templateStatusSelect.disabled = true;
                    submitButton.disabled = true;
                    const data = await fetchData(getTemplatesBody,
                        '{{ route('admin.developro.investment.getTemplates') }}')
                    if (data.status && data.status === 'success') {
                        const {template_id, template_name, template_status} = data.data;

                        templateNameSelect.value = template_id ?? 0;
                        templateStatusSelect.value = template_status ? 1 : 0;

                        toastr.success('Dane zostały pobrane pomyślnie');
                    } else {
                        toastr.error('Wystąpił błąd podczas pobierania danych');
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error('Wystąpił błąd podczas pobierania danych');
                }
                finally {
                    templateNameSelect.disabled = false;
                    templateStatusSelect.disabled = false;
                    submitButton.disabled = false;
                }




            })
        })
    </script>
@endsection
