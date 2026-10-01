{{-- Mała etykieta nad tytułem sekcji (pomarańczowa pinezka + napis). Dodatkowe klasy: na-srodku, pojawia-sie. --}}
<p {{ $attributes->class(['etykieta-sekcji']) }}><img src="{{ asset('img/ikona-pinezka.svg') }}" width="12" height="19" alt=""> {{ $slot }}</p>
