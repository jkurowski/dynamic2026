<?php

/*
 * Edytowalne stałe sekcje frontu.
 *
 * Każda sekcja to klucz (np. 'strona-glowna.o-nas') i lista pól. Wartości zapisane w panelu siedzą
 * w tabeli `sekcje` (kolumna `dane`, JSON jako TEXT). Pole, którego nie zapisano, bierze 'domyslnie'
 * - to treść z szablonu, więc nowa sekcja wygląda jak makieta, dopóki ktoś jej nie zmieni.
 *
 * W widoku:  @php $s = sekcja('strona-glowna.o-nas'); @endphp
 *            <section class="..." {!! $s->edycja() !!}> ... {{ $s->tekst('naglowek') }} ...
 *
 * Typy pól:
 *  - tekst    jedna linia; opcjonalnie 'max' (domyślnie 255)
 *  - html     edytor TinyMCE; 'edytor' => 'mini' (pogrubienie, kursywa, link) - domyślnie
 *  - link     tekst przycisku + adres (adres zaczynający się od / jest względny do strony)
 *  - obrazek  zdjęcie przycinane do 'kadr' [szer, wys] (JPG + WebP) i ALT; 'rozmiar' [szer, wys]
 *             to atrybuty width/height na stronie; domyślnie => ['jpg', 'webp', 'alt'] z public/img
 *
 * Adresy w configu piszemy jako ścieżki (/poznaj-nas), nie route() - config ładuje się przed trasami.
 */

return [

    'strona-glowna.o-nas' => [
        'nazwa' => 'Strona główna - O nas',
        'strona' => '/',
        'pola' => [
            'zdjecie_duze' => [
                'typ' => 'obrazek',
                'etykieta' => 'Zdjęcie duże (lewe)',
                'kadr' => [1080, 1122],
                'rozmiar' => [540, 561],
                'domyslnie' => ['jpg' => 'img/o-nas-duze.jpg', 'webp' => 'img/o-nas-duze.webp', 'alt' => 'Inwestycja Dynamic Development'],
            ],
            'zdjecie_male' => [
                'typ' => 'obrazek',
                'etykieta' => 'Zdjęcie małe (prawe)',
                'kadr' => [710, 916],
                'rozmiar' => [355, 458],
                'domyslnie' => ['jpg' => 'img/o-nas-male.jpg', 'webp' => 'img/o-nas-male.webp', 'alt' => 'Wnętrze mieszkania'],
            ],
            'etykieta' => [
                'typ' => 'tekst',
                'etykieta' => 'Etykieta (nad nagłówkiem)',
                'max' => 60,
                'domyslnie' => 'O NAS',
            ],
            'naglowek' => [
                'typ' => 'tekst',
                'etykieta' => 'Nagłówek',
                'domyslnie' => 'Kreujemy nowoczesną',
            ],
            'naglowek_akcent' => [
                'typ' => 'tekst',
                'etykieta' => 'Nagłówek - druga linia (wyróżniona)',
                'domyslnie' => 'rzeczywistość',
            ],
            'tresc' => [
                'typ' => 'html',
                'etykieta' => 'Treść',
                'domyslnie' => '<p>Stawiamy na rozwiązania niekonwencjonalne, innowacyjne i unikalne. Dzięki temu projekty naszych mieszkań i domów spełniają nie tylko współczesne standardy, ale także stanowią odpowiedź na potrzeby przyszłych właścicieli. Rzeczywistość wcale nie musi być nudna i ponura, a nasze nieruchomości są na to najlepszym dowodem. Kreatywność i fantazja to wartości, które mają ogromny potencjał na przyszłość. Właśnie dlatego realizując kolejne projekty, wspomniane wartości stanowią nasze motto, a także są podstawą ideologii, którą się kierujemy. Budujemy komfortowe mieszkania, domy, a także tworzymy niebanalne przestrzenie do rekreacji i wypoczynku.</p>',
            ],
            'przycisk' => [
                'typ' => 'link',
                'etykieta' => 'Przycisk',
                'domyslnie' => ['tekst' => 'WIĘCEJ O NAS', 'adres' => '/poznaj-nas'],
            ],
        ],
    ],

];
