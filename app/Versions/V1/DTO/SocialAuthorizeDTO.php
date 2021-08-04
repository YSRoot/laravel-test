<?php

namespace App\Versions\V1\DTO;

use App\DTO\BaseDTO;

class SocialAuthorizeDTO extends BaseDTO implements OAuthAuthorizeContract
{

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
