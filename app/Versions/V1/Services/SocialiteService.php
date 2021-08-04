<?php

namespace App\Versions\V1\Services;

use App\Models\UserSocialProfile;
use App\Versions\V1\DTO\UserDTO;
use App\Versions\V1\DTO\UserSocialProfileDTO;
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
    public function handleCallback(SocialiteUser $socialUser, string $driver): void
    {
        $userDTO = UserDTO::factory()->fromSocialUser($socialUser);
        $user = $this->userService->firstOrCreate($userDTO);

        $this->createSocialProfile($socialUser, $driver, $user);
    }

    /**
     * @throws Throwable
     */
    protected function createSocialProfile(SocialiteUser $socialUser, string $driver, User $user): void
    {
        // Привязка социального профиля возможна только для авторизованных пользователей или нового пользователя
        throw_if(!$user->wasRecentlyCreated && !Auth::check(), AuthorizationException::class);

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
