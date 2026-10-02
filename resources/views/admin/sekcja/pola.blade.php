{{-- Formularz sekcji ze schematu pól (config/sekcje.php). Używany w panelu (admin.sekcja.form)
     i w modalu na froncie ($modal = true - sam formularz, bez layoutu panelu, czysty Bootstrap). --}}
@php $modal = $modal ?? false; @endphp
<form method="POST" action="{{ route('admin.sekcja.update', $sekcja->klucz) }}" enctype="multipart/form-data" class="sekcja-formularz" data-nazwa="{{ $sekcja->nazwa() }}">
    @csrf
    @method('PUT')

    @foreach($sekcja->schemat()['pola'] as $pole => $definicja)
        @php $etykieta = $definicja['etykieta'] ?? $pole; $id = 'sekcja-' . $pole; @endphp
        <div class="mb-4">
            @switch($definicja['typ'])
                @case('tekst')
                    <label for="{{ $id }}" class="form-label fw-semibold">{{ $etykieta }}</label>
                    <input type="text" class="form-control" id="{{ $id }}" name="pola[{{ $pole }}]" maxlength="{{ $definicja['max'] ?? 255 }}"
                           value="{{ old("pola.$pole", $sekcja->tekst($pole)) }}">
                    @break

                @case('html')
                    <label for="{{ $id }}" class="form-label fw-semibold">{{ $etykieta }}</label>
                    <textarea class="form-control sekcja-edytor" id="{{ $id }}" name="pola[{{ $pole }}]" rows="8">{{ old("pola.$pole", $sekcja->html($pole)) }}</textarea>
                    @break

                @case('link')
                    @php $link = $sekcja->dane[$pole] ?? []; $domyslny = $definicja['domyslnie'] ?? []; @endphp
                    <div class="form-label fw-semibold">{{ $etykieta }}</div>
                    <div class="row g-2">
                        <div class="col-sm-5">
                            <label for="{{ $id }}-tekst" class="form-label small text-muted mb-1">Tekst przycisku</label>
                            <input type="text" class="form-control" id="{{ $id }}-tekst" name="pola[{{ $pole }}][tekst]" maxlength="60"
                                   value="{{ old("pola.$pole.tekst", ($link['tekst'] ?? '') ?: ($domyslny['tekst'] ?? '')) }}">
                        </div>
                        <div class="col-sm-7">
                            <label for="{{ $id }}-adres" class="form-label small text-muted mb-1">Adres - /podstrona albo https://...</label>
                            <input type="text" class="form-control" id="{{ $id }}-adres" name="pola[{{ $pole }}][adres]" maxlength="500"
                                   value="{{ old("pola.$pole.adres", ($link['adres'] ?? '') ?: ($domyslny['adres'] ?? '')) }}">
                        </div>
                    </div>
                    @break

                @case('obrazek')
                    @php $obrazek = $sekcja->obrazek($pole); [$kw, $kh] = $definicja['kadr']; @endphp
                    <div class="form-label fw-semibold">{{ $etykieta }}</div>
                    <div class="d-flex gap-3 align-items-start">
                        <img src="{{ $obrazek->jpg }}" alt="" class="rounded border" style="width:110px;height:110px;object-fit:cover;flex:0 0 110px">
                        <div class="flex-grow-1">
                            <input type="file" class="form-control" id="{{ $id }}" name="zdjecie[{{ $pole }}]" accept="image/jpeg,image/png,image/webp">
                            <div class="form-text">JPG, PNG albo WebP. Zdjęcie zostanie przycięte do {{ $kw }}×{{ $kh }} px (środek kadru){{ $obrazek->wlasny ? '' : ' - teraz zdjęcie z szablonu' }}.</div>
                            <label for="{{ $id }}-alt" class="form-label small text-muted mb-1 mt-2">Opis zdjęcia (ALT)</label>
                            <input type="text" class="form-control" id="{{ $id }}-alt" name="pola[{{ $pole }}][alt]" maxlength="255"
                                   value="{{ old("pola.$pole.alt", $obrazek->alt) }}">
                            @if($obrazek->wlasny)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="{{ $id }}-usun" name="usun[{{ $pole }}]">
                                    <label class="form-check-label" for="{{ $id }}-usun">Usuń zdjęcie (wróci zdjęcie z szablonu)</label>
                                </div>
                            @endif
                        </div>
                    </div>
                    @break
            @endswitch
        </div>
    @endforeach

    <div class="sekcja-bledy alert alert-danger d-none" role="alert"></div>

    @if($modal)
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.sekcja.edit', $sekcja->klucz) }}" class="small text-muted" target="_blank">Otwórz w panelu</a>
            <div>
                <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Anuluj</button>
                <button type="submit" class="btn btn-primary">Zapisz</button>
            </div>
        </div>
    @else
        @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
    @endif
</form>
