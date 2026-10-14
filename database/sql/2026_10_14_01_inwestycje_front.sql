-- Inwestycje na nowym froncie (lista /inwestycje, podstrona inwestycji, karta lokalu, /wybierz-nieruchomosc).
-- Nowe pola inwestycji:
--   location_label - nazwa miejsca nad adresem na kartach („WARSZAWA, MOKOTÓW”), adres zostaje w `address`,
--   external_url   - strona inwestycji poza serwisem (inwestycja bez podstrony u nas - „Zobacz inwestycję” prowadzi tam),
--   lat, lng       - pinezka na mapie inwestycji na stronie głównej (przeliczenie jak w biurach: config/mapa.php).
-- Termin oddania: istniejące `date_end` (tekst, np. „IV kwartał 2025”), wstęp/podtytuł: `entry_content`.

ALTER TABLE `investments`
    ADD COLUMN `location_label` VARCHAR(191) NULL AFTER `address`,
    ADD COLUMN `external_url` VARCHAR(500) NULL AFTER `location_label`,
    ADD COLUMN `lat` DECIMAL(10,7) NULL AFTER `external_url`,
    ADD COLUMN `lng` DECIMAL(10,7) NULL AFTER `lat`;

-- Strony w panelu Strony (meta SEO, okruszki) - jak Aktualności (id 5)
INSERT INTO `pages` (`id`, `parent_id`, `title`, `slug`, `uri`, `_lft`, `_rgt`, `meta_title`, `meta_description`, `active`, `type`, `sort`, `created_at`, `updated_at`) VALUES
(7, NULL, 'Inwestycje', 'inwestycje', 'inwestycje', 13, 14, 'Inwestycje w sprzedaży - Dynamic Development', 'Poznaj inwestycje Dynamic Development dopasowane do różnych stylów życia - od miejskiego komfortu po spokojną przestrzeń blisko natury.', 1, 1, 0, NOW(), NOW()),
(8, NULL, 'Wybierz nieruchomość', 'wybierz-nieruchomosc', 'wybierz-nieruchomosc', 15, 16, 'Wybierz nieruchomość - Dynamic Development', 'Mieszkania, domy, segmenty i lokale usługowe ze wszystkich inwestycji Dynamic Development w jednym miejscu.', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `title` = VALUES(`title`), `slug` = VALUES(`slug`), `uri` = VALUES(`uri`),
    `meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `updated_at` = NOW();
