<?php

namespace App\Versions\V1\DTO\Auth;

use App\DTO\BaseDTO;
use App\Versions\V1\DTO\Contracts\OAuthAuthorizeContract;
use App\Versions\V1\DTO\Traits\HasFactory;
use App\Versions\V1\Factories\DTO\Auth\SocialAuthorizeDTOFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @method static SocialAuthorizeDTOFactory factory()
 */
class SocialAuthorizeDTO extends BaseDTO implements OAuthAuthorizeContract
{
    use HasFactory;

    public static string $factory = SocialAuthorizeDTOFactory::class;

    public string $driver;
    public string $accessToken;
    public string $clientId;
    public string $clientSecret;
    public string $scope;

    #[ArrayShape([
        'access_token' => "string",
        'client_id' => "string",
        'client_secret' => "string",
        'provider' => "string",
        'scope' => "string"
    ])]
    public function authorizeParams(): array
    {
        return [
            'access_token' => $this->accessToken,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'provider' => $this->driver,
            'scope' => $this->scope,
        ];
    }
}
