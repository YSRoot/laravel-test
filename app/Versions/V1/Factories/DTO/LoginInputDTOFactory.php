<?php

namespace App\Versions\V1\Factories\DTO;

use App\Versions\V1\DTO\LoginInputDTO;
use App\Versions\V1\Http\Requests\Auth\LoginRequest;

class LoginInputDTOFactory implements FactoryInterface
{
    public function fromLoginRequest(LoginRequest $request): LoginInputDTO
    {
        return new LoginInputDTO(
            $request->get('email'),
            $request->get('password'),
            $request->get('client_id'),
            $request->get('client_secret'),
            $request->get('scope', '*'),
        );
    }
}
