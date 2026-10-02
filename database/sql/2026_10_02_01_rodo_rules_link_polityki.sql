-- Link do Polityki prywatności w klauzuli RODO (formularz kontaktowy): było href="#" z szablonu.
-- Polityka jest na obecnej stronie klienta. Nowa karta - żeby kliknięcie nie kasowało wypełnionego formularza.

UPDATE `rodo_rules` SET
    `text` = 'Zapoznałem się z <a href="https://dynamicdevelopment.pl/polityka-prywatnosci/" target="_blank" rel="noopener">Polityką prywatności</a> i zawartą w niej Informacją na temat przetwarzania danych osobowych',
    `updated_at` = NOW()
WHERE `id` = 1;
