-- articles.old_id (id wpisu ze starej bazy, używane tylko przez konwersję) było NOT NULL bez wartości domyślnej,
-- przez co dodanie artykułu w panelu kończyło się błędem 500 (SQLSTATE 1364: Field 'old_id' doesn't have a default value).

ALTER TABLE `articles`
    MODIFY COLUMN `old_id` INT NOT NULL DEFAULT 0;
