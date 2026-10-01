<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Pobierz użytkownika z bazy na podstawie ID z URL
        $user = User::find($this->route('id'));

        // Jeśli użytkownik nie istnieje, rzucamy błąd
        if (!$user) {
            throw new AuthorizationException('User not found');
        }

        // Sprawdzamy, czy hash zgadza się z tym, który mamy w URL
        if (!hash_equals(sha1($user->getEmailForVerification()), (string) $this->route('hash'))) {
            throw new AuthorizationException('Invalid hash');
        }

        return true; // Autoryzacja powiodła się
    }

    public function rules()
    {
        return [];
    }
}
