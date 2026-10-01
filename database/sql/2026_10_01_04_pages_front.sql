-- Strony statyczne frontu (MenuController / ContactController) - rekordy do panelu "Strony" i meta SEO.
-- Drzewo nested set (_lft/_rgt) jak po utworzeniu przez model Page (strony główne, bez rodzica).
-- ON DUPLICATE KEY UPDATE - plik można puścić ponownie bez dublowania.

INSERT INTO `pages` (`id`, `parent_id`, `title`, `slug`, `uri`, `_lft`, `_rgt`, `meta_title`, `meta_description`, `active`, `type`, `sort`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Finansowanie', 'finansowanie', 'finansowanie', 1, 2, 'Finansowanie - Dynamic Development', 'Kalkulator raty kredytu i wsparcie doradcy finansowego przy zakupie mieszkania.', 1, 1, 0, NOW(), NOW()),
(2, NULL, 'Wykończenie pod klucz', 'wykonczenie-pod-klucz', 'wykonczenie-pod-klucz', 3, 4, 'Wykończenie pod klucz - Dynamic Development', 'Gotowe do zamieszkania wnętrze bez koordynowania ekip i materiałów. Sprawdź nasze realizacje.', 1, 1, 0, NOW(), NOW()),
(3, NULL, 'Poznaj nas', 'poznaj-nas', 'poznaj-nas', 5, 6, 'Poznaj nas - Dynamic Development', 'Firma rodzinna z polskim kapitałem. Ponad 20 lat doświadczenia i blisko 1000 zrealizowanych lokali.', 1, 1, 0, NOW(), NOW()),
(4, NULL, 'Kontakt', 'kontakt', 'kontakt', 7, 8, 'Kontakt - Dynamic Development', 'Skontaktuj się z Biurem Sprzedaży Dynamic Development w Warszawie lub Nowej Woli.', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `title` = VALUES(`title`), `slug` = VALUES(`slug`), `uri` = VALUES(`uri`),
    `meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `updated_at` = NOW();
