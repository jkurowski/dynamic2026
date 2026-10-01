<?php

namespace App\Http\Requests;

use App\Models\RodoRules;
use App\Rules\ReCaptchaV3;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Formularz kontaktowy frontu (kontakt, sekcje kontaktu, inwestycja, lokal). Wzorzec: poligonowa.
 * Jedno pole "Imię i nazwisko" (name), telefon wymagany, e-mail opcjonalny - jak w projekcie Figma.
 */
class ContactFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Limity pod rzeczywiste kolumny (clients.name/phone varchar 191, client_msg.message text) -
        // dłuższa wartość dałaby błąd zapisu (500) zamiast komunikatu walidacji.
        $rules = [
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email:rfc|max:191',
            'message' => 'required|string|max:5000',
            // Token wymagany tylko, gdy reCAPTCHA jest skonfigurowana w panelu (Ustawienia -> SEO)
            'g-recaptcha-response' => ReCaptchaV3::isConfigured()
                ? ['required', new ReCaptchaV3()]
                : ['nullable'],

            // Pola kontekstowe (ukryte)
            'page' => 'nullable|string|max:255',
            'back' => 'nullable|boolean',
            'investment_id' => 'nullable|integer|exists:investments,id',
            'property_id' => 'nullable|integer|exists:properties,id',
        ];

        // Wymagane zgody - DOKŁADNIE ten sam zestaw, który pokazał formularz (ta sama inwestycja).
        // Inaczej serwer żądałby zgody, której formularz nie wyświetlił, i zgłoszenia by nie dochodziły.
        $rodoRules = RodoRules::requiredForFormAndInvestment(
            RodoRules::FORM_CONTACT,
            $this->filled('investment_id') ? (int) $this->input('investment_id') : null
        );

        foreach ($rodoRules as $rule) {
            $rules['rule_' . $rule->id] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Podaj imię i nazwisko.',
            'phone.required' => 'Podaj numer telefonu.',
            'email.email' => 'Podaj poprawny adres e-mail.',
            'message.required' => 'Napisz wiadomość.',
            'rule_*.required' => 'Zaznacz wymaganą zgodę na przetwarzanie danych.',
            'g-recaptcha-response.required' => 'Nie udało się potwierdzić, że nie jesteś robotem. Spróbuj ponownie.',

            'name.max' => 'Imię i nazwisko może mieć najwyżej :max znaków.',
            'email.max' => 'Adres e-mail może mieć najwyżej :max znaków.',
            'phone.max' => 'Numer telefonu może mieć najwyżej :max znaków.',
            'message.max' => 'Wiadomość może mieć najwyżej :max znaków.',
        ];
    }
}
