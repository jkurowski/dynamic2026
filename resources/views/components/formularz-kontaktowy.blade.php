{{-- Karta formularza kontaktowego (div.karta-formularza) - sekcja kontaktu na podstronach i strona Kontakt.
     Obsługa: Front\ContactController@send (wzorzec poligonowa) - zakłada klienta, wiadomość i zgody RODO.
     Props:
       strona       - nazwa strony zapisywana przy zgłoszeniu (client_msg.source, mail do biura)
       investmentId - formularz przy inwestycji (zawęża klauzule RODO, adresaci = biuro inwestycji)
       propertyId   - formularz przy lokalu (inwestycja, budynek, piętro brane z modelu lokalu)
       back         - "Wyślij i wróć": po wysyłce powrót na tę stronę zamiast na /kontakt
     Jedna karta na stronę - skrypt js/formularz.js szuka #formularzKontaktowy. --}}
@props(['strona' => 'Kontakt', 'investmentId' => null, 'propertyId' => null, 'back' => false])
@php
	$klauzule = \App\Models\RodoRules::forFormAndInvestment(\App\Models\RodoRules::FORM_CONTACT, $investmentId ? (int) $investmentId : null);
	$recaptcha = \App\Rules\ReCaptchaV3::isConfigured() ? \App\Rules\ReCaptchaV3::siteKey() : null;
	$bledy = $errors->getBag('default');
@endphp
<div {{ $attributes->class(['karta-formularza', 'pojawia-sie', 'opoznienie-1']) }}>
	<h3>Wyślij wiadomość</h3>

	<form id="formularzKontaktowy" action="{{ route('contact.send') }}" method="post" novalidate @if($recaptcha) data-recaptcha="{{ $recaptcha }}" @endif>
		@csrf
		<input type="hidden" name="page" value="{{ $strona }}">
		@if($investmentId)<input type="hidden" name="investment_id" value="{{ $investmentId }}">@endif
		@if($propertyId)<input type="hidden" name="property_id" value="{{ $propertyId }}">@endif
		@if($back)<input type="hidden" name="back" value="1">@endif
		@if($recaptcha)<input type="hidden" name="g-recaptcha-response" value="">@endif

		<div @class(['pole', 'ma-blad' => $bledy->has('name')])>
			<input type="text" name="name" value="{{ old('name') }}" placeholder="Imię i nazwisko*" maxlength="150" required>
			<img src="{{ asset('img/ikona-uzytkownik.svg') }}" width="31" height="31" alt="">
			<span class="blad">{{ $bledy->first('name') ?: 'Podaj imię i nazwisko.' }}</span>
		</div>
		<div @class(['pole', 'ma-blad' => $bledy->has('phone')])>
			<input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Telefon*" maxlength="30" required>
			<img src="{{ asset('img/ikona-telefon-form.svg') }}" width="31" height="31" alt="">
			<span class="blad">{{ $bledy->first('phone') ?: 'Podaj numer telefonu (min. 9 cyfr).' }}</span>
		</div>
		<div @class(['pole', 'ma-blad' => $bledy->has('email')])>
			<input type="email" name="email" value="{{ old('email') }}" placeholder="E-mail" maxlength="191">
			<img src="{{ asset('img/ikona-koperta.svg') }}" width="31" height="31" alt="">
			<span class="blad">{{ $bledy->first('email') ?: 'Podaj poprawny adres e-mail.' }}</span>
		</div>
		<div @class(['pole', 'ma-blad' => $bledy->has('message')])>
			<textarea name="message" placeholder="Wiadomość*" maxlength="5000" required>{{ old('message') }}</textarea>
			<img src="{{ asset('img/ikona-olowek.svg') }}" width="31" height="31" alt="">
			<span class="blad">{{ $bledy->first('message') ?: 'Napisz wiadomość.' }}</span>
		</div>

		{{-- Klauzule RODO z panelu (rodo_rules) - pole rule_{id}, zapis zgód w ClientObserver --}}
		@foreach($klauzule as $klauzula)
			<label @class(['zgoda', 'ma-blad' => $bledy->has('rule_' . $klauzula->id)])>
				<input type="checkbox" name="rule_{{ $klauzula->id }}" value="1" @checked(old('rule_' . $klauzula->id)) @if($klauzula->required) required data-wymagana @endif>
				<span>{!! $klauzula->text !!}</span>
			</label>
		@endforeach

		<button type="submit" class="formularz-przycisk">
			Wyślij wiadomość
			<x-ikona.strzalka />
		</button>

		@if(session('success'))
			<p class="komunikat-formularza sukces" role="status">{{ session('success') }}</p>
		@elseif($bledy->any())
			{{-- div, nie p - lista <ul> nie może stać w akapicie --}}
			<div class="komunikat-formularza blad" role="status"><ul>@foreach($bledy->all() as $blad)<li>{{ $blad }}</li>@endforeach</ul></div>
		@else
			<p class="komunikat-formularza" role="status"></p>
		@endif
	</form>
</div>

@if($recaptcha)
	@push('scripts')
		<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptcha }}"></script>
	@endpush
@endif
