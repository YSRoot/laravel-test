<?php

namespace App\Versions\V1\Services;

use App\Models\UserSocialProfile;
use App\Versions\V1\DTO\Users\UserDTO;
use App\Versions\V1\DTO\Users\UserSocialProfileDTO;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Two\User as SocialiteUser;
use App\Models\User;
use Throwable;

class SocialiteService
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function handleCallback(SocialiteUser $socialUser, string $driver, ?int $userId = null): void
    {
        //test
        $userDTO = UserDTO::factory()->fromSocialUser($socialUser);
        $user = $this->userService->firstOrCreate($userDTO);

        $this->createSocialProfile($socialUser, $driver, $user, $user->id == $userId);
    }

    /**
     * @throws Throwable
     */
    protected function createSocialProfile(
        SocialiteUser $socialUser,
        string $driver,
        User $user,
        bool $isAuthorized
    ): void {
        // Привязка социального профиля возможна только для авторизованных пользователей или нового пользователя
        throw_if(!$user->wasRecentlyCreated && !$isAuthorized, AuthorizationException::class);

        $socialProfile = new UserSocialProfile();
        $socialProfile->fill(
            UserSocialProfileDTO::factory()
                ->fromSocialUser($socialUser, $driver)
                ->toArray()
        );
        $socialProfile->user()->associate($user);
        $socialProfile->save();
    }
}
