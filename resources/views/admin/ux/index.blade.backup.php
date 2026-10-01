@extends('admin.layout')

@section('content')
    <div class="container-fluid">

        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title row"><i class="fe-grid"></i>Nazwa modułu</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                    <a href="#" class="btn btn-primary">Button</a>
                </div>
            </div>
        </div>

        <div class="card-header border-bottom card-nav">
            <nav class="nav">
                <a class="nav-link active" href="#"><span class="fe-globe"></span> Główne ustawienia</a>
                <a class="nav-link" href="#"><span class="fe-hash"></span> Social Media</a>
                <a class="nav-link" href="#"><span class="fe-hard-drive"></span> Logi PA</a>
                <a class="nav-link" href="#"><span class="fe-airplay"></span> Baner na start</a>
                <a class="nav-link" href="#"><span class="fe-facebook"></span> Facebook</a>
            </nav>
        </div>

        <div id="portlets">
            <div class="row">
                <!-- widget -->
                <div class="col-6 mt-3">
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
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end of widget -->
                <!-- widget -->
                <div class="col-6 mt-3">
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
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end of widget -->
                <!-- widget -->
                <div class="col-4 mt-3">
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
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end of widget -->
                <!-- widget -->
                <div class="col-4 mt-3">
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
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end of widget -->
                <!-- widget -->
                <div class="col-4 mt-3">
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
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end of widget -->
                <!-- widget -->
                <div class="col-3 mt-3">
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
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end of widget -->
                <!-- widget -->
                <div class="col-3 mt-3">
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
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end of widget -->
                <!-- widget -->
                <div class="col-3 mt-3">
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
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end of widget -->
                <!-- widget -->
                <div class="col-3 mt-3">
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
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end of widget -->
            </div>
        </div>

        <div class="card p-4 mt-3">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-12">
                        @include('form-elements.input-text', ['label' => 'Pole tekstowe', 'name' => 'input', 'value' => ''])

                        @include('form-elements.input-text', ['label' => 'Pole tekstowe', 'sublabel' => 'Wymagane', 'name' => 'input', 'value' => '', 'required' => 1])

                        @include('form-elements.html-input-text-count', ['label' => 'Pole tekstowe', 'sublabel' => 'Z licznikiem znaków', 'name' => 'input', 'value' => '', 'maxlength' => 255, 'required' => 1])

                        @include('form-elements.html-password', ['label' => 'Pole hasła', 'name' => 'confirm-password', 'value' => '12345', 'required' => 1])

                        @include('form-elements.html-input-file', [
                            'label' => 'Pole file',
                            'sublabel' => '(wymiary: 100px / 100px)',
                            'name' => 'file',
                            'file' => '',
                            'file_preview' => ''
                        ])

                        @include('form-elements.html-select', [
                            'label' => 'Pole wyboru',
                            'name' => 'select',
                            'selected' => '',
                            'select' => [
                                    '1' => 'Tak',
                                    '2' => 'Nie',
                                    '3' => 'Nie wiem'
                            ]
                        ])

                        @include('form-elements.html-select-multiple', [
                            'label' => 'Pole multiwyboru',
                            'name' => 'input',
                            'select' => [
                                    '1' => 'Tak',
                                    '2' => 'Nie',
                                    '3' => 'Nie wiem'
                            ],
                            'selected' => '',
                            'required' => 1
                        ])

                        @include('form-elements.html-textarea', [
                            'label' => 'Pole texarea',
                            'name' => 'texarea',
                            'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ante risus, faucibus ac scelerisque eget, malesuada id magna. Pellentesque viverra dolor et fermentum dignissim. Duis nec magna consectetur, sollicitudin orci varius, suscipit quam.',
                            'rows'=> 8,
                            'cols' => 3,
                            'class' => 'col-9'
                        ])

                        @include('form-elements.textarea-fullwidth', ['label' => 'Pole texarea (TinyMCE)', 'name' => 'texarea', 'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ante risus, faucibus ac scelerisque eget, malesuada id magna. Pellentesque viverra dolor et fermentum dignissim. Duis nec magna consectetur, sollicitudin orci varius, suscipit quam.', 'rows' => 21, 'class' => 'tinymce', 'required' => 1])
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('form-elements.tintmce')
@endsection

@push('scripts')

@endpush
