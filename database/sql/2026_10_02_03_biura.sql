-- Biura sprzedaży (panel -> Miasta, tabela cities): boksy na stronie Kontakt, zakładki biur w sekcji kontaktu
-- (strona główna, Finansowanie, Wykończenie, Poznaj nas...) i pinezki na mapie biur.
-- Nowe pola: address (HTML z mini edytora), map_link (Google Maps - "Wyznacz trasę"), file (obrazek kodu QR).
-- phone i working_hours zmienione na TEXT (HTML z mini edytora, linie rozdzielone <br>).
-- lat/lng - położenie pinezki na mapie biur (przeliczane na pozycję na obrazku mapy, config/mapa.php).
-- Stare kolumny (email, phone2, address_line_*, short_message, contact_*, footer) zostają, nieużywane w nowym szablonie.

ALTER TABLE `cities`
    ADD COLUMN `address` TEXT NULL AFTER `slug`,
    MODIFY COLUMN `phone` TEXT NULL,
    MODIFY COLUMN `working_hours` TEXT NULL,
    ADD COLUMN `map_link` VARCHAR(500) NULL AFTER `lng`,
    ADD COLUMN `file` VARCHAR(255) NULL AFTER `map_link`;

-- Dane biur z szablonu (dynamic-front: kontakt.html). Godziny Warszawy - wersja ze strony Kontakt.
-- lat/lng z OpenStreetMap (Nominatim) dla adresów biur. Kod QR: pusty = obrazek z szablonu (img/kod-qr.png).
INSERT INTO `cities` (`id`, `name`, `slug`, `address`, `phone`, `working_hours`, `lat`, `lng`, `map_link`, `file`, `completed`, `active`, `sort`, `created_at`, `updated_at`) VALUES
(1, '{"pl":"Warszawa"}', 'warszawa', 'ul. Bobrowiecka 1B/U3<br>00-728 Warszawa', '+48 576 786 666',
    'Poniedziałek - Piątek 9:00-17:00<br>Sobota - po wcześniejszym umówieniu<br>Niedziela – nieczynne',
    '52.1970523', '21.0463495',
    'https://www.google.com/maps/dir/?api=1&destination=ul.%20Bobrowiecka%201B%2FU3%2C%2000-728%20Warszawa', NULL, 1, 1, 1, NOW(), NOW()),
(2, '{"pl":"Nowa Wola"}', 'nowa-wola', 'ul. Maciejki 8/2<br>05-515 Nowa Wola', '+48 512 379 056',
    'Poniedziałek - Piątek 9:00-17:00',
    '52.1034025', '20.9635929',
    'https://www.google.com/maps/dir/?api=1&destination=ul.%20Maciejki%208%2F2%2C%2005-515%20Nowa%20Wola', NULL, 1, 1, 2, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`), `slug` = VALUES(`slug`), `address` = VALUES(`address`), `phone` = VALUES(`phone`),
    `working_hours` = VALUES(`working_hours`), `lat` = VALUES(`lat`), `lng` = VALUES(`lng`),
    `map_link` = VALUES(`map_link`), `updated_at` = NOW();
