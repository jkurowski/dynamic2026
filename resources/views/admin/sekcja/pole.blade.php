{{-- Jedno pole formularza sekcji. $sciezka - klucze od korzenia (np. ['liczby', 0, 'opis']), $wartosci - PolaSekcji
     poziomu, na którym leży pole (sekcja albo element listy), $pole - nazwa pola na tym poziomie. --}}
@php
    $nazwa = fn (string $korzen, array $dalej = []) => $korzen . collect([...$sciezka, ...$dalej])->map(fn ($k) => "[$k]")->implode('');
    $kropki = implode('.', $sciezka);
    $id = 'sekcja-' . implode('-', $sciezka);
    $etykieta = $definicja['etykieta'] ?? $pole;
@endphp
<div class="mb-4">
    @switch($definicja['typ'])
        @case('tekst')
            <label for="{{ $id }}" class="form-label fw-semibold">{{ $etykieta }}</label>
            <input type="text" class="form-control" id="{{ $id }}" name="{{ $nazwa('pola') }}" maxlength="{{ $definicja['max'] ?? 255 }}"
                   value="{{ old("pola.$kropki", $wartosci->tekst($pole)) }}">
            @break

        @case('html')
            <label for="{{ $id }}" class="form-label fw-semibold">{{ $etykieta }}</label>
            {{-- edytor 'linie' - bez akapitów, Enter = nowa linia (<br>) --}}
            <textarea class="form-control sekcja-edytor" id="{{ $id }}" name="{{ $nazwa('pola') }}" rows="{{ ($definicja['edytor'] ?? '') === 'linie' ? 4 : 8 }}"
                      data-edytor="{{ $definicja['edytor'] ?? 'akapity' }}">{{ old("pola.$kropki", $wartosci->html($pole)) }}</textarea>
            @break

        @case('link')
            @php $link = $wartosci->link($pole); $surowy = old("pola.$kropki"); @endphp
            <div class="form-label fw-semibold">{{ $etykieta }}</div>
            <div class="row g-2">
                <div class="col-sm-5">
                    <label for="{{ $id }}-tekst" class="form-label small text-muted mb-1">Tekst przycisku</label>
                    <input type="text" class="form-control" id="{{ $id }}-tekst" name="{{ $nazwa('pola', ['tekst']) }}" maxlength="60"
                           value="{{ $surowy['tekst'] ?? $link->tekst }}">
                </div>
                <div class="col-sm-7">
                    <label for="{{ $id }}-adres" class="form-label small text-muted mb-1">Adres - /podstrona albo https://...</label>
                    {{-- W polu adres w postaci, w jakiej go wpisano (/poznaj-nas), nie pełny URL --}}
                    <input type="text" class="form-control" id="{{ $id }}-adres" name="{{ $nazwa('pola', ['adres']) }}" maxlength="500"
                           value="{{ $surowy['adres'] ?? \Illuminate\Support\Str::after($link->adres, rtrim(url('/'), '/')) }}">
                </div>
            </div>
            @break

        @case('obrazek')
            @php $obrazek = $wartosci->obrazek($pole); [$kw, $kh] = $definicja['kadr']; @endphp
            <div class="form-label fw-semibold">{{ $etykieta }}</div>
            <div class="d-flex gap-3 align-items-start">
                <img src="{{ $obrazek->jpg }}" alt="" class="rounded border" style="width:110px;height:110px;object-fit:cover;flex:0 0 110px">
                <div class="flex-grow-1">
                    <input type="file" class="form-control" id="{{ $id }}" name="{{ $nazwa('zdjecie') }}" accept="image/jpeg,image/png,image/webp">
                    <div class="form-text">JPG, PNG albo WebP. Zdjęcie zostanie przycięte do {{ $kw }}×{{ $kh }} px (środek kadru){{ $obrazek->wlasny ? '' : ' - teraz zdjęcie z szablonu' }}.</div>
                    <label for="{{ $id }}-alt" class="form-label small text-muted mb-1 mt-2">Opis zdjęcia (ALT)</label>
                    <input type="text" class="form-control" id="{{ $id }}-alt" name="{{ $nazwa('pola', ['alt']) }}" maxlength="255"
                           value="{{ old("pola.$kropki.alt", $obrazek->alt) }}">
                    @if($obrazek->wlasny)
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="{{ $id }}-usun" name="{{ $nazwa('usun') }}">
                            <label class="form-check-label" for="{{ $id }}-usun">Usuń zdjęcie (wróci zdjęcie z szablonu)</label>
                        </div>
                    @endif
                </div>
            </div>
            @break

        @case('ikona')
            @php $wybrana = old("pola.$kropki", $wartosci->wartosc($pole)); @endphp
            <div class="form-label fw-semibold">{{ $etykieta }}</div>
            <div class="d-flex flex-wrap gap-2">
                @foreach($definicja['opcje'] as $plik => [$opis])
                    <input type="radio" class="btn-check" name="{{ $nazwa('pola') }}" id="{{ $id }}-{{ $loop->index }}" value="{{ $plik }}" @checked($wybrana === $plik)>
                    <label class="btn btn-outline-secondary p-2" for="{{ $id }}-{{ $loop->index }}" title="{{ $opis }}">
                        <img src="{{ asset($plik) }}" alt="{{ $opis }}" width="40" height="40">
                    </label>
                @endforeach
            </div>
            @break

        @case('lista')
            <fieldset class="border rounded p-3">
                <legend class="float-none w-auto px-2 fs-6 fw-semibold mb-0">{{ $etykieta }}</legend>
                @foreach($wartosci->lista($pole) as $i => $element)
                    <div class="sekcja-element {{ $loop->last ? '' : 'border-bottom mb-3' }}">
                        <div class="text-muted small text-uppercase mb-2">{{ $definicja['element'] ?? $etykieta }} {{ $i + 1 }}</div>
                        @foreach($definicja['pola'] as $podpole => $poddefinicja)
                            @include('admin.sekcja.pole', ['pole' => $podpole, 'definicja' => $poddefinicja, 'wartosci' => $element, 'sciezka' => [...$sciezka, $i, $podpole]])
                        @endforeach
                    </div>
                @endforeach
            </fieldset>
            @break
    @endswitch
</div>
