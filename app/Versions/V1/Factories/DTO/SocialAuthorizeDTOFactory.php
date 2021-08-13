<?php

namespace App\Versions\V1\Factories\DTO;

use App\Versions\V1\DTO\Auth\SocialAuthorizeDTO;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SocialAuthorizeDTOFactory implements FactoryInterface
{
    /**
     * @throws UnknownProperties
     */
    public function fromParams(
        string $driver,
        string $accessToken,
        string $clientId,
        string $clientSecret,
        ?string $scope
    ): SocialAuthorizeDTO {
        return new SocialAuthorizeDTO(
            driver: $driver,
            accessToken: $accessToken,
            clientId: $clientId,
            clientSecret: $clientSecret,
            scope: $scope ?? '*',
        );
    }
}
