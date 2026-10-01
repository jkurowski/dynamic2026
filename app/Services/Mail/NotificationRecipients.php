<?php

namespace App\Services\Mail;

use App\Models\Investment;

/**
 * Adresy, na które idzie powiadomienie o nowym zgłoszeniu z formularza.
 *
 * **Biuro sprzedaży inwestycji ma PIERWSZEŃSTWO i wyklucza adres ogólny** (decyzja
 * klienta z 2026-08-03). Adresy główne z Ustawień strony wchodzą dopiero wtedy, gdy
 * inwestycja nie ma własnych — są siatką bezpieczeństwa, a nie stałym odbiorcą kopii.
 *
 * Do 2026-08-03 serwis SCALAŁ oba zbiory, więc centrala dostawała każde zgłoszenie
 * dotyczące dowolnej inwestycji.
 *
 * Oba pola zapisuje w panelu tagify, więc w bazie leży JSON w postaci
 * `[{"value":"biuro@example.com"},…]`. Trzeba go rozpakować, zanim trafi do Mail::to() —
 * `IframePageController` podawał wcześniej ten napis wprost jako adres odbiorcy.
 */
class NotificationRecipients
{
    /**
     * @return string[] unikalne, poprawne adresy e-mail
     */
    public function for(?Investment $investment = null): array
    {
        // Odsiewanie literówek siedzi w `parse()`, więc inwestycja z jednym błędnym
        // adresem i niczym więcej zachowuje się jak inwestycja bez adresów — zgłoszenie
        // idzie na adres główny, zamiast przepaść.
        $office = $this->parse($investment?->office_emails);

        if ($office !== []) {
            return array_values(array_unique($office));
        }

        return array_values(array_unique($this->parse(settings()->get('page_email'))));
    }

    /**
     * Rozpakowuje wartość z tagify. Przyjmuje też zwykły napis z adresami po przecinku
     * i gotową tablicę, bo starsze instalacje mają w tym polu jedno i drugie.
     */
    public function parse($value): array
    {
        if (blank($value)) {
            return [];
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $value = json_last_error() === JSON_ERROR_NONE ? $decoded : explode(',', $value);
        }

        if (!is_array($value)) {
            return [];
        }

        $addresses = [];

        foreach ($value as $item) {
            $address = is_array($item) ? ($item['value'] ?? null) : $item;

            if (!is_string($address)) {
                continue;
            }

            $address = trim($address);

            // Niepoprawny adres wywala całą wysyłkę, a leadu nikt wtedy nie zobaczy —
            // lepiej pominąć literówkę w ustawieniach niż stracić zgłoszenie.
            if ($address !== '' && filter_var($address, FILTER_VALIDATE_EMAIL)) {
                $addresses[] = $address;
            }
        }

        return $addresses;
    }
}
