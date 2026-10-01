@props([
    'pageTitle' => $pageTitle ?? 'Kontakt',
    'investmentName' => $investmentName ?? null,
    'investmentId' => $investmentName ?? null,
    'propertyId' => $propertyId ?? null,
    'back' => $back ?? false,
    'method' => 'POST',
])

@if($propertyId)
    <form id="contact-form" autocomplete="off" action="{{route('clipboard.send')}}" method="{{ $method }}" class="contact-form validateForm">
        @else
            <form id="contact-form" autocomplete="off" action="{{ route('clipboard.send') }}" method="post" class="validateForm">
                @endif
                @csrf
                @if($back)
                    <input name="back" type="hidden" value="{{ $back }}">
                @endif
                <input type="hidden" name="page" value="{{ $pageTitle }}">
                <div class="row">
                    <div class="col-12">
                        @if (session('success'))
                            <div class="alert alert-success border-0 mb-3">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-warning border-0 mb-3">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-2">
                            <input placeholder="Imię i nazwisko" name="name" id="form_name"
                                   class="validate[required] form-control @error('form_name') is-invalid @enderror" type="text"
                                   value="{{ old('form_name') }}">
                            <label for="form_name">Imię i nazwisko <span class="text-danger">*</span></label>

                            @error('form_name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-12 col-lg-6">
                        <div class="form-floating mb-2">
                            <input placeholder="Adres e-mail" name="email" id="form_email"
                                   class="validate[required] form-control @error('form_email') is-invalid @enderror" type="text"
                                   value="{{ old('form_email') }}">
                            <label for="form_email">Adres e-mail <span class="text-danger">*</span></label>

                            @error('form_email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-12 col-lg-6">
                        <div class="form-floating mb-2">
                            <input placeholder="Telefon" name="phone" id="form_phone"
                                   class="validate[required] form-control @error('form_phone') is-invalid @enderror" type="text"
                                   value="{{ old('form_phone') }}">
                            <label for="form_phone">Telefon <span class="text-danger">*</span></label>

                            @error('form_phone')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                <textarea placeholder="Wiadomość" style='min-height: 100px;' rows="5" cols="1" name="message"
                          id="form_message" class="validate[required] form-control @error('form_message') is-invalid @enderror">{{ old('form_message') }}</textarea>
                            <label for="form_message">Wiadomość <span class="text-danger">*</span></label>

                            @error('form_message')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    @foreach ($rules as $r)
                        <div class="form-check text-start pt-2 pb-2 @error('rule_'.$r->id) is-invalid @enderror">
                            <input class="form-check-input @if($r->required === 1) validate[required] @endif" type="checkbox" value="1" id="rule_{{$r->id}}" name="rule_{{$r->id}}" data-prompt-position="topLeft:70px">
                            <label class="form-check-label fs-10 fs-8 fs-md-10" for="rule_{{$r->id}}">
                                {!! $r->text !!}
                                @error('rule_'.$r->id)
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </label>
                        </div>
                    @endforeach

                    <div class="text-start fs-10 fs-8 fs-md-10">
                        Wyrażona zgoda jest dobrowolna ale niezbędna, żeby zarejestrować Pana/Panią w bazie kontaktowej Administratora Danych. Mają Państwo prawo żądać wstrzymania przetwarzania lub usunięcia danych, które zebraliśmy za Państwa zgodą. Równocześnie mają Państwo prawo do wycofania zgody poprzez przesłanie odpowiedniego oświadczenia na adres: <a href="mailto:bialystok@kalternieruchomosci.pl">bialystok@kalternieruchomosci.pl</a>. Administratorem danych jest Kalter Nieruchomości Sp. z o.o.. Wycofanie zgody nie wpływa na zgodność z prawem przetwarzania danych na podstawie zgody przed jej wycofaniem
                    </div>
                </div>
                <div class="col-12 text-center text-md-start pt-md-3">
                    <script type="text/javascript">
                        document.write("<button type=\"submit\" class=\"g-recaptcha btn btn-primary btn-with-icon \" data-sitekey=\"{{ config('services.recaptcha_v3.siteKey') }}\" data-callback=\"onRecaptchaSuccess\" data-action=\"submitContact\" style=\"min-width: revert;\">Wyślij <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"6.073\" height=\"11.062\" viewBox=\"0 0 6.073 11.062\"><path d=\"M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z\" transform=\"translate(-356 684)\" fill=\"currentColor\" /></svg></button>");
                    </script>
                    <noscript>Do poprawnego działania, Java musi być włączona.</noscript>
                </div>

                <div class="investmentText">
                    {!! $investmentText !!}
                </div>
            </form>

            @push('scripts')
                <script src="{{ asset('js/validation.js') }}" charset="utf-8"></script>
                <script src="{{ asset('js/pl.js') }}" charset="utf-8"></script>
                <script src="https://www.google.com/recaptcha/api.js"></script>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $(".validateForm").validationEngine({
                            validateNonVisibleFields: true,
                            updatePromptsPosition:true,
                            promptPosition : "topRight:-154px",
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
                    $(window).on('load', function () {
                        const aboveHeight = $('header').outerHeight();
                        $('html, body').stop().animate({
                            scrollTop: $('.validateForm').offset().top - aboveHeight - 80
                        }, 1000, 'easeInOutExpo');
                    });
                    @endif
                </script>
    @endpush
