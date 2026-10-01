<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ReCaptchaV3 implements Rule
{
    private ?string $action = null;
    private ?float $minScore = null;

    public function __construct(?string $action = null, ?float $minScore = null)
    {
        $this->action = $action;
        $this->minScore = $minScore;
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
        $siteVerify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha_v3.secretKey'),
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
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Google reCAPTCHA validation failed.';
    }
}
