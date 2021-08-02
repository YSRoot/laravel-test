<?php

namespace App\Versions\V1\Factories\DTO;

use App\Versions\V1\DTO\RefreshInputDTO;
use App\Versions\V1\Http\Requests\Auth\RefreshTokenRequest;

class RefreshInputDTOFactory implements FactoryInterface
{
    public function fromRefreshTokenRequest(RefreshTokenRequest $request): RefreshInputDTO
    {
        return new RefreshInputDTO(
            $request->get('refresh_token'),
            $request->get('client_id'),
            $request->get('client_secret'),
            $request->get('scope', '*'),
        );
    }
}
