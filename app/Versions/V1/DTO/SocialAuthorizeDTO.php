<?php

namespace App\Versions\V1\DTO;

use App\DTO\BaseDTO;
use App\Versions\V1\DTO\Traits\HasFactory;
use App\Versions\V1\Factories\DTO\SocialAuthorizeDTOFactory;

/** @method static SocialAuthorizeDTOFactory factory() */
class SocialAuthorizeDTO extends BaseDTO implements OAuthAuthorizeContract
{
    use HasFactory;

    public static string $factory = SocialAuthorizeDTOFactory::class;

    public string $driver;
    public string $accessToken;
    public string $clientId;
    public string $clientSecret;
    public string $scope;

    public function authorizeParams(): array
    {
        return array_merge(
            $this->except('driver')->toArray(),
            ['provider' => $this->driver],
        );
    }
}
