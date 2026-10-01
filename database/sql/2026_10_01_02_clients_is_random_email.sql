-- Formularz kontaktowy nie wymaga e-maila (wymagany jest telefon).
-- Klient bez e-maila dostaje adres zastępczy noemail_{uuid}@example.com (clients.mail jest NOT NULL),
-- a ta flaga mówi, że adres jest sztuczny i nie wolno na niego niczego wysyłać.

ALTER TABLE `clients`
    ADD COLUMN `is_random_email` TINYINT(1) NOT NULL DEFAULT 0 AFTER `mail`;
