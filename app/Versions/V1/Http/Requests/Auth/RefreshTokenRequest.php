<?php

namespace App\Versions\V1\Http\Requests\Auth;

use App\Versions\V1\Http\Requests\Traits\Auth\OAuthRequestParams;
use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
{
    use OAuthRequestParams;

    public function rules(): array
    {
        return array_merge(
            [
                'refresh_token' => ['required', 'string',],
            ],
            $this->oauthParams(),
        );
    }
}
