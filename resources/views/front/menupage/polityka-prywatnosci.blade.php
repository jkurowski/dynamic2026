@extends('front.menupage.strona-tekstowa')

{{-- Polityka prywatności (pages.uri = polityka-prywatnosci) - tak jak na obecnej stronie klienta
     (dynamicdevelopment.pl/polityka-prywatnosci/): treść ładuje skrypt Cookiebot (deklaracja cookies).
     Nad nim ewentualna treść z panelu (Strony). Skrypt jest w widoku, a nie w treści strony, bo edytor TinyMCE
     usuwa znaczniki <script>.
     Cookiebot pokazuje deklarację tylko na domenach dopisanych w Cookiebot Manager (konto klienta) - na innych
     (dynamic-cms.test, staging) wyświetla błąd "The domain ... is not authorized". --}}

@section('po_tresci')
	<script id="CookieDeclaration" src="https://consent.cookiebot.com/ecbc02bb-a80c-454e-840f-5b069a03f823/cd.js" type="text/javascript" async></script>
@endsection
