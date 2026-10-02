{{-- Formularz sekcji ze schematu pól (config/sekcje.php). Używany w panelu (admin.sekcja.form)
     i w modalu na froncie ($modal = true - sam formularz, bez layoutu panelu, czysty Bootstrap). --}}
@php $modal = $modal ?? false; @endphp
<form method="POST" action="{{ route('admin.sekcja.update', $sekcja->klucz) }}" enctype="multipart/form-data" class="sekcja-formularz" data-nazwa="{{ $sekcja->nazwa() }}">
    @csrf
    @method('PUT')

    @foreach($sekcja->schemat()['pola'] as $pole => $definicja)
        @include('admin.sekcja.pole', ['pole' => $pole, 'definicja' => $definicja, 'wartosci' => $sekcja->wartosci(), 'sciezka' => [$pole]])
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
