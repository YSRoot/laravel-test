<?php

namespace App\Versions\V1\Factories\DTO;

use App\Versions\V1\DTO\Users\UserSocialProfileDTO;
use Laravel\Socialite\Contracts\User;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UserSocialProfileDTOFactory implements FactoryInterface
{
    /**
     * @throws UnknownProperties
     */
    public function fromSocialUser(User $user, string $driver): UserSocialProfileDTO
    {
        return new UserSocialProfileDTO(
            driver: $driver,
            driverId: $user->getId(),
            nickname: $user->getNickname(),
            name: $user->getName(),
            email: $user->getEmail(),
            avatar: $user->getAvatar(),
        );
    }
}
