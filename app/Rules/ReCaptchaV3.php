<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Weryfikacja tokenu reCAPTCHA v3 po stronie serwera.
 *
 * Klucze pochodzą WYŁĄCZNIE z panelu (Ustawienia → SEO). Wcześniej było to
 * rozjechane: sekret czytała ta reguła z panelu, a klucz witryny brał się
 * z `.env` — klient wpisujący klucze w panelu nie wpływał więc na przycisk
 * w formularzu i odwrotnie.
 *
 * Gdy klucze nie są uzupełnione, reguła PRZEPUSZCZA. To świadoma decyzja:
 * instalacja bez wykupionej reCAPTCHY ma mieć działający formularz kontaktowy
 * zamiast martwego. Ochrony wtedy nie ma i panel mówi o tym wprost — patrz
 * komunikat w Ustawieniach → SEO i na pulpicie.
 */
class ReCaptchaV3 implements Rule
{
    private ?string $action = null;
    private ?float $minScore = null;

    public function __construct(?string $action = null, ?float $minScore = null)
    {
        $this->action = $action;
        $this->minScore = $minScore;
    }

    public static function siteKey(): ?string
    {
        $key = settings()->get('recaptcha_site_key');

        return is_string($key) && trim($key) !== '' ? trim($key) : null;
    }

    public static function secretKey(): ?string
    {
        $key = settings()->get('recaptcha_secret_key');

        return is_string($key) && trim($key) !== '' ? trim($key) : null;
    }

    /** Ochrona działa tylko wtedy, gdy OBA klucze są uzupełnione */
    public static function isConfigured(): bool
    {
        return self::siteKey() !== null && self::secretKey() !== null;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!self::isConfigured()) {
            $this->logMissingProtection();

            return true;
        }

        $siteVerify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => self::secretKey(),
            'response' => $value,
        ]);

        if ($siteVerify->failed()) {
            return false;
        }

        if ($siteVerify->successful()) {
            $body = $siteVerify->json();

            if ($body['success'] !== true) {
                return false;
            }

            if (!is_null($this->action) && $this->action != $body['action']) {
                return false;
            }

            if (!is_null($this->minScore) && $this->minScore > $body['score']) {
                return false;
            }
        }

        return true;
    }

    /**
     * Ślad w logu, że formularz przyjął zgłoszenie bez ochrony. Raz na godzinę,
     * żeby przy zaspamowanym formularzu nie zapchać logu — chodzi o sygnał,
     * nie o zliczanie.
     */
    private function logMissingProtection(): void
    {
        cache()->remember('recaptcha_missing_logged', now()->addHour(), function () {
            Log::warning(
                'Formularz przyjął zgłoszenie BEZ ochrony reCAPTCHA — klucze nie są '
                . 'uzupełnione w Ustawieniach → SEO.'
            );

            return true;
        });
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Google reCAPTCHA validation failed.';
    }
}
