<?php

namespace App\Versions\V1\Http\Requests\Traits\Auth;

/**
 * @property string $client_id
 * @property string $client_secret
 * @property string $scope
 */
trait OAuthRequestParams
{
    public function oauthParams(): array
    {
        return [
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'scope' => ['sometimes', 'required', 'string'],
        ];
    }
}
