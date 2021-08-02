<?php

namespace App\Versions\V1\Http\Resources\Auth;

use App\Versions\V1\DTO\PasswordTokenDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PasswordTokenDTO
 */
class OAuthTokenResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
        ];
    }
}
