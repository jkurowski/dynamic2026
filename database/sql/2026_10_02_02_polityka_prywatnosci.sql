-- Strona "Polityka prywatności" u nas (/polityka-prywatnosci) zamiast odnośnika do obecnej strony klienta.
-- Jak na obecnej stronie klienta: treść ładuje skrypt Cookiebot (widok front/menupage/polityka-prywatnosci).
-- Nad nim treść z panelu (Strony) - na razie pusta.
-- Zastępuje link z 2026_10_02_01 (adres zewnętrzny) linkiem do naszej strony.

INSERT INTO `pages` (`id`, `parent_id`, `title`, `slug`, `uri`, `_lft`, `_rgt`, `content`, `meta_title`, `meta_description`, `meta_robots`, `active`, `type`, `sort`, `created_at`, `updated_at`) VALUES
(6, NULL, 'Polityka prywatności', 'polityka-prywatnosci', 'polityka-prywatnosci', 11, 12, NULL, 'Polityka prywatności - Dynamic Development', 'Polityka prywatności i informacja o plikach cookies serwisu Dynamic Development.', NULL, 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `title` = VALUES(`title`), `slug` = VALUES(`slug`), `uri` = VALUES(`uri`),
    `meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `updated_at` = NOW();

UPDATE `rodo_rules` SET
    `text` = 'Zapoznałem się z <a href="/polityka-prywatnosci" target="_blank" rel="noopener">Polityką prywatności</a> i zawartą w niej Informacją na temat przetwarzania danych osobowych',
    `updated_at` = NOW()
WHERE `id` = 1;
