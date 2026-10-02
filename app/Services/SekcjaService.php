<?php

namespace App\Services;

use App\Models\Sekcja;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Zapis edytowalnej sekcji: reguły walidacji i dane budowane ze schematu pól (config/sekcje.php).
 * Nazwy pól w formularzu: pola[nazwa], pola[link][tekst|adres], pola[obrazek][alt],
 * zdjecie[obrazek] (plik), usun[obrazek] (powrót do zdjęcia z szablonu).
 */
class SekcjaService
{
    // Adres przycisku: ścieżka na stronie (/kontakt), pełny adres, kotwica, telefon albo e-mail
    private const ADRES = '/^(\/|https?:\/\/|#|tel:|mailto:)/i';

    public function reguly(Sekcja $sekcja): array
    {
        $reguly = [];

        foreach ($sekcja->schemat()['pola'] as $pole => $definicja) {
            switch ($definicja['typ']) {
                case 'tekst':
                    $reguly["pola.$pole"] = 'nullable|string|max:' . ($definicja['max'] ?? 255);
                    break;
                case 'html':
                    $reguly["pola.$pole"] = 'nullable|string|max:20000';
                    break;
                case 'link':
                    $reguly["pola.$pole.tekst"] = 'nullable|string|max:60';
                    $reguly["pola.$pole.adres"] = ['nullable', 'string', 'max:500', 'regex:' . self::ADRES];
                    break;
                case 'obrazek':
                    $reguly["pola.$pole.alt"] = 'nullable|string|max:255';
                    $reguly["zdjecie.$pole"] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360';
                    $reguly["usun.$pole"] = 'nullable|boolean';
                    break;
            }
        }

        return $reguly;
    }

    /** Czytelne nazwy pól w komunikatach walidacji - etykiety z configu */
    public function nazwyPol(Sekcja $sekcja): array
    {
        $nazwy = [];

        foreach ($sekcja->schemat()['pola'] as $pole => $definicja) {
            $etykieta = $definicja['etykieta'] ?? $pole;
            $nazwy["pola.$pole"] = $etykieta;
            $nazwy["pola.$pole.tekst"] = $etykieta . ' - tekst';
            $nazwy["pola.$pole.adres"] = $etykieta . ' - adres';
            $nazwy["pola.$pole.alt"] = $etykieta . ' - opis (ALT)';
            $nazwy["zdjecie.$pole"] = $etykieta;
        }

        return $nazwy;
    }

    public function komunikaty(): array
    {
        return [
            'regex' => 'Pole :attribute musi zaczynać się od / (strona w serwisie), https://, #, tel: albo mailto:.',
        ];
    }

    /** Zapis po walidacji. Puste pole = wartość z szablonu (domyślna z configu). */
    public function zapisz(Sekcja $sekcja, Request $request): void
    {
        $dane = $sekcja->dane ?? [];
        $wejscie = $request->input('pola', []);
        $doUsuniecia = [];

        foreach ($sekcja->schemat()['pola'] as $pole => $definicja) {
            switch ($definicja['typ']) {
                case 'tekst':
                    $dane[$pole] = $this->czysc($wejscie[$pole] ?? null);
                    break;

                case 'html':
                    $html = trim((string) ($wejscie[$pole] ?? ''));
                    // Pusty edytor TinyMCE zostawia <p>&nbsp;</p> - traktujemy jak brak treści
                    $dane[$pole] = trim(html_entity_decode(strip_tags($html)), " \t\n\r\0\x0B\xC2\xA0") === '' ? null : $html;
                    break;

                case 'link':
                    $dane[$pole] = array_filter([
                        'tekst' => $this->czysc($wejscie[$pole]['tekst'] ?? null),
                        'adres' => $this->czysc($wejscie[$pole]['adres'] ?? null),
                    ], fn ($v) => $v !== null) ?: null;
                    break;

                case 'obrazek':
                    $obecny = $dane[$pole] ?? [];
                    $plik = $obecny['plik'] ?? null;

                    if ($request->hasFile("zdjecie.$pole")) {
                        $doUsuniecia[] = $plik;
                        $plik = $this->wgraj($sekcja, $pole, $definicja, $request->file("zdjecie.$pole"));
                    } elseif ($request->boolean("usun.$pole")) {
                        $doUsuniecia[] = $plik;
                        $plik = null;
                    }

                    $dane[$pole] = array_filter([
                        'plik' => $plik,
                        'alt' => $this->czysc($wejscie[$pole]['alt'] ?? null),
                    ], fn ($v) => $v !== null) ?: null;
                    break;
            }
        }

        $sekcja->dane = array_filter($dane, fn ($v) => $v !== null);
        $sekcja->save();

        // Stare pliki dopiero po udanym zapisie
        foreach ($doUsuniecia as $plik) {
            Sekcja::usunPlik($plik);
        }
    }

    /** Kadr z configu (zwykle 2x rozmiar na stronie), JPG q85 + WebP q80 */
    private function wgraj(Sekcja $sekcja, string $pole, array $definicja, UploadedFile $plik): string
    {
        [$szerokosc, $wysokosc] = $definicja['kadr'];
        $base = date('His') . '_' . Str::slug(str_replace('.', '-', $sekcja->klucz) . '-' . $pole) . '-' . Str::lower(Str::random(4));

        File::ensureDirectoryExists(public_path(Sekcja::KATALOG . 'webp'));

        // orientate() - zdjęcia z telefonu mają obrót zapisany w EXIF
        $obraz = Image::make($plik->getRealPath())->orientate()->fit($szerokosc, $wysokosc);
        $obraz->save(public_path(Sekcja::KATALOG . $base . '.jpg'), 85, 'jpg');
        $obraz->save(public_path(Sekcja::KATALOG . 'webp/' . $base . '.webp'), 80, 'webp');

        return $base . '.jpg';
    }

    private function czysc($wartosc): ?string
    {
        $wartosc = trim((string) $wartosc);

        return $wartosc === '' ? null : $wartosc;
    }
}
