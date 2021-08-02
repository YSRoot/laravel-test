<?php

namespace App\Versions\V1\Factories\DTO;

use App\Versions\V1\DTO\PasswordTokenDTO;

class PasswordTokenDTOFactory implements FactoryInterface
{
    public function fromArray(array $array): PasswordTokenDTO
    {
        return new PasswordTokenDTO(
            $array['token_type'],
            $array['expires_in'],
            $array['access_token'],
            $array['refresh_token'],
        );
    }
}
