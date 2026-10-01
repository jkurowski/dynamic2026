@extends('layouts.page', ['body_class' => 'property'])

@section('meta_title', '')
@section('seo_title', '')
@section('seo_description', '')

@section('content')
    <div id="property">
        <div class="container">

            <div class="row pb-4">
                <div class="col-4">@if($prev_house) <a href="{{route('front.developro.house', [$investment->slug, $prev_house->number_order])}}" class="bttn bttn-sm">{{$prev_house->name}}</a> @endif</div>
                <div class="col-4"></div>
                <div class="col-4 d-flex justify-content-end">@if($next_house) <a href="{{route('front.developro.house', [$investment->slug, $next_house->number_order])}}" class="bttn bttn-sm">{{$next_house->name}}</a> @endif</div>
            </div>

            <div class="row">
                <div class="col-12 col-xl-5">
                    <div class="property-desc">
                        <div class="room-status room-status-{{$property->status}}">
                            {{ roomStatus($property->status )}}
                        </div>

                        @if ($property->status == 2)
                            <div id="notifications" class="p-5">
                                <form id="notification-form">
                                    @csrf
                                    <div class="form-input">
                                        <label for="email">E-mail:</label>
                                        <input type="email" id="email" name="email" class="form-control" required>
                                    </div>
                                    <button type="submit" class="bttn mt-3">Zapisz mnie</button>
                                </form>
                                <div id="notification-message"></div>
                            </div>
                        @endif

                        @if($property->price_brutto)
                            <h6 class="propertyPrice">@money($property->price_brutto)</h6>
                        @endif

                        <ul class="list-unstyled">
                            <li>Pokoje:<span>{{$property->rooms}}</span></li>
                            <li>Powierzchnia:<span>{{$property->area}} m<sup>2</sup></span></li>
                            @if($property->garden_area)<li>Ogródek:<span>{{$property->garden_area}} m<sup>2</sup></span></li>@endif
                            @if($property->balcony_area)<li>Balkon:<span>{{$property->balcony_area}} m<sup>2</sup></span></li>@endif
                            @if($property->balcony_area_2)<li>Balkon 2:<span>{{$property->balcony_area_2}} m<sup>2</sup></span></li>@endif
                            @if($property->terrace_area)<li>Taras:<span>{{$property->terrace_area}} m<sup>2</sup></span></li>@endif
                            @if($property->loggia_area)<li>Loggia:<span>{{$property->loggia_area}} m<sup>2</sup></span></li>@endif
                            @if($property->parking_space)<li>Miejsce postojowe:<span>{{$property->parking_space}}</span></li>@endif
                            @if($property->garage)<li>Garaż:<span>{{$property->garage}}</span></li>@endif
                        </ul>
                    </div>

                    <div class="property-img">
                        @if($property->file)
                            <a href="{{ asset('/investment/property/'.$property->file) }}" class="swipebox">
                                <picture>
                                    <source type="image/webp" srcset="{{ asset('/investment/property/thumbs/webp/'.$property->file_webp) }}">
                                    <source type="image/jpeg" srcset="{{ asset('/investment/property/thumbs/'.$property->file) }}">
                                    <img src="{{ asset('/investment/property/thumbs/'.$property->file) }}" alt="{{$property->name}}">
                                </picture>
                            </a>
                        @endif
                    </div>

                    <div class="property-desc d-flex justify-content-center">
                        @if($property->file_pdf)
                            <a href="{{ asset('/investment/property/pdf/'.$property->file_pdf) }}" target="_blank" class="bttn">POBIERZ PLAN .PDF</a>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-xl-7 ps-3 ps-xl-5">
                    <div id="property-form">
                        <div class="container">
                            <div class="row d-flex">
                                <div class="col-12">
                                    <form method="post" id="contact-form" action="{{route('front.contact.property', $property->id)}}" class="validateForm">
                                        {{ csrf_field() }}
                                        <div class="col-12">
                                            <div class="text-center">
                                                <h2>Zapytaj o {{$property->name}}</h2>
                                            </div>
                                        </div>
                                        @if (session('success'))
                                            <div class="alert alert-success border-0">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        @if (session('warning'))
                                            <div class="alert alert-warning border-0">
                                                {{ session('warning') }}
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-12 form-input">
                                                <label for="form_name">Imię <span class="text-danger">*</span></label>
                                                <input name="name" id="form_name" class="validate[required] form-control @error('name') is-invalid @enderror" type="text" value="{{ old('name') }}">

                                                @error('name')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-sm-6 form-input">
                                                <label for="form_email">E-mail <span class="text-danger">*</span></label>
                                                <input name="email" id="form_email" class="validate[required] form-control @error('email') is-invalid @enderror" type="text" value="{{ old('email') }}">

                                                @error('email')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-sm-6 form-input">
                                                <label for="form_phone">Telefon <span class="text-danger">*</span></label>
                                                <input name="phone" id="form_phone" class="validate[required] form-control @error('phone') is-invalid @enderror" type="text" value="{{ old('phone') }}">

                                                @error('phone')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                            <div class="col-12 mt-1 form-input">
                                                <label for="form_message">Treść wiadomości <span class="text-danger">*</span></label>
                                                <textarea rows="5" cols="1" name="message" id="form_message" class="validate[required] form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>

                                                @error('message')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <div class="rodo-obligation mt-4">
                                                    <p>Zgodnie z art. 13 ust.1 i ust. 2 Rozporządzenia Parlamentu Europejskiego i Rady (UE) 2016/679 z dnia 27 kwietnia 2016 r. w sprawie ochrony osób fizycznych w związku z przetwarzaniem danych osobowych i w sprawie swobodnego przepływu takich danych informujemy, że administratorem Pani/Pana danych osobowych jest Madey Development spółka z ograniczoną odpowiedzialnością 2 sp.k., z siedzibą w  93-120 Łódź ul. Przybyszewskiego 199/205. Dane będą przetwarzane w celu założenia i prowadzenia konta klienta na stronie internetowej w tym przede wszystkim świadczenia usług drogą elektroniczną jak również w celu komunikacji.</p>
                                                    <p>Osobie, której dane dotyczą, przysługuje prawo dostępu do treści swoich danych oraz ich poprawiania a także prawo sprzeciwu i żądania zaprzestania przetwarzania i usunięcia swoich danych osobowych.. Podanie danych osobowych przez użytkownika jest dobrowolne, jednakże odmowa podania danych osobowych spowoduje  brak możliwości skontaktowania się oraz udzielenia ewentualnej odpowiedzi na treść zamieszczoną w formularzu kontaktowym (w tym celu możesz wysłać takie oświadczenie na adres email biuro@madej-bud.pl lub pisemnie na adres siedziby. (<a href="https://www.madeydevelopment.pl/files/upload/polityka_prywatnosci.pdf" target="_blank">Polityka informacyjna</a>):</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row row-form-submit">
                                            <div class="col-12 pt-3">
                                                <div class="input text-center">
                                                    <input name="page" type="hidden" value="{{$property->name}}">
                                                    <script type="text/javascript">
                                                        document.write("<button class=\"bttn\" type=\"submit\">WYŚLIJ WIADOMOŚĆ</button>");
                                                    </script>
                                                    <noscript><p><b>Do poprawnego działania, Java musi być włączona.</b><p></noscript>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('/js/validation.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/pl.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
        document.getElementById('notification-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const csrfToken = formData.get('_token');

            fetch('{{ route("front.developro.properties.notifications.store", $property->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    const messageDiv = document.getElementById('notification-message');
                    if (data.success) {
                        messageDiv.innerText = data.message;
                        messageDiv.style.color = 'green';
                    } else {
                        messageDiv.innerText = data.message;
                        messageDiv.style.color = 'red';
                    }
                })
                .catch(error => {
                    const messageDiv = document.getElementById('notification-message');
                    messageDiv.innerText = 'Wystąpił błąd podczas zapisywania.';
                    messageDiv.style.color = 'red';
                });
        });

        $(document).ready(function(){
            $(".validateForm").validationEngine({
                validateNonVisibleFields: true,
                updatePromptsPosition:true,
                promptPosition : "topRight:-137px"
            });
        });
        @if (session('success')||session('warning'))
        $(window).load(function() {
            const aboveHeight = $('header').outerHeight();
            $('html, body').stop().animate({
                scrollTop: $('.alert').offset().top-aboveHeight
            }, 1500, 'easeInOutExpo');
        });
        @endif
    </script>
@endpush
