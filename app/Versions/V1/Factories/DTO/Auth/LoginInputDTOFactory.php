<?php

namespace App\Versions\V1\Factories\DTO\Auth;

use App\Versions\V1\DTO\Auth\LoginInputDTO;
use App\Versions\V1\Factories\DTO\Contracts\FactoryInterface;
use App\Versions\V1\Http\Requests\Auth\LoginRequest;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class LoginInputDTOFactory implements FactoryInterface
{
    /**
     * @throws UnknownProperties
     */
    public function fromLoginRequest(LoginRequest $request): LoginInputDTO
    {
        return new LoginInputDTO(
            email: $request->get('email'),
            password: $request->get('password'),
            clientId: $request->get('client_id'),
            clientSecret: $request->get('client_secret'),
            scope: $request->get('scope', '*'),
        );
    }
}
