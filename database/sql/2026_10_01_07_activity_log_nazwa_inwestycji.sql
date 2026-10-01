-- Model Investment logował z literówką "Investycje" - od 2026-10-01 log_name = "Inwestycje".
-- Ujednolicenie starych wpisów dziennika, żeby filtr "Moduł" w panelu pokazywał jedną pozycję.

UPDATE `activity_log` SET `log_name` = 'Inwestycje' WHERE `log_name` = 'Investycje';
