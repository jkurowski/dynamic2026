@props([
    'pageTitle' => 'Kariera',
    'action' => '',
    'method' => 'POST',
])

<form id="apply-form" autocomplete="off" class="contact-form validateForm" action="{{ $action }}" method="{{ $method }}">
    @csrf
    <input type="hidden" name="page" value="{{ $pageTitle }}">
    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success border-0 mb-3">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-warning border-0 mb-3">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        <div class="col-12">
            <div class="form-floating position-relative mb-2 mb-md-3">
                <input type="text" class="validate[required] form-control @error('username') is-invalid @enderror" id="user-name"
                    placeholder="Imię i nazwisko" name="username" value="{{ old('username') }}">
                <label for="user-name">Imię i nazwisko <span class="text-danger">*</span></label>
                @error('username')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-12 col-lg-6">
            <div class="form-floating position-relative mb-2 mb-md-3">
                <input type="email" class="validate[required] form-control @error('email') is-invalid @enderror" id="user-email"
                    placeholder="E-mail" name="email" value="{{ old('email') }}" required>
                <label for="user-email">E-mail <span class="text-danger">*</span></label>
                @error('email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-12 col-lg-6">
            <div class="form-floating position-relative mb-2 mb-md-3">
                <input type="tel" class="validate[required] form-control @error('tel') is-invalid @enderror" id="user-tel"
                    placeholder="Telefon" name="tel" value="{{ old('tel') }}">
                <label for="user-tel">Telefon <span class="text-danger">*</span></label>
                @error('tel')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating position-relative mb-2 mb-md-3">
                <textarea class="validate[required] form-control @error('message') is-invalid @enderror" placeholder="Wiadomość" 
                    id="user-message" style="height: 100px" name="message">{{ old('message') }}</textarea>
                <label for="user-message">Wiadomość <span class="text-danger">*</span></label>
                @error('message')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="mb-1">
                <label for="form-file"
                    class="form-label cursor-pointer d-inline-flex gap-3 align-items-center"
                    role="button" tabindex="0">
                    <img src="{{ asset('img/attachment.svg') }}" alt=""
                        width="10" height="19" loading="lazy" decoding="async">
                    Załącz CV
                </label>
                <input class="form-control d-none @error('cv') is-invalid @enderror" type="file" id="form-file" name="cv">
                @error('cv')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-check text-start pb-4 pb-md-5">
            <input class="form-check-input validate[required]" type="checkbox" value="" id="terms-check" name="terms">
            <label class="form-check-label small fw-medium" for="terms-check">Akceptuję <a class="link-hover-primary text-decoration-underline" href="/pl/polityka-prywatnosci/" target="_blank">politykę prywatności.</a><span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-12 text-center text-md-start">
        <button data-btn-submit="" type="submit" class="btn btn-primary btn-with-icon">
            Aplikuj
            <svg xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062"
                viewBox="0 0 6.073 11.062">
                <path id="chevron_right_FILL0_wght100_GRAD0_opsz24"
                    d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z"
                    transform="translate(-356 684)" fill="currentColor" />
            </svg>
        </button>
    </div>
</form>

@push('scripts')
    <script src="{{ asset('js/validation.js') }}" defer></script>
    <script src="{{ asset('js/pl.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            $("#apply-form").validationEngine({
                validateNonVisibleFields: true,
                updatePromptsPosition:true,
                promptPosition : "bottomRight",
                autoPositionUpdate: false
            });
        });
    </script>
@endpush


