-- Zawężanie klauzul RODO do formularzy i inwestycji (wzorzec: poligonowa, 2026-08-16).
-- rodo_rules.forms: lista kodów formularzy (JSON zapisany jako TEXT - baza docelowa nie ma typu JSON),
-- NULL lub [] = klauzula we wszystkich formularzach.
-- rodo_rule_investment: klauzula przypisana do inwestycji; brak przypisań = klauzula wszędzie.

ALTER TABLE `rodo_rules`
    ADD COLUMN `forms` TEXT NULL AFTER `text`;

CREATE TABLE IF NOT EXISTS `rodo_rule_investment` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `rodo_rule_id` BIGINT UNSIGNED NOT NULL,
    `investment_id` BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `rodo_rule_investment_unique` (`rodo_rule_id`, `investment_id`),
    KEY `rodo_rule_investment_investment_id_foreign` (`investment_id`),
    CONSTRAINT `rodo_rule_investment_rodo_rule_id_foreign` FOREIGN KEY (`rodo_rule_id`) REFERENCES `rodo_rules` (`id`) ON DELETE CASCADE,
    CONSTRAINT `rodo_rule_investment_investment_id_foreign` FOREIGN KEY (`investment_id`) REFERENCES `investments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
