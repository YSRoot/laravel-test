<?php

namespace App\Versions\V1\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:256'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'max:128',
                'confirmed',
                (new Password(8))
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
        ];
    }
}
