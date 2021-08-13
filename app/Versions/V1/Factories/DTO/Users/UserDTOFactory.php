<?php

namespace App\Versions\V1\Factories\DTO\Users;

use App\Versions\V1\DTO\Users\UserDTO;
use App\Versions\V1\Factories\DTO\Contracts\FactoryInterface;
use App\Versions\V1\Http\Requests\Auth\RegisterRequest;
use Laravel\Socialite\Contracts\User;
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

    /**
     * @throws UnknownProperties
     */
    public function fromSocialUser(User $user): UserDTO
    {
        return new UserDTO(
            name: $user->getName() ?? $user->getNickname() ?? 'Unknown',
            email: $user->getEmail()
        );
    }

    /**
     * @throws UnknownProperties
     */
    public function fromUser(\App\Models\User $user): UserDTO
    {
        return new UserDTO(
            name: $user->name,
            email: $user->email,
        );
    }
}
