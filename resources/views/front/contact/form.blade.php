<div class="container">
    <div class="row d-flex justify-content-center">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success border-0">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-warning border-0">
                    {{ session('error') }}
                </div>
            @endif
            <form method="post" id="contact-form" action="{{ route('front.contact.send') }}" class="validateForm">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-12 mb-4 mb-md-0 col-md-4 form-input">
                        <label for="form_name">Imię <span class="text-danger">*</span></label>
                        <input name="form_name" id="form_name" class="validate[required] form-control @error('form_name') is-invalid @enderror" type="text" value="{{ old('form_name') }}">

                        @error('form_name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="col-12 mb-4 mb-md-0 col-md-4 form-input d-none">
                        <label for="form_surnames">Nazwisko <span class="text-danger">*</span></label>
                        <input name="form_surnames" id="form_surnames" class="form-control" type="text" value="{{ old('form_surname') }}">
                    </div>
                    <div class="col-12 mb-4 mb-md-0 col-md-4 form-input">
                        <label for="form_email">Adres e-mail <span class="text-danger">*</span></label>
                        <input name="form_email" id="form_email" class="validate[required] form-control @error('form_email') is-invalid @enderror" type="text" value="{{ old('form_email') }}">

                        @error('form_email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="col-12 col-md-4 form-input">
                        <label for="form_phone">Telefon <span class="text-danger">*</span></label>
                        <input name="form_phone" id="form_phone" class="validate[required] form-control @error('form_phone') is-invalid @enderror" type="text" value="{{ old('form_phone') }}">

                        @error('form_phone')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="col-12 mt-4 form-input">
                        <label for="form_message">Treść wiadomości <span class="text-danger">*</span></label>
                        <textarea rows="5" cols="1" name="form_message" id="form_message" class="validate[required] form-control @error('form_message') is-invalid @enderror">{{ old('form_message') }}</textarea>

                        @error('form_message')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="col-12 col-xxl-8">
                        @if($obligation)
                            <div class="rodo-obligation mt-3">
                                {!! $obligation->obligation !!}
                            </div>
                        @endif
                        <div class="rodo-rules">
                            @foreach ($rules as $r)
                                <div class="col-12 @error('rule_'.$r->id) is-invalid @enderror">
                                    <div class="rodo-rule clearfix">
                                        <input name="rule_{{$r->id}}" id="rule_{{$r->id}}" value="1" type="checkbox" @if($r->required === 1) class="validate[required]" @endif data-prompt-position="topLeft:0">
                                        <label for="rule_{{$r->id}}" class="rules-text">
                                            {!! $r->text !!}
                                            @error('rule_'.$r->id)
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12 col-xxl-4 d-flex justify-content-end align-items-end">
                        <div class="form-submit">
                            <input name="form_page" type="hidden" value="{{ $page_name }}">
                            <script type="text/javascript">
                                @if(settings()->get("recaptcha_site_key") && settings()->get("recaptcha_secret_key"))
                                document.write("<button type=\"submit\" class=\"bttn g-recaptcha\" data-sitekey=\"{{ settings()->get("recaptcha_site_key") }}\" data-callback=\"onRecaptchaSuccess\" data-action=\"submitContact\">WYŚLIJ WIADOMOŚĆ</button>");
                                @else
                                document.write("<button class=\"bttn\" type=\"submit\">WYŚLIJ WIADOMOŚĆ</button>");
                                @endif
                            </script>
                            <noscript>Do poprawnego działania, Java musi być włączona.</noscript>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('js/validation.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/pl.js') }}" charset="utf-8"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".validateForm").validationEngine({
                validateNonVisibleFields: true,
                updatePromptsPosition:true,
                promptPosition : "topRight:-137px",
                autoPositionUpdate: false
            });
        });

        function onRecaptchaSuccess(token) {
            $(".validateForm").validationEngine('updatePromptsPosition');
            const isValid = $(".validateForm").validationEngine('validate');
            if (isValid) {
                $("#contact-form").submit();
            } else {
                grecaptcha.reset();
            }
        }

        @if (session('success') || session('warning') || $errors->any())
        $(window).load(function() {
            const aboveHeight = $('header').outerHeight();
            $('html, body').stop().animate({
                scrollTop: $('.validateForm').offset().top-aboveHeight
            }, 1500, 'easeInOutExpo');
        });
        @endif
    </script>
@endpush