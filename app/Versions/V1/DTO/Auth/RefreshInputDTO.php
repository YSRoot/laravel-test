<?php

namespace App\Versions\V1\DTO\Auth;

use App\DTO\BaseDTO;
use App\Versions\V1\DTO\Contracts\OAuthAuthorizeContract;
use App\Versions\V1\DTO\Traits\HasFactory;
use App\Versions\V1\Factories\DTO\Auth\RefreshInputDTOFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @method static RefreshInputDTOFactory factory()
 */
class RefreshInputDTO extends BaseDTO implements OAuthAuthorizeContract
{
    use HasFactory;

    public static string $factory = RefreshInputDTOFactory::class;

    public string $refreshToken;
    public string $clientId;
    public string $clientSecret;
    public string $scope;

    #[ArrayShape([
        'refresh_token' => "string",
        'client_id' => "string",
        'client_secret' => "string",
        'scope' => "string"
    ])]
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
