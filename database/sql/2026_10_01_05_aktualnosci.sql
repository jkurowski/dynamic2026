-- Aktualności: kategoria wpisu (etykieta na karcie: NOWA INWESTYCJA / DZIENNIK INWESTYCJI / PORADNIK - jak w projekcie)
-- oraz strona "Aktualności" w pages (meta SEO listy, okruszki). Lista kategorii: App\Models\Article::KATEGORIE.

ALTER TABLE `articles`
    ADD COLUMN `category` VARCHAR(50) NULL AFTER `slug`;

INSERT INTO `pages` (`id`, `parent_id`, `title`, `slug`, `uri`, `_lft`, `_rgt`, `meta_title`, `meta_description`, `active`, `type`, `sort`, `created_at`, `updated_at`) VALUES
(5, NULL, 'Aktualności', 'aktualnosci', 'aktualnosci', 9, 10, 'Aktualności - Dynamic Development', 'Porady, dziennik inwestycji i nowości od Dynamic Development.', 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `title` = VALUES(`title`), `slug` = VALUES(`slug`), `uri` = VALUES(`uri`),
    `meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `updated_at` = NOW();
