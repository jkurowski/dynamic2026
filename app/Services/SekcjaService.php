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
 *
 * Nazwy pól w formularzu (ścieżka = pole albo lista.numer.pole):
 *   pola[sciezka]              tekst, html, ikona
 *   pola[sciezka][tekst|adres] link
 *   pola[sciezka][alt]         obrazek - opis
 *   zdjecie[sciezka]           obrazek - plik
 *   usun[sciezka]              obrazek - powrót do zdjęcia z szablonu
 */
class SekcjaService
{
    // Adres przycisku: ścieżka na stronie (/kontakt), pełny adres, kotwica, telefon albo e-mail
    private const ADRES = '/^(\/|https?:\/\/|#|tel:|mailto:)/i';

    public function reguly(Sekcja $sekcja): array
    {
        return $this->regulyPol($sekcja->schemat()['pola'], '');
    }

    private function regulyPol(array $pola, string $przedrostek): array
    {
        $reguly = [];

        foreach ($pola as $pole => $definicja) {
            $s = $przedrostek . $pole;

            switch ($definicja['typ']) {
                case 'tekst':
                    $reguly["pola.$s"] = 'nullable|string|max:' . ($definicja['max'] ?? 255);
                    break;
                case 'html':
                    $reguly["pola.$s"] = 'nullable|string|max:20000';
                    break;
                case 'link':
                    $reguly["pola.$s.tekst"] = 'nullable|string|max:60';
                    $reguly["pola.$s.adres"] = ['nullable', 'string', 'max:500', 'regex:' . self::ADRES];
                    break;
                case 'obrazek':
                    $reguly["pola.$s.alt"] = 'nullable|string|max:255';
                    $reguly["zdjecie.$s"] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360';
                    $reguly["usun.$s"] = 'nullable|boolean';
                    break;
                case 'ikona':
                    $reguly["pola.$s"] = ['nullable', 'in:' . implode(',', array_keys($definicja['opcje']))];
                    break;
                case 'lista':
                    foreach (array_keys($definicja['domyslnie']) as $i) {
                        $reguly += $this->regulyPol($definicja['pola'], "$s.$i.");
                    }
                    break;
            }
        }

        return $reguly;
    }

    /** Czytelne nazwy pól w komunikatach walidacji - etykiety z configu */
    public function nazwyPol(Sekcja $sekcja): array
    {
        return $this->nazwy($sekcja->schemat()['pola'], '', '');
    }

    private function nazwy(array $pola, string $przedrostek, string $etykietaNad): array
    {
        $nazwy = [];

        foreach ($pola as $pole => $definicja) {
            $s = $przedrostek . $pole;
            $etykieta = $etykietaNad . ($definicja['etykieta'] ?? $pole);

            if ($definicja['typ'] === 'lista') {
                foreach (array_keys($definicja['domyslnie']) as $i) {
                    $nazwy += $this->nazwy($definicja['pola'], "$s.$i.", ($definicja['element'] ?? $etykieta) . ' ' . ($i + 1) . ' - ');
                }
                continue;
            }

            $nazwy["pola.$s"] = $etykieta;
            $nazwy["pola.$s.tekst"] = $etykieta . ' - tekst';
            $nazwy["pola.$s.adres"] = $etykieta . ' - adres';
            $nazwy["pola.$s.alt"] = $etykieta . ' - opis (ALT)';
            $nazwy["zdjecie.$s"] = $etykieta;
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
        $doUsuniecia = [];
        $sekcja->dane = $this->zbierz($sekcja, $sekcja->schemat()['pola'], $sekcja->dane ?? [], '', $request, $doUsuniecia) ?? [];
        $sekcja->save();

        // Stare pliki dopiero po udanym zapisie
        foreach ($doUsuniecia as $plik) {
            Sekcja::usunPlik($plik);
        }
    }

    private function zbierz(Sekcja $sekcja, array $pola, array $obecne, string $przedrostek, Request $request, array &$doUsuniecia): ?array
    {
        $dane = [];

        foreach ($pola as $pole => $definicja) {
            $s = $przedrostek . $pole;

            switch ($definicja['typ']) {
                case 'tekst':
                case 'ikona':
                    $dane[$pole] = $this->czysc($request->input("pola.$s"));
                    break;

                case 'html':
                    $html = trim((string) $request->input("pola.$s"));
                    // Pusty edytor TinyMCE zostawia <p>&nbsp;</p> - traktujemy jak brak treści
                    $dane[$pole] = trim(html_entity_decode(strip_tags($html)), " \t\n\r\0\x0B\xC2\xA0") === '' ? null : $html;
                    break;

                case 'link':
                    $dane[$pole] = $this->bezPustych([
                        'tekst' => $this->czysc($request->input("pola.$s.tekst")),
                        'adres' => $this->czysc($request->input("pola.$s.adres")),
                    ]);
                    break;

                case 'obrazek':
                    $plik = $obecne[$pole]['plik'] ?? null;

                    if ($request->hasFile("zdjecie.$s")) {
                        $doUsuniecia[] = $plik;
                        $plik = $this->wgraj($sekcja, $s, $definicja, $request->file("zdjecie.$s"));
                    } elseif ($request->boolean("usun.$s")) {
                        $doUsuniecia[] = $plik;
                        $plik = null;
                    }

                    $dane[$pole] = $this->bezPustych([
                        'plik' => $plik,
                        'alt' => $this->czysc($request->input("pola.$s.alt")),
                    ]);
                    break;

                case 'lista':
                    // Stała liczba elementów (tyle, ile domyślnych) - numer elementu = jego miejsce na stronie
                    $elementy = [];
                    foreach (array_keys($definicja['domyslnie']) as $i) {
                        $elementy[$i] = $this->zbierz($sekcja, $definicja['pola'], $obecne[$pole][$i] ?? [], "$s.$i.", $request, $doUsuniecia);
                    }
                    $dane[$pole] = array_filter($elementy) ? $elementy : null;
                    break;
            }
        }

        return $this->bezPustych($dane);
    }

    /** Kadr z configu (zwykle 2x rozmiar na stronie), JPG q85 + WebP q80 */
    private function wgraj(Sekcja $sekcja, string $sciezka, array $definicja, UploadedFile $plik): string
    {
        [$szerokosc, $wysokosc] = $definicja['kadr'];
        $base = date('His') . '_' . Str::slug(str_replace('.', '-', $sekcja->klucz . '-' . $sciezka)) . '-' . Str::lower(Str::random(4));

        File::ensureDirectoryExists(public_path(Sekcja::KATALOG . 'webp'));

        // orientate() - zdjęcia z telefonu mają obrót zapisany w EXIF
        $obraz = Image::make($plik->getRealPath())->orientate()->fit($szerokosc, $wysokosc);
        $obraz->save(public_path(Sekcja::KATALOG . $base . '.jpg'), 85, 'jpg');
        $obraz->save(public_path(Sekcja::KATALOG . 'webp/' . $base . '.webp'), 80, 'webp');

        return $base . '.jpg';
    }

    private function bezPustych(array $dane): ?array
    {
        $dane = array_filter($dane, fn ($v) => $v !== null);

        return $dane ?: null;
    }

    private function czysc($wartosc): ?string
    {
        $wartosc = trim((string) $wartosc);

        return $wartosc === '' ? null : $wartosc;
    }
}
