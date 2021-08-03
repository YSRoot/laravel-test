<?php

namespace App\Versions\V1\Factories\DTO;

use App\Versions\V1\DTO\UserDTO;
use App\Versions\V1\Http\Requests\Auth\RegisterRequest;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UserDTOFactory implements FactoryInterface
{
    /**
     * @throws UnknownProperties
     */
    public function fromRegisterRequest(RegisterRequest $request): UserDTO
    {
        return new UserDTO(
            name: $request->get('name'),
            email: $request->get('email'),
            password: $request->get('password'),
        );
    }
}
