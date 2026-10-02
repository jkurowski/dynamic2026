<?php

/*
 * Mapa w szablonie (img/mapa.jpg, 1920x750) to statyczny obrazek - pinezki stoją w procentach szerokości/wysokości.
 * Przeliczenie lat/lng -> pozycja na obrazku: liniowe, skalibrowane na dwóch punktach, których położenie
 * na mapie wyznaczył projektant (pinezki biur w kontakt.html) i których współrzędne są znane (OpenStreetMap).
 * Sprawdzenie: wychodzi ~1809 px/stopień długości przy ~2883 px/stopień szerokości, a na 52°N powinno być
 * 2883 * cos(52°) = 1776 - mapa jest w przybliżeniu geograficzna, więc kalibracja trzyma się w okolicy.
 * Przy wymianie obrazka mapy - podmienić punkty kalibracji.
 */

return [
    'kalibracja' => [
        // [lat, lng, left %, top %]
        [52.1970523, 21.0463495, 52.4, 42.3],   // biuro Warszawa, ul. Bobrowiecka 1B
        [52.1034025, 20.9635929, 44.6, 78.3],   // biuro Nowa Wola, ul. Maciejki 8
    ],
];
