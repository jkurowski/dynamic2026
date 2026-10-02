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
 *  - html     edytor TinyMCE (pogrubienie, kursywa, listy, link); 'edytor' => 'linie' - bez akapitów,
 *             Enter = <br> (dane kontaktowe, krótkie opisy)
 *  - link     tekst przycisku + adres (adres zaczynający się od / jest względny do strony)
 *  - obrazek  zdjęcie przycinane do 'kadr' [szer, wys] (JPG + WebP) i ALT; 'rozmiar' [szer, wys]
 *             to atrybuty width/height na stronie; domyślnie => ['jpg', 'webp', 'alt'] z public/img
 *  - ikona    wybór z ikon szablonu: 'opcje' => [plik => [nazwa, szerokość, wysokość]] (bez wgrywania
 *             SVG - wgrany SVG mógłby zawierać skrypt)
 *  - lista    stała liczba elementów (tyle, ile w 'domyslnie'), każdy z polami 'pola'; 'element' - nazwa
 *             elementu w formularzu. Puste pole elementu = wartość domyślna elementu o tym samym numerze.
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

    'strona-glowna.liczby' => [
        'nazwa' => 'Strona główna - Liczby',
        'strona' => '/',
        'pola' => [
            'tlo' => [
                'typ' => 'obrazek',
                'etykieta' => 'Zdjęcie w tle',
                'kadr' => [1920, 503],
                'rozmiar' => [1920, 503],
                'domyslnie' => ['jpg' => 'img/tlo-liczby.jpg', 'webp' => 'img/tlo-liczby.webp', 'alt' => ''],
            ],
            'liczby' => [
                'typ' => 'lista',
                'etykieta' => 'Liczby',
                'element' => 'Liczba',
                'pola' => [
                    'wartosc' => ['typ' => 'tekst', 'etykieta' => 'Liczba (duża)', 'max' => 20],
                    'jednostka' => ['typ' => 'tekst', 'etykieta' => 'Jednostka (obok liczby)', 'max' => 20],
                    'opis' => ['typ' => 'tekst', 'etykieta' => 'Opis (pod liczbą)', 'max' => 60],
                ],
                'domyslnie' => [
                    ['wartosc' => '20+', 'jednostka' => 'LAT', 'opis' => 'DOŚWIADCZENIA W BRANŻY'],
                    ['wartosc' => '1000', 'jednostka' => 'LOKALI', 'opis' => 'MIESZKALNYCH I UŻYTKOWYCH'],
                    ['wartosc' => '40', 'jednostka' => 'TYS.', 'opis' => 'METRÓW KWADRATOWYCH'],
                ],
            ],
        ],
    ],

    'strona-glowna.mapa' => [
        'nazwa' => 'Strona główna - Mapa inwestycji (nagłówek)',
        'strona' => '/',
        'pola' => [
            'etykieta' => ['typ' => 'tekst', 'etykieta' => 'Etykieta (nad nagłówkiem)', 'max' => 60, 'domyslnie' => 'MAPA INWESTYCJI'],
            'naglowek' => ['typ' => 'tekst', 'etykieta' => 'Nagłówek', 'domyslnie' => 'Dobry adres'],
            'naglowek_akcent' => ['typ' => 'tekst', 'etykieta' => 'Nagłówek - druga linia (wyróżniona)', 'domyslnie' => 'dla lepszego życia'],
        ],
    ],

    'strona-glowna.dlaczego-warto' => [
        'nazwa' => 'Strona główna - Dlaczego warto?',
        'strona' => '/',
        'pola' => [
            'zdjecie' => [
                'typ' => 'obrazek',
                'etykieta' => 'Zdjęcie',
                'kadr' => [1920, 1172],
                'rozmiar' => [1370, 836],
                'domyslnie' => ['jpg' => 'img/dlaczego-warto.jpg', 'webp' => 'img/dlaczego-warto.webp', 'alt' => 'Osiedle Dynamic Development'],
            ],
            'etykieta' => ['typ' => 'tekst', 'etykieta' => 'Etykieta (nad nagłówkiem)', 'max' => 60, 'domyslnie' => 'DLACZEGO WARTO?'],
            'naglowek' => ['typ' => 'tekst', 'etykieta' => 'Nagłówek', 'domyslnie' => 'Z myślą o Twoim'],
            'naglowek_akcent' => ['typ' => 'tekst', 'etykieta' => 'Nagłówek - druga linia (wyróżniona)', 'domyslnie' => 'komforcie'],
            'zalety' => [
                'typ' => 'lista',
                'etykieta' => 'Zalety',
                'element' => 'Zaleta',
                'pola' => [
                    'ikona' => [
                        'typ' => 'ikona',
                        'etykieta' => 'Ikona',
                        'opcje' => [
                            'img/ikona-teczka.svg' => ['Teczka', 70, 70],
                            'img/ikona-rysunek.svg' => ['Projekt', 68, 68],
                            'img/ikona-park.svg' => ['Park', 67, 67],
                            'img/ikona-zegar.svg' => ['Zegar', 69, 69],
                            'img/ikona-adres.svg' => ['Adres', 69, 69],
                            'img/ikona-sluchawka.svg' => ['Telefon', 69, 69],
                        ],
                    ],
                    'tytul' => ['typ' => 'tekst', 'etykieta' => 'Tytuł', 'max' => 80],
                    // Spacja PRZED <br class="lamanie-desktop"> (łamanie tylko na komputerze) - TinyMCE usuwa spację po <br>,
                    // a na telefonie <br> jest ukryty i słowa by się skleiły
                    'opis' => ['typ' => 'html', 'etykieta' => 'Opis', 'edytor' => 'linie'],
                ],
                'domyslnie' => [
                    ['ikona' => 'img/ikona-teczka.svg', 'tytul' => '20+ lat doświadczenia', 'opis' => 'Rodzinny deweloper z polskim kapitałem i blisko 1000 zrealizowanych lokali'],
                    ['ikona' => 'img/ikona-rysunek.svg', 'tytul' => 'Funkcjonalna architektura', 'opis' => 'Przemyślane układy, jasne przestrzenie i rozwiązania dopasowane do codziennego życia'],
                    ['ikona' => 'img/ikona-park.svg', 'tytul' => 'Komfortowe otoczenie', 'opis' => 'Osiedla projektowane z myślą o rekreacji, wypoczynku <br class="lamanie-desktop">i dobrze zagospodarowanej przestrzeni.'],
                ],
            ],
        ],
    ],

    // Komponent <x-sekcje.kafle> - ta sama treść na stronie głównej i na podstronie inwestycji
    'kafle' => [
        'nazwa' => 'Kafle Finansowanie i Wykończenie pod klucz',
        'strona' => '/',
        'pola' => [
            'kafle' => [
                'typ' => 'lista',
                'etykieta' => 'Kafle',
                'element' => 'Kafel',
                'pola' => [
                    'zdjecie' => ['typ' => 'obrazek', 'etykieta' => 'Zdjęcie w tle', 'kadr' => [1660, 964], 'rozmiar' => [830, 482]],
                    'etykieta' => ['typ' => 'tekst', 'etykieta' => 'Etykieta (nad nagłówkiem)', 'max' => 60],
                    'naglowek' => ['typ' => 'tekst', 'etykieta' => 'Nagłówek'],
                    'naglowek_akcent' => ['typ' => 'tekst', 'etykieta' => 'Nagłówek - druga linia (wyróżniona)'],
                    'przycisk' => ['typ' => 'link', 'etykieta' => 'Przycisk (adres = link całego kafla)'],
                ],
                'domyslnie' => [
                    [
                        'zdjecie' => ['jpg' => 'img/kafel-finansowanie.jpg', 'webp' => 'img/kafel-finansowanie.webp', 'alt' => ''],
                        'etykieta' => 'FINANSOWANIE',
                        'naglowek' => 'Sprawdź swoją ratę,',
                        'naglowek_akcent' => 'zanim kupisz',
                        'przycisk' => ['tekst' => 'SPRAWDŹ SWOJĄ RATĘ', 'adres' => '/finansowanie'],
                    ],
                    [
                        'zdjecie' => ['jpg' => 'img/kafel-wykonczenie.jpg', 'webp' => 'img/kafel-wykonczenie.webp', 'alt' => ''],
                        'etykieta' => 'WYKOŃCZENIE POD KLUCZ',
                        'naglowek' => 'Zamieszkaj od razu',
                        'naglowek_akcent' => 'po odbiorze',
                        'przycisk' => ['tekst' => 'SPRAWDŹ OFERTĘ', 'adres' => '/wykonczenie-pod-klucz'],
                    ],
                ],
            ],
        ],
    ],

    'strona-glowna.aktualnosci' => [
        'nazwa' => 'Strona główna - Aktualności (nagłówek i przycisk)',
        'strona' => '/',
        'pola' => [
            'etykieta' => ['typ' => 'tekst', 'etykieta' => 'Etykieta (nad nagłówkiem)', 'max' => 60, 'domyslnie' => 'AKTUALNOŚCI'],
            'naglowek' => ['typ' => 'tekst', 'etykieta' => 'Nagłówek', 'domyslnie' => 'Co słychać'],
            'naglowek_akcent' => ['typ' => 'tekst', 'etykieta' => 'Nagłówek - dalsza część (wyróżniona)', 'domyslnie' => 'nowego?'],
            'przycisk' => ['typ' => 'link', 'etykieta' => 'Przycisk', 'domyslnie' => ['tekst' => 'WSZYSTKIE AKTUALNOŚCI', 'adres' => '/aktualnosci']],
        ],
    ],

    // Stopka jest na każdej stronie - edytować można z dowolnej
    'stopka.kontakt' => [
        'nazwa' => 'Stopka - dane kontaktowe',
        'strona' => '/',
        'pola' => [
            'naglowek' => ['typ' => 'tekst', 'etykieta' => 'Nagłówek kolumny', 'max' => 40, 'domyslnie' => 'KONTAKT'],
            'dane' => [
                'typ' => 'html',
                'etykieta' => 'Dane kontaktowe',
                'edytor' => 'linie',
                'domyslnie' => 'Dynamic Development sp. z o.o.<br>ul. Plonowa 24, <span class="kod-pocztowy">05-515</span> Nowa Wola<br>KRS: 0000257514<br>NIP: 5213389378<br>REGON: 140557694',
            ],
        ],
    ],

];
