<?php

namespace App\Versions\V1\Http\Requests\Auth;

use App\Versions\V1\Http\Requests\Traits\Auth\OAuthRequestParams;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
{
    use OAuthRequestParams;

    public function rules(): array
    {
        return array_merge(
            $this->oauthParams(),
            [
                'email' => ['required', 'email'],
                'password' => ['required', 'string', Password::default()],
            ],
        );
    }
}
