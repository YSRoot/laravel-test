<?php

namespace App\Versions\V1\Factories\DTO\Auth;

use App\Versions\V1\DTO\Auth\PasswordTokenDTO;
use App\Versions\V1\Factories\DTO\Contracts\FactoryInterface;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class PasswordTokenDTOFactory implements FactoryInterface
{
    /**
     * @throws UnknownProperties
     */
    public function fromArray(array $array): PasswordTokenDTO
    {
        return new PasswordTokenDTO(
            tokenType: $array['token_type'],
            expiresIn: $array['expires_in'],
            accessToken: $array['access_token'],
            refreshToken: $array['refresh_token'],
        );
    }
}
