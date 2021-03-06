<?php

namespace App\Versions\V1\Factories\DTO\Auth;

use App\Versions\V1\DTO\Auth\RefreshInputDTO;
use App\Versions\V1\Factories\DTO\Contracts\FactoryInterface;
use App\Versions\V1\Http\Requests\Auth\RefreshTokenRequest;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class RefreshInputDTOFactory implements FactoryInterface
{
    /**
     * @throws UnknownProperties
     */
    public function fromRefreshTokenRequest(RefreshTokenRequest $request): RefreshInputDTO
    {
        return new RefreshInputDTO(
            refreshToken: $request->get('refresh_token'),
            clientId: $request->get('client_id'),
            clientSecret: $request->get('client_secret'),
            scope: $request->get('scope', '*'),
        );
    }
}
