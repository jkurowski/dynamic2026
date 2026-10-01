@extends('admin.settings.index')

@section('settings')
    <form method="POST" action="{{route('admin.settings.seo.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid p-0">
            <div class="card p-4">
                <div class="card-body p-3 control-col12">

                    <div class="row w-100 form-group">
                        @include('form-elements.html-input-text-count', ['label' => 'Nazwa strony', 'sublabel' => 'Meta tag - title / ilość znaków: 50 - 60', 'name' => 'page_title', 'value' => settings()->get("page_title"), 'maxlength' => 255, 'required' => 1])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.html-input-text-count', ['label' => 'Opis strony', 'sublabel' => 'Meta tag - description / ilość znaków: 120 - 158', 'name' => 'page_description', 'value' => settings()->get("page_description"), 'maxlength' => 255, 'required' => 1])
                    </div>

                    <div class="row w-100">
                        @include('form-elements.input-text', ['label' => 'Adres strony', 'sublabel' => 'URL strony', 'name' => 'page_url', 'value' => settings()->get("page_url"), 'required' => 1])
                    </div>


                    <div class="row w-100 form-group">
                        <div class="col-12 col-form-label control-label"></div>
                        <div class="col-12 control-input">
                            <div class="btn btn-primary" id="pagespeed">PageSpeed Insights</div>
                            <div class="btn btn-primary" id="facebookcrawler">Facebook Crawler</div>
                        </div>
                    </div>

                    <div class="row w-100">
                        @include('form-elements.input-text', ['label' => 'Adres e-mail', 'sublabel' => 'Adres e-mail do formularza kontaktowego', 'name' => 'page_email', 'value' => settings()->get("page_email"), 'required' => 1])
                    </div>

                    <div class="row w-100">
                        <div class="col-12">
                            <div class="section">Podgląd wyszukiwarki Google</div>
                        </div>
                    </div>

                    <div class="row w-100 form-group">
                        <div class="col-12 col-form-label control-label"></div>
                        <div class="col-12 control-input" style="line-height: normal">
                            <h3 style="font-family: arial,sans-serif;font-size:18px;line-height: 1.2;margin:0;font-weight: normal;color:#1a0dab;">{{settings()->get("page_title")}}</h3>
                            <div style="line-height: 14px"><cite style="font-size:14px;line-height: 16px;color: #006621;font-style: normal;font-family: arial,sans-serif">{{settings()->get("page_description")}}</cite></div>
                            <span style="font-family: arial,sans-serif;font-size:13px;line-height:18px;color: #545454">{{settings()->get("page_url")}}</span>
                        </div>
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', ['label' => 'Autor strony', 'sublabel' => 'Meta tag - author', 'name' => 'page_author', 'value' => settings()->get("page_author")])
                    </div>

                    <div class="row w-100 form-group">
                        <label for="page_robots" class="col-12 col-form-label control-label">
                            <div class="text-start w-100">
                                Indeksowanie<br><span>Meta tag - robots</span>
                            </div>
                        </label>
                        <div class="col-12 control-input d-flex align-items-center">
                            <select class="form-select" name="page_robots" id="page_robots">
                                <option value="noindex, nofollow"<?php if(settings()->get("page_robots") == 'noindex, nofollow'){?> selected<?php } ?>>noindex, nofollow</option>
                                <option value="index, follow" <?php if(settings()->get("page_robots") == 'index, follow'){?> selected<?php } ?>>index, follow</option>
                                <option value="index, nofollow" <?php if(settings()->get("page_robots") == 'index, nofollow'){?> selected<?php } ?>>index, nofollow</option>
                                <option value="noindex, follow" <?php if(settings()->get("page_robots") == 'noindex, follow'){?> selected<?php } ?>>noindex, follow</option>
                            </select>
                        </div>
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.html-input-file', [
                            'label' => 'Favicon',
                            'sublabel' => '(wymiary: max 200 px / max 200 px)',
                            'name' => 'page_favicon',
                            'file' => settings()->get("page_favicon"),
                            'file_preview' => 'uploads/',
                            'file_preview_style' => 'border:1px solid #d5d5d5;width:20px'
                        ])
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.textarea', ['label' => 'Plik', 'sublabel' => '<code>robots.txt</code>', 'name' => 'robots_txt', 'value' => $robots, 'rows' => 6])
                    </div>

                    <div class="row w-100">
                        <div class="col-12">
                            <div class="section">Dodatkowe ustawienia</div>
                        </div>
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.html-input-file', ['label' => 'Logo', 'sublabel' => '(wymiary: 40 px / 200 px)', 'name' => 'page_logo', 'value' => settings()->get("page_logo")])
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', ['label' => 'Logo - atrybut ALT', 'name' => 'page_logo_alt', 'value' => settings()->get("page_logo_alt")])
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', ['label' => 'Logo - atrybut Title', 'name' => 'page_logo_title', 'value' => settings()->get("page_logo_title")])
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', ['label' => 'Klucz Google Maps', 'name' => 'google_maps_api', 'value' => settings()->get("google_maps_api")])
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.textarea', ['label' => 'Kod w <code>&lt;head&gt;</code>', 'sublabel' => 'Kod zostanie wklejony po otworzeniu znacznika &lt;head&gt; na każdej podstronie.', 'name' => 'scripts_head', 'value' => settings()->get("scripts_head"), 'rows' => 7])
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.textarea', ['label' => 'Kod po otworzeniu <code>&lt;body&gt;</code>', 'sublabel' => 'Kod zostanie wklejony po otworzeniu znacznika &lt;body&gt; na każdej podstronie.', 'name' => 'scripts_afterbody', 'value' => settings()->get("scripts_afterbody"), 'rows' => 7])
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.textarea', ['label' => 'Kod przed zamknięciem <code>&lt;body&gt;</code>', 'sublabel' => 'Kod zostanie wklejony przed zamknięciem znacznika &lt;body&gt; na każdej podstronie.', 'name' => 'scripts_beforebody', 'value' => settings()->get("scripts_beforebody"), 'rows' => 7])
                    </div>


                    <div class="row w-100">
                        <div class="col-12">
                            <div class="section">Google reCaptcha</div>
                        </div>
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', ['label' => 'Klucz witryny', 'name' => 'recaptcha_site_key', 'value' => settings()->get("recaptcha_site_key")])
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.html-input-password', ['label' => 'Tajny klucz', 'name' => 'recaptcha_secret_key', 'value' => settings()->get("recaptcha_secret_key")])
                    </div>

                    <div class="row w-100">
                        <div class="col-12">
                            <div class="section">SMS Api</div>
                        </div>
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', ['label' => 'Token', 'name' => 'sms_api_token', 'value' => settings()->get("sms_api_token")])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', ['label' => 'Sender', 'name' => 'sms_api_sender', 'value' => settings()->get("sms_api_sender")])
                    </div>

                </div>
            </div>
            <div class="form-group form-group-submit">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <input name="submit" id="submit" value="Zapisz" class="btn btn-primary" type="submit">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-right",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif
        $(document).ready(function(){
            $( ".input-url .form-control" ).each(function() {
                if($( this ).val()) {
                    $( this ).next( ".input-group-append" ).removeClass('d-none').addClass('d-block');
                }
            });
            $( ".input-group-append button" ).click(function() {
                const url = $( this ).offsetParent().children('input').val();
                window.open(url, '_blank');
            });
            $('#pagespeed').click(function() {
                const url = $( "#form_page_url" ).val();
                window.open('https://developers.google.com/speed/pagespeed/insights/?url=' + url, '_blank');
            });

            $('[name=page_email]').tagify({
                'autoComplete.enabled': false
            });
        });
    </script>
@endpush
