-- Edytowalne stałe sekcje frontu (np. strona główna - O nas).
-- Opis pól sekcji: config/sekcje.php. Tu tylko zapisane wartości - czego nie ma w `dane`,
-- bierze się z wartości domyślnych w configu (treść z szablonu).
-- `dane` to JSON zapisany jako TEXT (bez kolumny typu JSON).

CREATE TABLE IF NOT EXISTS `sekcje` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `klucz` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dane` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sekcje_klucz_unique` (`klucz`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Uprawnienia (Spatie): lista w panelu i edycja (panel + przycisk „Edytuj” na froncie)
INSERT INTO `permissions` (`name`, `guard_name`, `created_at`, `updated_at`)
SELECT 'sekcja-list', 'web', NOW(), NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `name` = 'sekcja-list' AND `guard_name` = 'web');

INSERT INTO `permissions` (`name`, `guard_name`, `created_at`, `updated_at`)
SELECT 'sekcja-edit', 'web', NOW(), NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `name` = 'sekcja-edit' AND `guard_name` = 'web');

INSERT IGNORE INTO `role_has_permissions` (`permission_id`, `role_id`)
SELECT p.`id`, r.`id` FROM `permissions` p JOIN `roles` r ON r.`name` = 'Administrator'
WHERE p.`name` IN ('sekcja-list', 'sekcja-edit') AND p.`guard_name` = 'web';
