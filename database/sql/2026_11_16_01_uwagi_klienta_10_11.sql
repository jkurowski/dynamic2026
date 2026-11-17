-- Uwagi klienta z 10 i 11.11.2026 (zaakceptowane 13.11)

-- Biuro Nowa Wola: pierwszy numer 534 379 056, dotychczasowy jako drugi
UPDATE cities SET phone = '+48 534 379 056<br>+48 512 379 056' WHERE slug = 'nowa-wola';

-- Lake Village leży w Zaleziu Górnym (pinezka stała w miejscu biura w Nowej Woli).
-- Zalesie Górne jest poza obrazkiem mapy na stronie głównej - pinezka znika z mapy, dopóki mapa nie obejmie tego terenu.
UPDATE investments SET lat = 52.0296000, lng = 21.0409000 WHERE slug = 'segmenty-lake-village';

-- Nie komunikujemy wykończenia mieszkania / domu przed odbiorem - bez składnika ceny „Wykończenie pod klucz” na kartach lokali
DELETE pp FROM property_price_component_property pp
    JOIN property_price_components c ON c.id = pp.property_price_component_id
    WHERE c.name = 'Wykończenie pod klucz';
DELETE FROM property_price_components WHERE name = 'Wykończenie pod klucz';
