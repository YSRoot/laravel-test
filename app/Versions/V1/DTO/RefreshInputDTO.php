<?php

namespace App\Versions\V1\DTO;

use App\DTO\BaseDTO;
use App\Versions\V1\DTO\Traits\HasFactory;
use App\Versions\V1\Factories\DTO\RefreshInputDTOFactory;

/** @method static RefreshInputDTOFactory factory() */
class RefreshInputDTO extends BaseDTO implements OAuthAuthorizeContract
{
    use HasFactory;

    public static string $factory = RefreshInputDTOFactory::class;

    public string $refreshToken;
    public string $clientId;
    public string $clientSecret;
    public string $scope;

    public function authorizeParams(): array
    {
        return [
            'refresh_token' => $this->refreshToken,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => $this->scope,
        ];
    }
}
