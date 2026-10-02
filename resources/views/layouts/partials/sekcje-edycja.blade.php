{{-- Edytor sekcji na froncie (public/js/sekcje-edycja.js) - tylko dla zalogowanych z uprawnieniem sekcja-edit.
     Gość nie dostaje ani modala, ani skryptów. --}}
{{-- Arkusz tutaj, nie w @push('style') - stos w <head> jest już wypisany, gdy layout dochodzi do tego miejsca --}}
<link rel="stylesheet" href="{{ asset('css/sekcje-edycja.css') }}?v={{ filemtime(public_path('css/sekcje-edycja.css')) }}">

<div class="modal fade sekcja-modal" id="sekcjaModal" tabindex="-1" aria-labelledby="sekcjaModalTytul" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sekcjaModalTytul">Edycja sekcji</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/editor/tinymce.min.js') }}" charset="utf-8"></script>
    {{-- ?v= - po zmianie pliku przeglądarka nie bierze starej wersji z pamięci --}}
    <script src="{{ asset('js/sekcje-edycja.js') }}?v={{ filemtime(public_path('js/sekcje-edycja.js')) }}"></script>
@endpush
