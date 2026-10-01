@extends('admin.layout')


@section('content')
    <div class="container-fluid">

        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title d-flex"><i class="fe-inbox"></i>Masowa wysyłka maili</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">

                    </div>
                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
        <div class="card mt-3">
            <div class="card-body p-3">
                <div class="d-flex justify-content-betweenalign-items-center gap-3">
                    <div>
                        <input type="text" class="form-control" placeholder="Wyszukaj użytkownika" id="searchUserInput">
                    </div>
                </div>
            </div>
        </div>
        @error('users')
            <div class="alert alert-danger mt-3">
                {{ $message }}
            </div>
        @enderror
        <form action="{{ route('admin.mass-mail.send') }}" method="POST" id='send-mail-form'>
            @csrf
            <div class="card mt-3">
                <div class="card-body p-0">

                    <div class="table-responsive" style="height: 500px;">
                        <table class="table" style="table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </th>
                                    <th style="width: 200px">Imię</th>
                                    <th style="width: 200px">Nazwisko</th>
                                    <th style="width: 200px">Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox" name="users[]"
                                                value="{{ $user['id'] }}">
                                        </td>
                                        <td data-user-name="{{ $user['name'] ?? '-' }}">{{ $user['name'] ?? '-' }}</td>
                                        <td data-user-surname="{{ $user['surname'] ?? '-' }}">
                                            {{ $user['surname'] ?? '-' }}
                                        </td>
                                        <td data-user-email="{{ $user['email'] ?? '-' }}">{{ $user['email'] ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end p-3 pb-2 align-items-center gap-3">
                        <div>Wybrano użytkowników: <span id="selectedCount">0</span></div>
                    </div>

                </div>
            </div>

            @error('subject')
                <div class="alert alert-danger mt-3">
                    {{ $message }}
                </div>
            @enderror
            @error('content')
                <div class="alert alert-danger mt-3">
                    {{ $message }}
                </div>
            @enderror
            <div class="card mt-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h2 class='h6 mb-5'>Wyślij wiadomość tekstową</h2>
                            <div class="form-group border-0">
                                <label for="subject">Temat wiadomości<abbr class="text-danger">*</abbr></label>
                                <input required type="text" class="form-control" id="message-subject" name="subject">
                            </div>
                            <div class="form-group border-0">
                                <label for="content">Treść wiadomości</label>
                                <textarea class="form-control" id="message-content" name="content" style="min-height: 200px;"
                                    placeholder="Treść wiadomości"></textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary">Wyślij maila</button>
                            </div>
                        </div>
                        <div class="col-6">
                            <h2 class='h6 mb-5'>Lub wybierz szablon wiadomości</h2>
                        
    
                            <label for="select-template">Wybierz szablon</label>
                            <select class="form-select" name="template" id="select-template">
                                <option value="">Wybierz szablon</option>
                                @foreach ($templates as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <p class="text-end mt-2">
                                <a href="{{ route('admin.email.generator.index') }}">
                                    <i class="fe-plus"></i>
                                    Utwórz nowy szablon
                                </a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('/js/editor/tinymce.min.js') }}" charset="utf-8"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkboxManager = createCheckboxManager('selectAll', 'input[name="users[]"]');
            const searchUserManager = createSearchUserManager('searchUserInput');

            checkboxManager.init();
            searchUserManager.init();
            initializeEditor();
        });

        const createCheckboxManager = (selectAllId, checkboxSelector) => {
            const selectAll = document.getElementById(selectAllId);
            const checkboxes = Array.from(document.querySelectorAll(checkboxSelector));
            const selectedCountElement = document.getElementById('selectedCount');

            const updateSelectedCount = () => {
                selectedCountElement.textContent = checkboxes.filter(checkbox => checkbox.checked).length;
            };

            return {
                init: () => {
                    selectAll.addEventListener('change', () => {
                        checkboxes.forEach(checkbox => checkbox.checked = selectAll.checked);
                        updateSelectedCount();
                    });

                    checkboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', () => {
                            selectAll.checked = checkboxes.every(checkbox => checkbox.checked);
                            updateSelectedCount();
                        });
                    });

                    updateSelectedCount();
                }
            };
        };

        const createSearchUserManager = (inputId) => {
            const input = document.getElementById(inputId);
            const rows = Array.from(document.querySelectorAll('tbody tr'));

            const filterRows = searchText => {
                const lowerSearchText = searchText.toLowerCase();
                rows.forEach(row => {
                    const rowData = ['name', 'surname', 'email']
                        .map(attr => row.querySelector(`td[data-user-${attr}]`)?.getAttribute(
                            `data-user-${attr}`)?.toLowerCase() || '')
                        .join(' ');
                    row.style.display = rowData.includes(lowerSearchText) ? '' : 'none';
                });
            };

            return {
                init: () => {
                    input.addEventListener('input', () => filterRows(input.value));
                }
            };
        };

        const initializeEditor = () => {
            tinymce.init({
                selector: "#message-content",
                language: "pl",
                plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste help wordcount emoticons',
                toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | help',
                menubar: 'file edit view insert format tools table help',
                toolbar_mode: 'sliding',
                contextmenu: 'link image table',
                height: 300,
                content_style: 'body { font-family: Arial, Helvetica, sans-serif; font-size: 14px; }',
                paste_data_images: true,
                convert_urls: false,
                relative_urls: false,
                remove_script_host: false,
                branding: false,
            });
        };
    </script>
@endpush
