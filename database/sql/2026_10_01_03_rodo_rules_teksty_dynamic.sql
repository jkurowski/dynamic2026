-- Treść klauzul RODO z szablonu Dynamic Development (dynamic-front, formularz kontaktowy).
-- Wcześniej w bazie były teksty po poprzedniej instalacji (Kalter Nieruchomości Sp. z o.o.).
-- Link do polityki prywatności ma href="#" - do podmiany, gdy powstanie podstrona polityki.

UPDATE `rodo_rules` SET
    `title` = 'Polityka prywatności',
    `text` = 'Zapoznałem się z <a href="#" data-strona="polityka-prywatnosci">Polityką prywatności</a> i zawartą w niej Informacją na temat przetwarzania danych osobowych',
    `required` = 1,
    `sort` = 1,
    `updated_at` = NOW()
WHERE `id` = 1;

UPDATE `rodo_rules` SET
    `title` = 'Marketing',
    `text` = 'Wyrażam zgodę na otrzymywanie od Dynamic Development sp. z o.o. informacji marketingowych, przekazywanych za pomocą telekomunikacyjnych urządzeń końcowych oraz tzw. automatycznych systemów wywołujących drogą elektroniczną na podany powyżej adres e-mail',
    `required` = 0,
    `sort` = 2,
    `updated_at` = NOW()
WHERE `id` = 2;
