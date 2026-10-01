@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-users"></i><a href="{{route('admin.crm.clients.index')}}">Klienci</a><span class="d-inline-flex me-2 ms-2">/</span>{{$client->name}}</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit"></div>
            </div>
        </div>
        @include('admin.crm.client.client_shared.menu')
        <div class="row">
            <div class="col-4">
                @include('admin.crm.client.client_shared.aside')
            </div>
            <div class="col-8">
                <div class="card mt-3">
                    <form method="POST" action="{{route('admin.crm.clients.update', $client)}}" class="card-body control-col12">
                        @method('PUT')
                        @csrf
                        <div class="row w-100 mb-4">
                            <div class="col-3">
                                @include('form-elements.html-select', ['label' => 'Status', 'name' => 'active', 'selected' => '', 'select' => ['1' => 'Aktywny', '2' => 'Nieaktywny']])
                            </div>
                            <div class="col-3">
                                @include('form-elements.html-select', ['label' => 'Opiekun', 'name' => 'user_id', 'selected' => '', 'select' => ['1' => 'Jan Kowalski']])
                            </div>
                        </div>

                        <div class="row w-100 mb-4">
                            <div class="col-6">
                                @include('form-elements.html-input-text', ['label' => 'Imię', 'name' => 'name', 'value' => $client->name, 'required' => 1])
                            </div>
                            <div class="col-6">
                                @include('form-elements.html-input-text', ['label' => 'Nazwisko', 'name' => 'lastname', 'value' => $client->lastname, 'required' => 1])
                            </div>
                        </div>

                        <div class="row w-100 mb-4">
                            <div class="col-6">
                                @include('form-elements.html-input-text', ['label' => 'Adres e-mail', 'name' => 'mail', 'value' => $client->mail, 'required' => 1, 'readonly' => 1])
                            </div>
                            <div class="col-6">
                                @include('form-elements.html-input-text', ['label' => 'Adres e-mail 2', 'name' => 'mail2', 'value' => $client->mail2])
                            </div>
                        </div>

                        <div class="row w-100">
                            <div class="col-6">
                                @include('form-elements.html-input-text', ['label' => 'Telefon', 'name' => 'phone', 'value' => $client->phone, 'required' => 1])
                            </div>
                            <div class="col-6">
                                @include('form-elements.html-input-text', ['label' => 'Telefon 2', 'name' => 'phone2', 'value' => $client->phone2])
                            </div>
                        </div>

                        <div class="row w-100">
                            <div class="col-12">
                                <div class="section">CRM</div>
                            </div>
                        </div>

                        <div class="row w-100 mb-4">
                            <div class="col-6">
                                @include('form-elements.html-select', ['label' => 'Źródło kontaktu', 'name' => 'source', 'selected' => $client->source, 'select' => [
                                    '1' => 'Formularz kontaktowy',
                                    '2' => 'Bezpośrednia wiadomość e-mail',
                                    '3' => 'Telefon',
                                    '4' => 'Chat online',
                                    '5' => 'Media społecznościowe',
                                    '6' => 'Reklama',
                                    '7' => 'Newsletter',
                                    '8' => 'Rekomendacje',
                                    '9' => 'Wydarzenia i konferencje',
                                    '10' => 'Kontakt osobisty',
                                    '11' => 'Inne'
                                ]])
                            </div>
                            <div class="col-6">
                                @include('form-elements.html-input-text', ['label' => 'Dodatkowa informacja o źródle', 'name' => 'source_additional', 'value' => $client->source_additional])
                            </div>
                        </div>

                        <div class="row w-100 mb-4">
                            <div class="col-6">
                                @include('form-elements.html-select', ['label' => 'Status sprzedaży', 'name' => 'status', 'selected' => $client->status, 'select' => [
                                    '1' => 'Kontakt wstępny',
                                    '2' => 'Wysłana Oferta',
                                    '3' => 'Umówione Spotkanie',
                                    '4' => 'Negocjacje',
                                    '5' => 'Umowa Rezerwacyjna',
                                    '6' => 'Umowa Deweloperska',
                                    '7' => 'Zmiany Lokatorskie',
                                    '8' => 'Odbiór Techniczny',
                                    '9' => 'Umowa przeniesienia Własności',
                                    '10' => 'Wydanie Lokalu',
                                    ]])
                            </div>
                            <div class="col-6">
                                @include('form-elements.html-input-text', ['label' => 'Dodatkowa informacja o statusie sprzedaży', 'name' => 'deal_additional', 'value' => $client->deal_additional])
                            </div>
                        </div>

                        <div class="row w-100 mb-4">
                            <div class="col-4">
                                @include('form-elements.html-select', ['label' => 'Inwestycja', 'name' => 'investment_id', 'selected' => '', 'select' => ['1' => 'Nazwa inwestycji']])
                            </div>
                            <div class="col-4">
                                @include('form-elements.html-input-text', ['label' => 'Pokoje', 'name' => 'room', 'value' => $client->room])
                            </div>
                            <div class="col-4">
                                @include('form-elements.html-input-text', ['label' => 'Metraż', 'name' => 'area', 'value' => $client->area])
                            </div>
                        </div>

                        <div class="row w-100 mb-4">
                            <div class="col-6">
                                @include('form-elements.html-input-text', ['label' => 'Budżet', 'name' => 'budget', 'value' => $client->budget])
                            </div>
                            <div class="col-6">
                                @include('form-elements.html-input-text', ['label' => 'Przeznaczenie', 'name' => 'purpose', 'value' => $client->purpose])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-end">
                                <input name="submit" id="submit" value="Zapisz" class="btn btn-primary" type="submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-right",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif

            const myInput = document.getElementById("mail");
            myInput.readOnly = true;
        </script>
    @endpush
@endsection
